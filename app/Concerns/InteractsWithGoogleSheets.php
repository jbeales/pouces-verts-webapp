<?php
namespace App\Concerns;

use Illuminate\Support\Collection;
use Revolution\Google\Sheets\Facades\Sheets as SheetsFacade;
use Revolution\Google\Sheets\Sheets;

trait InteractsWithGoogleSheets {
    /**
     * @var Sheet[] The Google Sheet instance
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
            $this->sheets[] = SheetsFacade::spreadsheet(
                $sheet['spreadsheet_id']
            )->sheet(
                $sheet['range_id']
            );
        }
    }

    public function firstSheet(): ?Sheets
    {
        return $this->sheets[0];
    }
}
