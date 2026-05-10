<table>
    <thead>
        <tr>
            <th colspan="6" style="font-size: 16pt; font-weight: bold; text-align: center;">
                LAPORAN INVENTARIS ASET SEKOLAH
            </th>
        </tr>
        <tr>
            <th colspan="6" style="text-align: center;">
                Tanggal Cetak: {{ date('d/m/Y') }}
            </th>
        </tr>
        <tr></tr> <tr>
            <th style="background-color: #4CAF50; color: #ffffff; font-weight: bold; border: 1px solid #000;">No</th>
            <th style="background-color: #4CAF50; color: #ffffff; font-weight: bold; border: 1px solid #000;">Kode Aset</th>
            <th style="background-color: #4CAF50; color: #ffffff; font-weight: bold; border: 1px solid #000;">Nama Barang</th>
            <th style="background-color: #4CAF50; color: #ffffff; font-weight: bold; border: 1px solid #000;">Ruangan</th>
            <th style="background-color: #4CAF50; color: #ffffff; font-weight: bold; border: 1px solid #000;">Kondisi</th>
            <th style="background-color: #4CAF50; color: #ffffff; font-weight: bold; border: 1px solid #000;">Keterangan</th>
        </tr>
    </thead>
    <tbody>
        @foreach($assets as $index => $asset)
            <tr>
                <td style="border: 1px solid #000; text-align: center;">{{ $index + 1 }}</td>
                <td style="border: 1px solid #000;">{{ $asset->asset_code }}</td>
                <td style="border: 1px solid #000;">{{ $asset->name_asset }}</td>
                <td style="border: 1px solid #000;">{{ $asset->room }}</td>
                <td style="border: 1px solid #000;">{{ $asset->condition }}</td>
                <td style="border: 1px solid #000;">{{ $asset->note ?? '-' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>