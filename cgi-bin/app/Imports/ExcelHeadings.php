<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\HeadingRowImport;
use Maatwebsite\Excel\Concerns\SkipsUnknownSheets;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;

HeadingRowFormatter::default('none');
class ExcelHeadings implements WithMultipleSheets,SkipsUnknownSheets
{
    use Importable;
    public function sheets(): array
    {
        return [
            'Home Plans'         => new HeadingRowImport(),
            'Design Types'      => new HeadingRowImport(),
            'Design Options'   => new HeadingRowImport(),
        ];
    }

    public function onUnknownSheet($sheetName){
        // E.g. you can log that a sheet was not found.
        info("Sheet {$sheetName} was skipped");
    }
}
