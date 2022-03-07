<?php

namespace App;

use App\Concerns\InteractsWithGoogleSheets;
use Illuminate\Support\Collection;
use Serializable;

class Member implements Serializable {

    use InteractsWithGoogleSheets;

    /**
     * @var Collection The member's record from the Members spreadsheet.
     */
    protected Collection $record;

    public function __construct() {
        $this->bootSheets([[
            'spreadsheet_id' => config('google.sheets.members.spreadsheet_id'),
            'range_id' => config('google.sheets.members.range_id')
        ]]);
    }

    public function serialize() {
        return serialize($this->record);
    }
    public function unserialize($data) {
        $this->record = unserialize($data);
    }

    public function load_from_email(string $member_email) {
        // look up the member.
        $member_email = mb_strtolower($member_email);

        $member = $this->get_collection()->filter(function($member) use ($member_email) {
            return mb_strtolower($member->get('Email Address')) === $member_email;
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

    public function dump() {
        echo '<pre>';
        print_r($this->record->toArray());
        echo '</pre>';
    }

}
