@extends('layouts.dashboard')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Manajemen Admin Gudang</h2>
        <a href="{{ route('users.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-semibold shadow-md transition">
            + Tambah Admin Baru
        </a>
    </div>

    <!-- Notifikasi Sukses -->
    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 text-green-800 rounded-lg border border-green-200">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead class="bg-indigo-50 border-b border-indigo-100">
                <tr>
                    <th class="px-6 py-4 font-bold text-indigo-900 text-sm uppercase tracking-wider">Nama</th>
                    <th class="px-6 py-4 font-bold text-indigo-900 text-sm uppercase tracking-wider">Email</th>
                    <th class="px-6 py-4 font-bold text-indigo-900 text-sm uppercase tracking-wider text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 bg-white">
                @forelse($users as $user)
                <tr class="hover:bg-indigo-50/50 transition duration-150">
                    <!-- text-gray-900 memastikan tulisan hitam pekat -->
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">
                        {{ $user->name }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 font-medium">
                        {{ $user->email }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                        <form action="{{ route('users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus admin ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800 font-bold underline transition">
                                Hapus
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="px-6 py-12 text-center text-gray-500 italic bg-gray-50">
                        Belum ada Admin Gudang yang terdaftar.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection