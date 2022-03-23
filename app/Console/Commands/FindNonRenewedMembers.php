<?php

namespace App\Console\Commands;

use App\Member;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use League\Csv\Reader;

class FindNonRenewedMembers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'find:non-renewed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Takes a CSV of last year\'s members and checks if they have registered for 2022.';

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



    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Load members list
        $member = new Member();
        $non_renewed = [];

        // load the CSV
        $csv = Reader::createFromPath(storage_path('2021-members.csv'), 'r');
        $csv->setHeaderOffset(0); //set the CSV header offset

        foreach($csv as $row) {
            if(!$member->has_renewed($row['email'], $row['main phone'], $row['alternate phone'])) {
                $non_renewed[] = $row;
            }
        }

        $this->line(print_r($non_renewed, true));
        $this->line(sprintf('%d non-renewed', count($non_renewed)));

        return 0;
    }


}
