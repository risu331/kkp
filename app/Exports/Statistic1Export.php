<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use App\Models\DataCollection;

class Statistic1Export implements FromView, ShouldAutoSize
{

    public function __construct($data)
    {
        $this->data = $data;
    }


    public function view(): View
    {
        return view('dashboard.statistic.1.excel', [
            'data' => $this->data
        ]);
    }
}
