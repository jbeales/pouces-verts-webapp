<?php
namespace App\Concerns;

use Illuminate\Support\Collection;
use Revolution\Google\Sheets\Facades\Sheets as SheetsFacade;
use Revolution\Google\Sheets\Sheets;

trait InteractsWithGoogleSheets {


    /**
     * @var Sheets[] The Google Sheet instance
     */
    protected array $sheets = [];

    public function get_collection(): Collection
    {

        $collection = collect([]);
        foreach($this->sheets as $sheet) {


            $rows = $sheet->get();
            $header = $rows->pull(0);

            $collection = $collection->concat(
                $collection->concat(
                    SheetsFacade::collection($header, $rows)
                )
            );

        }

        return $collection;
    }

    public function bootSheets( array $sheets ) {

        foreach($sheets as $sheet) {
            $gsheet = SheetsFacade::spreadsheet(
                $sheet['spreadsheet_id']
            )->sheet(
                $sheet['range_id']
            );
            $this->sheets[] = $gsheet;
        }
    }

    public function firstSheet(): ?Sheets
    {
        return (!empty($this->sheets) ? $this->sheets[0] : null);
    }
}
