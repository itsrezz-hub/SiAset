@extends('layouts.dashboard')

@section('content')
<div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
        <div>
            <h3 class="text-xl font-bold text-gray-800">Manajemen Aset Sekolah</h3>
            <p class="text-sm text-gray-500">Kelola dan pantau seluruh aset inventaris sekolah.</p>
        </div>
        
        <form action="{{ route('dashboard') }}" method="GET" class="flex flex-wrap items-end gap-3 bg-gray-50 p-3 rounded-lg border border-gray-200">
            <div class="flex flex-col">
                <label class="text-[10px] font-bold text-gray-400 uppercase mb-1">Dari Tanggal</label>
                <input type="date" name="from_date" value="{{ request('from_date') }}" 
                       class="border-gray-300 rounded text-sm focus:ring-indigo-500 focus:border-indigo-500 py-1">
            </div>
            <div class="flex flex-col">
                <label class="text-[10px] font-bold text-gray-400 uppercase mb-1">Sampai Tanggal</label>
                <input type="date" name="to_date" value="{{ request('to_date') }}" 
                       class="border-gray-300 rounded text-sm focus:ring-indigo-500 focus:border-indigo-500 py-1">
            </div>
            
            <div class="flex gap-2">
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded shadow-sm hover:bg-indigo-700 text-sm transition">
                    <i class="fas fa-filter mr-1"></i> Filter
                </button>

                @php
                    $exportParams = request()->only(['from_date', 'to_date']);
                @endphp

                <a href="{{ route('aset.exportExcel', $exportParams) }}" 
                   class="px-4 py-2 bg-green-600 text-white rounded shadow-sm hover:bg-green-700 text-sm transition flex items-center">
                    <i class="fas fa-file-excel mr-2"></i> 
                    {{ request('from_date') ? 'Ekspor Filtered' : 'Ekspor Semua' }}
                </a>
            </div>
            
            @if(request('from_date'))
                <a href="{{ route('dashboard') }}" class="text-xs text-red-500 hover:underline">Reset</a>
            @endif
        </form>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 text-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm border-collapse">
            <thead class="bg-indigo-50/50 uppercase text-indigo-900 font-bold text-xs">
                <tr>
                    <th class="px-4 py-4 border-b">Detail Aset</th>
                    <th class="px-4 py-4 border-b text-center">Stok</th>
                    <th class="px-4 py-4 border-b">Nota/Gambar</th>
                    <th class="px-4 py-4 border-b text-center">Tgl Input</th>
                    <th class="px-4 py-4 border-b text-center">Aksi</th>
                    <th class="px-4 py-4 border-b">Keterangan</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($assets as $asset)
                <tr class="hover:bg-gray-50/50 transition">
                    <td class="px-4 py-4">
                        <div class="font-bold text-gray-900">{{ $asset->name_asset }}</div>
                        <div class="text-[10px] text-gray-400 font-mono">{{ $asset->asset_code }}</div>
                    </td>
                    
                    <td class="px-4 py-4 text-center">
                        <span class="px-3 py-1 bg-blue-50 text-blue-700 font-bold rounded-full border border-blue-100">
                            {{ $asset->jumlah }}
                        </span>
                    </td>

                    <td class="px-4 py-4">
                        @if($asset->receipt_file)
                            <img src="{{ asset('storage/receipts/' . $asset->receipt_file) }}" class="w-12 h-12 object-cover rounded border shadow-sm ring-1 ring-gray-200">
                        @else
                            <span class="text-gray-300 italic text-xs">No Photo</span>
                        @endif
                    </td>

                    <td class="px-4 py-4 text-center text-gray-500 text-xs whitespace-nowrap">
                        {{ $asset->created_at->format('d/m/Y') }}<br>
                        <span class="text-[10px] text-gray-400">{{ $asset->created_at->format('H:i') }}</span>
                    </td>

                    <td class="px-4 py-4">
                        <div class="flex justify-center items-center gap-2">
                            <!-- Tombol Download -->
                            @if($asset->receipt_file)
                                <a href="{{ asset('storage/receipts/' . $asset->receipt_file) }}" 
                                   download="{{ $asset->receipt_file }}" 
                                   class="text-indigo-600 hover:text-indigo-900 transition p-2 bg-indigo-50 rounded-lg" title="Download Nota">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </a>
                            @endif

                            <!-- Tombol Edit -->
                            <a href="{{ route('aset.edit', $asset->id) }}" 
                               class="text-amber-600 hover:text-amber-900 transition p-2 bg-amber-50 rounded-lg" title="Edit Aset">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </a>

                            <!-- Tombol Hapus -->
                            <form action="{{ route('aset.destroy', $asset->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus aset ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 transition p-2 bg-red-50 rounded-lg" title="Hapus Aset">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </td>

                    <td class="px-4 py-4 text-gray-500 italic text-xs max-w-[150px] truncate">
                        {{ $asset->note ?? '-' }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-4 py-10 text-center text-gray-400 italic">
                        Data aset tidak ditemukan untuk periode ini.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection