<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsUnknownSheets;
use App\Models\History;
use Illuminate\Support\Facades\Auth;

class ManageImport implements WithMultipleSheets,SkipsUnknownSheets
{
    use Importable;
    public $mapArray;
    public $importing_on;
    public $flag;
    public function __construct($mapArray,$importing_on,$flag){
        $this->mapArray = $mapArray;
        $this->importing_on = $importing_on;
        $this->flag= $flag;
        History::create([
            'type' => 'data',
            'imported_on' => $this->importing_on,
            'imported_by' => Auth::user()->id,
            'file_name' => session('excel')
        ]);
    }

    public function sheets(): array
    {
        return [
            'Home Plans'  => new HomePlanImport($this->mapArray->{'home_plans'},$this->importing_on,$this->flag),
            'Design Types'   => new DesignTypeImport($this->mapArray->{'design_types'},$this->importing_on,$this->flag),
            'Design Options'   => new DesignOptionImport($this->mapArray->{'design_options'},$this->importing_on,$this->flag),
        ];
    }

    public function onUnknownSheet($sheetName)
    {
        // E.g. you can log that a sheet was not found.
        info("Sheet {$sheetName} was skipped");
    }
}
