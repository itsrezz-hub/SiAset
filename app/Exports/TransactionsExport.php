<?php

namespace App\Exports;

use App\Models\Asset;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Carbon\Carbon;

class TransactionsExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $fromDate;
    protected $toDate;

    // Menerima parameter filter dari Controller
    public function __construct($fromDate = null, $toDate = null)
    {
        $this->fromDate = $fromDate;
        $this->toDate = $toDate;
    }

    /**
    * Mengambil koleksi data berdasarkan filter
    */
    public function collection()
    {
        $query = Asset::query();

        // Jika filter tanggal diisi, filter data berdasarkan created_at
        if ($this->fromDate && $this->toDate) {
            $query->whereBetween('created_at', [
                $this->fromDate . ' 00:00:00', 
                $this->toDate . ' 23:59:59'
            ]);
        }

        return $query->latest()->get();
    }

    /**
    * Mapping data ke setiap baris kolom excel
    */
    public function map($asset): array
    {
        return [
            $asset->asset_code,
            $asset->name_asset,
            $asset->jumlah,
            $asset->category,
            $asset->room,
            $asset->condition,
            // Tanggal Input per unit (tetap dipertahankan)
            Carbon::parse($asset->created_at)->translatedFormat('d/m/Y H:i'),
        ];
    }

    /**
    * Header Tabel dan Informasi Laporan
    */
    public function headings(): array
    {
        // Info rentang waktu yang sedang di-ekspor
        $periode = ($this->fromDate && $this->toDate) 
            ? "Periode: " . Carbon::parse($this->fromDate)->format('d/m/Y') . " s/d " . Carbon::parse($this->toDate)->format('d/m/Y')
            : "Periode: Semua Data";

        return [
            ['LAPORAN INVENTARIS ASET SEKOLAH'], // Baris 1
            ['Tanggal Cetak: ' . Carbon::now()->translatedFormat('d F Y H:i')], // Baris 2 (Tanggal Cetak)
            [$periode], // Baris 3 (Info Filter)
            [''], // Baris 4: Kosong
            [ // Baris 5: Header kolom
                'Kode Aset',
                'Nama Barang',
                'Jumlah/Stok',
                'Kategori',
                'Ruangan',
                'Kondisi',
                'Tanggal Input Per Unit'
            ]
        ];
    }

    /**
    * Styling Tabel
    */
    public function styles(Worksheet $sheet)
    {
        return [
            // Judul Utama (Bold & Besar)
            1 => ['font' => ['bold' => true, 'size' => 14]],
            
            // Tanggal Cetak & Periode (Italic)
            2 => ['font' => ['italic' => true]],
            3 => ['font' => ['italic' => true]],

            // Styling Header Tabel (Baris ke-5)
            5 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4F46E5'] // Warna Indigo
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ],
            ],
        ];
    }
}