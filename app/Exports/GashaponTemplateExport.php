<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;

class GashaponTemplateExport implements FromArray
{
    public function array(): array
    {
        return [
            ['Column 1', 'Column 2', 'Column 3'], // Add your required column headers
        ];
    }
}