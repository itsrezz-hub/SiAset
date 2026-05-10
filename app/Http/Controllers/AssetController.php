<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage; // Tambahkan ini untuk handle file
use App\Exports\TransactionsExport;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class AssetController extends Controller
{
    /**
     * --- 1. AKSES PUBLIK (Visitor) ---
     */
    public function publicIndex()
    {
        $assets = Asset::latest()->get(); 
        return view('welcome', compact('assets'));
    }

    /**
     * --- 2. DASHBOARD ADMIN ---
     */
    public function index(Request $request) 
    {
        $query = Asset::query();

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name_asset', 'like', '%' . $request->search . '%')
                  ->orWhere('asset_code', 'like', '%' . $request->search . '%');
            });
        }

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
     */
    public function monitoring(Request $request)
    {
        $query = Asset::query();

        if ($request->filled('search')) {
            $query->where('name_asset', 'like', '%' . $request->search . '%')
                  ->orWhere('asset_code', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('from_date') && $request->filled('to_date')) {
            $query->whereBetween('created_at', [
                $request->from_date . ' 00:00:00', 
                $request->to_date . ' 23:59:59'
            ]);
        }

        $assets = $query->latest()->paginate(20); 

        return view('monitoring.aset', compact('assets'));
    }

    /**
     * EKSPOR EXCEL
     */
    public function exportExcel(Request $request)
    {
        $fromDate = $request->from_date;
        $toDate = $request->to_date;

        $fileName = ($fromDate && $toDate) 
            ? "laporan-aset_{$fromDate}_to_{$toDate}.xlsx" 
            : "laporan-aset_keseluruhan_" . date('Y-m-d') . ".xlsx";

        return Excel::download(new TransactionsExport($fromDate, $toDate), $fileName);
    }

    /**
     * --- CREATE & STORE ---
     */
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

            $asset = Asset::create(array_merge($request->all(), ['receipt_file' => $path]));

            Transaction::create([
                'asset_id'    => $asset->id,
                'user_id'     => Auth::id(),
                'type'        => 'in',
                'quantity'    => $request->jumlah,
                'description' => 'Input aset baru: ' . ($request->note ?? '-'),
            ]);

            return redirect()->route('dashboard')->with('success', 'Aset Berhasil Disimpan!');

        } catch (\Exception $e) {
            Log::error("Gagal simpan aset: " . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan data.');
        }
    }

    /**
     * --- EDIT & UPDATE ---
     */
    public function edit($id)
    {
        $asset = Asset::findOrFail($id);
        return view('edit_aset', compact('asset'));
    }

    public function update(Request $request, $id)
    {
        $asset = Asset::findOrFail($id);

        $request->validate([
            'asset_code'    => 'required|unique:assets,asset_code,' . $id,
            'name_asset'    => 'required',
            'jumlah'        => 'required|integer|min:0',
            'receipt_file'  => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
        ]);

        try {
            $data = $request->all();

            if ($request->hasFile('receipt_file')) {
                // Hapus foto lama jika ada
                if ($asset->receipt_file) {
                    Storage::disk('public')->delete('receipts/' . $asset->receipt_file);
                }

                // Upload foto baru
                $file = $request->file('receipt_file');
                $safeName = str_replace(' ', '-', $request->name_asset);
                $filename = date('Y-m-d') . '-' . $safeName . '-' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->storeAs('receipts', $filename, 'public');
                $data['receipt_file'] = $filename;
            }

            $asset->update($data);

            return redirect()->route('dashboard')->with('success', 'Data aset berhasil diperbarui!');
        } catch (\Exception $e) {
            Log::error("Gagal update aset: " . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui data.');
        }
    }

    /**
     * --- DESTROY (DELETE) ---
     */
    public function destroy($id)
    {
        try {
            $asset = Asset::findOrFail($id);

            // Hapus file gambar dari storage jika ada
            if ($asset->receipt_file) {
                Storage::disk('public')->delete('receipts/' . $asset->receipt_file);
            }

            $asset->delete();

            return redirect()->route('dashboard')->with('success', 'Aset berhasil dihapus dari sistem.');
        } catch (\Exception $e) {
            Log::error("Gagal hapus aset: " . $e->getMessage());
            return redirect()->route('dashboard')->with('error', 'Gagal menghapus aset.');
        }
    }
}