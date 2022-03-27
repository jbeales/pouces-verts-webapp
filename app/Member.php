<?php

namespace App;

use Illuminate\Support\Facades\Log;
use Revolution\Google\Sheets\Facades\Sheets;
use Illuminate\Support\Collection;
use Serializable;

class Member implements Serializable {

    protected array $googleSheetIds;

    const paymentCols = [
        'Form Responses 1' => ['AI','AJ'],
        'Form Responses 2' => ['AF','AG']
    ];

    public function getAllMembers(): Collection
        {

        $members = collect([]);

        foreach($this->googleSheetIds as $sheetId) {
            $sheet = Sheets::spreadsheet(
                    $sheetId['spreadsheet_id']
                )->sheet(
                    $sheetId['range_id']
                );


            $rows = $sheet->get();
            if($rows->isEmpty()) {
                Log::error("Got an empty result for sheet {$sheetId['range_id']}");
                throw new \Exception("Empty result for sheet {$sheetId['range_id']}");
            }

            $header = $rows->pull(0);

            $members = $members->concat(
                Sheets::collection($header, $rows)
            );
        }
        return $members;
    }

    function has_renewed(string $email, string $mainphone, string $otherphone): bool
    {
        static $members = false;
        if(!$members) {
            $members = $this->getAllMembers();
            $members = $members->map(function($member) {
                return [
                    'email' => trim(mb_strtolower($member['Adresse Courriel'])),
                    'mainphone' => Waitlist::normalizePhoneNumber($member['Numéro de téléphone principal']),
                    'otherphone' => Waitlist::normalizePhoneNumber($member['Numéro de téléphone alternatif']),
                ];
            });
        }

        if(!empty($email) && $members->pluck('email')->contains(mb_strtolower($email))) {
            return true;
        }

        foreach([$mainphone,$otherphone] as $phone) {
            if(
                !empty($phone) && (
                    $members->pluck('mainphone')->contains(Waitlist::normalizePhoneNumber($phone))
                    || $members->pluck('otherphone')->contains(Waitlist::normalizePhoneNumber($phone))
                )

            ) {
                return true;
            }
        }
        return false;
    }


    /**
     * @var Collection The member's record from the Members spreadsheet.
     */
    protected Collection $record;

    public function __construct() {
        $this->googleSheetIds = config('google.sheets.members');
    }

    public function serialize() {
        return serialize($this->record);
    }
    public function unserialize($data) {
        $this->record = unserialize($data);
    }

    public function load_from_email(string $member_email) {
        // look up the member.
        $member_email = trim(mb_strtolower($member_email));

        $member = $this->getAllMembers()->filter(function($member) use ($member_email) {
            return mb_strtolower(trim($member->get('Adresse Courriel'))) === $member_email;
        });

        if($member->isEmpty()) {
            throw new \Exception('Member Not Found');
        }

        $this->record = $member->first();
    }

    public function has_paid():bool
    {
        return !empty( $this->record->get('Payment Amount') );
    }

    public function has_biblio_loisir(): bool
    {
        return !empty($this->record->get('Numéro de carte biblio-loisir / carte accès'));
    }

    public function get(string $key) {
        return $this->record->get($key);
    }

    public function record_payment(int $amount, string $id):bool
    {
        // Could even link to payment: https://dashboard.stripe.com/test/payments/{intent ID}
        //  If using this, the 'livemode' attribute tells us whether or not to incllude /test/

        // Column names are "Payment Amount" and "Payment Transaction"

        // Get the current user's Email.
        $email = $this->get('Adresse Courriel');
        if(empty($email)) {
            return false;
        }

        foreach($this->googleSheetIds as $sheetId) {
            $sheet = Sheets::spreadsheet(
                $sheetId['spreadsheet_id']
            )->sheet(
                $sheetId['range_id']
            );

            $rows = $sheet->get();
            $header = $rows->first();

            // Find out which column is the email address
            $email_column = array_search('Adresse Courriel', $header, true);
            if($email_column === false) {
                return false;
            }
            // get the row number that matches the member's email address.
            $user_row = $rows->search(function($item, $key) use($email, $email_column) {
                return $item[$email_column] === $email;
            });

            if(empty($user_row)) {
                return false;
            }

            $payment_cell = static::paymentCols[$sheetId['range_id']][0] . ($user_row + 1);
            $sheet->range($payment_cell)->update([[(string)$amount/100, $id, date('Y-m-d H:i:s')]]);
            return true;
        }

        return false;
    }

    public function dump() {
        echo '<pre>';
        print_r($this->record->toArray());
        echo '</pre>';
    }

}
