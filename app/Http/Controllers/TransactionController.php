<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Asset;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class TransactionController extends Controller
{
    /**
     * Menampilkan Dashboard Utama berdasarkan Role
     */
    public function index(Request $request)
    {
        $role = Auth::user()->role;

        // 1. DASHBOARD SUPERADMIN (Manajemen User)
        if ($role === 'superadmin') {
            return view('dashboard.superadmin');
        }

        // 2. DASHBOARD ADMIN (Manajemen Barang & Laporan)
        if ($role === 'admin') {
            $query = Transaction::with(['asset', 'user']);

            // Sistem Filter Laporan untuk Transaksi
            if ($request->has('filter')) {
                switch ($request->filter) {
                    case 'harian':
                        $query->whereDate('created_at', Carbon::today());
                        break;
                    case 'mingguan':
                        $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                        break;
                    case 'bulanan':
                        $query->whereMonth('created_at', Carbon::now()->month)
                              ->whereYear('created_at', Carbon::now()->year);
                        break;
                    case 'tahunan':
                        $query->whereYear('created_at', Carbon::now()->year);
                        break;
                }
            }

            // Data riwayat transaksi
            $transactions = $query->latest()->get();

            // PENTING: Ambil data aset untuk ditampilkan di tabel admin.blade.php
            // Baris ini yang akan menghilangkan error "Undefined variable $assets"
            $assets = Asset::latest()->get();

            // Kirim variabel 'transactions' DAN 'assets' ke view
            return view('dashboard.admin', compact('transactions', 'assets'));
        }

        // 3. DASHBOARD USER BIASA (Melihat List Aset)
        return view('dashboard.user_index');
    }

    /**
     * Ekspor Laporan Excel (Untuk Admin)
     */
    public function exportExcel(Request $request) 
    {
        $filter = $request->get('filter', 'semua');
        return Excel::download(new \App\Exports\TransactionsExport($filter), "laporan_aset_{$filter}.xlsx");
    }

    /**
     * Simpan Mutasi Barang (Input dari Admin)
     */
    public function store(Request $request)
    {
        $request->validate([
            'asset_id' => 'required|exists:assets,id',
            'type'     => 'required|in:in,out',
            'quantity' => 'required|integer|min:1',
        ]);

        $asset = Asset::findOrFail($request->asset_id);

        // Validasi jika stok keluar melebihi stok yang ada
        if ($request->type == 'out' && $asset->jumlah < $request->quantity) {
            return redirect()->back()->with('error', 'Stok tidak mencukupi!');
        }

        // Catat di tabel transaksi
        Transaction::create([
            'asset_id'    => $request->asset_id,
            'user_id'     => Auth::id(),
            'type'        => $request->type,
            'quantity'    => $request->quantity,
            'description' => $request->description,
        ]);

        // Update jumlah stok di tabel assets
        if ($request->type == 'in') {
            $asset->increment('jumlah', $request->quantity);
        } else {
            $asset->decrement('jumlah', $request->quantity);
        }

        return redirect()->back()->with('success', 'Transaksi berhasil dicatat!');
    }
}