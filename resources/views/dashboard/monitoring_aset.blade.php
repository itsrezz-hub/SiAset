<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Exports\TransactionsExport;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class AssetController extends Controller
{
    /**
     * DASHBOARD ADMIN
     * Menampilkan daftar aset dengan filter untuk manajemen harian.
     */
    public function index(Request $request) 
    {
        $query = Asset::query();

        // 1. Search Box
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name_asset', 'like', '%' . $request->search . '%')
                  ->orWhere('asset_code', 'like', '%' . $request->search . '%');
            });
        }

        // 2. Filter Tanggal
        if ($request->filled('from_date') && $request->filled('to_date')) {
            $query->whereBetween('created_at', [
                $request->from_date . ' 00:00:00', 
                $request->to_date . ' 23:59:59'
            ]);
        }

        $assets = $query->latest()->get();

        return view('dashboard.admin', compact('assets'));
    }

    /**
     * MONITORING SUPERADMIN
     * Serupa dengan index, tapi biasanya digunakan untuk audit global.
     */
    public function monitoring(Request $request)
    {
        $query = Asset::query();

        // Fitur Search
        if ($request->filled('search')) {
            $query->where('name_asset', 'like', '%' . $request->search . '%')
                  ->orWhere('asset_code', 'like', '%' . $request->search . '%');
        }

        // Fitur Filter Tanggal
        if ($request->filled('from_date') && $request->filled('to_date')) {
            $query->whereBetween('created_at', [
                $request->from_date . ' 00:00:00', 
                $request->to_date . ' 23:59:59'
            ]);
        }

        $assets = $query->latest()->paginate(20); // Gunakan paginate untuk monitoring data besar

        return view('admin.monitoring', compact('assets'));
    }

    /**
     * EKSPOR EXCEL
     */
    public function exportExcel(Request $request)
    {
        $fromDate = $request->from_date;
        $toDate = $request->to_date;

        if ($fromDate && $toDate) {
            $fileName = "laporan-aset_{$fromDate}_to_{$toDate}.xlsx";
        } else {
            $fileName = "laporan-aset_keseluruhan_" . date('Y-m-d') . ".xlsx";
        }

        return Excel::download(new TransactionsExport($fromDate, $toDate), $fileName);
    }

    public function create() 
    {
        return view('input_aset');
    }

    public function store(Request $request) 
    {
        $request->validate([
            'asset_code'    => 'required|unique:assets,asset_code',
            'name_asset'    => 'required',
            'jumlah'        => 'required|integer|min:1',
            'receipt_file'  => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
        ]);

        try {
            $path = null;
            if ($request->hasFile('receipt_file')) {
                $file = $request->file('receipt_file');
                $safeName = str_replace(' ', '-', $request->name_asset);
                $filename = date('Y-m-d') . '-' . $safeName . '-' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->storeAs('receipts', $filename, 'public');
                $path = $filename;
            }

            $asset = Asset::create([
                'asset_code'    => $request->asset_code,
                'name_asset'    => $request->name_asset,
                'jumlah'        => $request->jumlah,
                'source_asset'  => $request->source_asset,
                'category'      => $request->category ?? 'Lainnya',
                'room'          => $request->room ?? 'Gudang',
                'condition'     => $request->condition ?? 'Baik',
                'note'          => $request->note, 
                'price'         => $request->price ?? 0,
                'purchase_date' => $request->purchase_date ?? now(),
                'receipt_file'  => $path,
            ]);

            Transaction::create([
                'asset_id'    => $asset->id,
                'user_id'     => Auth::id(),
                'type'        => 'in',
                'quantity'    => $request->jumlah,
                'description' => 'Input aset baru: ' . ($request->note ?? '-'),
            ]);

            // Sesuaikan route redirect ke 'dashboard' atau 'aset.index' sesuai web.php Anda
            return redirect()->route('dashboard')->with('success', 'Aset Berhasil Disimpan!');

        } catch (\Exception $e) {
            Log::error("Gagal simpan aset: " . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan data.');
        }
    }
}