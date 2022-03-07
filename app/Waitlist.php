<?php

namespace App;

use App\Concerns\InteractsWithGoogleSheets;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Revolution\Google\Sheets\Sheets;
use Revolution\Google\Sheets\Facades\Sheets as SheetsFacade;


class Waitlist {

    use InteractsWithGoogleSheets;

    public static function instance() {
        static $waitlist;
        if(empty($waitlist)) {
            $waitlist = new static;
        }
        return $waitlist;
    }


    /**
    @function normalizePhoneNumber($number)
    @param (string) $number : The phone number to normalize.

    - Normalizes a phone number so that we can compare it to other normalized phone numbers.
    - Removes all non-numerical digits from the phone number.
    - Removes the first digit if it is a 0 or 1

    @return (int) : The normalized phone number.

     */
    public static function normalizePhoneNumber($number) {

        $number = preg_replace('~^[01]~','',preg_replace('~\D~','',$number));
        return $number;

    }

    public function is_already_on_list(string $email, string $phone): bool
    {
        $phone = static::normalizePhoneNumber($phone);
        $email = mb_strtolower($email);
        return $this->get_collection()->contains(function($person) use($email, $phone) {
            if(!empty($person->get('Email')) && mb_strtolower($person->get('Email')) === $email) {
                return true;
            }

            if(static::normalizePhoneNumber($person->get('Phone')) === $phone) {
                return true;
            }
            return false;

        });
    }

    public function add(string $name, string $phone, string $email, string $language, string $notes ) {
        if( $this->is_already_on_list($email, $phone)) {
            throw new \Exception('Already on list', 1);
        }

        $this->firstSheet()->append([[
            'Name' => $name,
            'Phone' => $phone,
            'Email' => !empty($email) ? $email : '',
            'Language' => mb_strtoupper($language),
            'Signup Date' => Carbon::now()->format('j F Y'),
            'Notes' => $notes,
        ]]);

        return true;
    }

    public function __construct() {
        $this->bootSheets([[
            'spreadsheet_id' => config('google.sheets.waitlist.spreadsheet_id'),
            'range_id' => config('google.sheets.waitlist.range_id')
        ]]);
    }
}
