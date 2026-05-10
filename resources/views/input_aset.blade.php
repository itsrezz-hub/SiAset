<!DOCTYPE html>
<html>
<head>
    <title>Tambah Aset - SI-ASET</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-light">
<div class="container mt-5 mb-5">
    <div class="card shadow border-0">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold">Tambah Aset Baru</h5>
            <a href="{{ route('dashboard') }}" class="btn btn-light btn-sm fw-bold text-primary">
                ⬅ Kembali ke Dashboard
            </a>
        </div>
        <div class="card-body p-4">

            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Penyimpanan Gagal:</strong>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('aset.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-semibold">Kode Aset</label>
                        <input type="text" name="asset_code" class="form-control" value="{{ old('asset_code') }}" placeholder="Contoh: KMP-001" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-semibold">Nama Barang</label>
                        <input type="text" name="name_asset" class="form-control" value="{{ old('name_asset') }}" placeholder="Contoh: Kursi Lipat" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-semibold">Jumlah Masuk</label>
                        <input type="number" name="jumlah" class="form-control" value="{{ old('jumlah', 1) }}" min="1" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-semibold">Kategori</label>
                        <select name="category" class="form-select" required>
                            <option value="Elektronik" {{ old('category') == 'Elektronik' ? 'selected' : '' }}>Elektronik</option>
                            <option value="Meubel" {{ old('category') == 'Meubel' ? 'selected' : '' }}>Meubel</option>
                            <option value="Alat Peraga" {{ old('category') == 'Alat Peraga' ? 'selected' : '' }}>Alat Peraga</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-semibold">Lokasi Ruangan</label>
                        <input type="text" name="room" class="form-control" value="{{ old('room') }}" placeholder="Contoh: Ruang Guru" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-semibold">Kondisi</label>
                        <select name="condition" class="form-select" required>
                            <option value="Baik" {{ old('condition') == 'Baik' ? 'selected' : '' }}>Baik</option>
                            <option value="Rusak Ringan" {{ old('condition') == 'Rusak Ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-semibold">Harga Per Unit (Rp)</label>
                        <input type="text" id="rupiah" class="form-control" placeholder="Rp 0" required>
                        <input type="hidden" name="price" id="price_real">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-semibold">Tanggal Beli</label>
                        <input type="date" name="purchase_date" class="form-control" value="{{ old('purchase_date') }}" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-semibold">Sumber Barang</label>
                        <input type="text" name="source_asset" class="form-control" value="{{ old('source_asset') }}" placeholder="Contoh: Hibah Alumni" required>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold">Upload Nota (Format bebas, Maks 5MB)</label>
                    <input type="file" name="receipt_file" class="form-control">
                </div>

                <div class="mt-4">
                    <label for="note" class="block font-medium text-sm text-gray-700">Keterangan / Note</label>
                    <textarea name="note" id="note" class="w-full border-gray-300 rounded-md shadow-sm" rows="3">{{ old('note') }}</textarea>
                </div>
                <button type="submit" class="btn btn-success btn-lg w-100 fw-bold shadow-sm">Simpan Aset & Update Stok</button>
            </form>
        </div>
    </div>
</div>

<script>
    /* 1. Fungsi Format Rupiah saat Mengetik */
    const rupiahInput = document.getElementById('rupiah');
    const priceRealInput = document.getElementById('price_real');

    rupiahInput.addEventListener('keyup', function(e) {
        // Ambil angka saja untuk input hidden
        let rawValue = this.value.replace(/[^0-9]/g, '');
        priceRealInput.value = rawValue;

        // Tampilkan format rupiah ke user
        this.value = formatRupiah(this.value, 'Rp ');
    });

    function formatRupiah(angka, prefix) {
        let number_string = angka.replace(/[^,\d]/g, '').toString(),
            split = number_string.split(','),
            sisa = split[0].length % 3,
            rupiah = split[0].substr(0, sisa),
            ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        if (ribuan) {
            separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }

        rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
        return prefix == undefined ? rupiah : (rupiah ? 'Rp ' + rupiah : '');
    }

    /* 2. SweetAlert Notifikasi */
    @if(session('success'))
        Swal.fire({ icon: 'success', title: 'Berhasil', text: "{{ session('success') }}" });
    @endif

    @if(session('error'))
        Swal.fire({ icon: 'error', title: 'Gagal', text: "{{ session('error') }}" });
    @endif
</script>
</body>
</html>