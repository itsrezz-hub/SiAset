<?php

namespace App\Exports;

use App\Models\Asset;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class AssetExport implements FromView, ShouldAutoSize
{
    public function view(): View
    {
        return view('dashboard.export_excel', [
            'assets' => Asset::all()
        ]);
    }
}