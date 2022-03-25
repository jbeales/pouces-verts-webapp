<?php

namespace App\Console\Commands;

use App\Member;
use App\Waitlist;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use League\Csv\Reader;
use Twilio\Rest\Client;

class CallNonRenewedMembers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'call:non-renewed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Takes a CSV of last year\'s members and phones them if they haven\'t renewed for 2022';

    // This year's members
    protected Collection $members;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    protected function getNumbersToCall(): array
    {
        // Load members list
        $member = new Member();
        $non_renewed = [];

        // load the CSV
        $csv = Reader::createFromPath(storage_path('app/2021-members.csv'), 'r');
        $csv->setHeaderOffset(0); //set the CSV header offset

        foreach($csv as $row) {
            if(!$member->has_renewed($row['email'], $row['main phone'], $row['alternate phone'])) {
                $non_renewed[] = $row;
            }
        }

        $this->line(print_r($non_renewed, true));
        $this->line(sprintf('%d non-renewed', count($non_renewed)));
        return array_map(
            [Waitlist::class,'normalizePhoneNumber'],
            array_filter(
                array_column($non_renewed, 'main phone')
            )
        );
    }

    public static function formatNumberForTwilio($number) {
        $number = Waitlist::normalizePhoneNumber($number);
        return '+1' . $number;
    }



    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $numbers_to_call = $this->getNumbersToCall();
        Storage::put('2022-renewal-robocall-recipients.txt', implode("\n",$numbers_to_call));


        $client = new Client(config('services.twilio.sid'), config('services.twilio.token'));

        // safety
        exit;

        foreach($numbers_to_call as $number) {
            // Read TwiML at this URL when a call connects (hold music)
            $call = $client->calls->create(
                static::formatNumberForTwilio($number), // Call this number
                static::formatNumberForTwilio(config('services.twilio.number')),
                [
                    'url' => 'https://cadet-collie-6554.twil.io/assets/robocall-2022.xml',
                    "MachineDetection" => "Enable"
                ]
            );
        }


        return 0;
    }


}
