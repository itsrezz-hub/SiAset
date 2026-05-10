@extends('layouts.dashboard')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
        <!-- Header -->
        <div class="bg-indigo-600 px-6 py-4">
            <h3 class="text-xl font-bold text-white">Edit Data Aset</h3>
            <p class="text-indigo-100 text-sm">Perbarui informasi inventaris sekolah.</p>
        </div>

        <!-- Form -->
        <form action="{{ route('aset.update', $asset->id) }}" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Kode Aset -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Kode Aset</label>
                    <input type="text" name="asset_code" value="{{ old('asset_code', $asset->asset_code) }}" 
                           class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('asset_code') border-red-500 @enderror">
                    @error('asset_code') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Nama Aset -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Aset</label>
                    <input type="text" name="name_asset" value="{{ old('name_asset', $asset->name_asset) }}" 
                           class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <!-- Jumlah -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Jumlah (Stok)</label>
                    <input type="number" name="jumlah" value="{{ old('jumlah', $asset->jumlah) }}" 
                           class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <!-- Harga Satuan dengan Format Rupiah -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Harga Satuan</label>
                    <div class="relative mt-1 rounded-md shadow-sm">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                            <span class="text-gray-500 sm:text-sm">Rp</span>
                        </div>
                        <input type="text" 
                               id="price_display"
                               value="{{ old('price', number_format($asset->price, 0, ',', '.')) }}" 
                               class="w-full border-gray-300 rounded-lg pl-10 focus:ring-indigo-500 focus:border-indigo-500 @error('price') border-red-500 @enderror" 
                               placeholder="0"
                               inputmode="numeric">
                        
                        <!-- Input Hidden untuk mengirim angka murni ke Database -->
                        <input type="hidden" name="price" id="price_real" value="{{ old('price', $asset->price) }}">
                    </div>
                    @error('price') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Upload Nota/Gambar Baru -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Ganti Nota/Gambar (Opsional)</label>
                    @if($asset->receipt_file)
                        <div class="mb-2">
                            <p class="text-xs text-gray-400 mb-1">File saat ini:</p>
                            <img src="{{ asset('storage/receipts/' . $asset->receipt_file) }}" class="w-32 h-32 object-cover rounded border">
                        </div>
                    @endif
                    <input type="file" name="receipt_file" 
                           class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                </div>

                <!-- Keterangan/Note -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Keterangan</label>
                    <textarea name="note" rows="3" 
                              class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">{{ old('note', $asset->note) }}</textarea>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="mt-8 flex items-center justify-end gap-3 border-t pt-6">
                <a href="{{ route('dashboard') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition text-sm">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-lg font-bold hover:bg-indigo-700 shadow-md transition text-sm">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    /* Script untuk Masking Rupiah Otomatis */
    const priceDisplay = document.getElementById('price_display');
    const priceReal = document.getElementById('price_real');

    priceDisplay.addEventListener('input', function(e) {
        // Ambil angka saja
        let numberString = this.value.replace(/[^,\d]/g, '').toString();
        let split = numberString.split(',');
        let sisa = split[0].length % 3;
        let rupiah = split[0].substr(0, sisa);
        let ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        if (ribuan) {
            let separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }

        rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
        
        // Tampilkan ke UI
        this.value = rupiah;

        // Masukkan angka bersih (tanpa titik) ke input hidden untuk dikirim ke server
        priceReal.value = numberString.replace(/\./g, '').replace(/,/g, '.');
    });
</script>
@endsection