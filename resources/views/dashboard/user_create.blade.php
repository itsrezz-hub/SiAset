@extends('layouts.dashboard')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('users.index') }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">
            ← Kembali ke Daftar Admin
        </a>
        <h2 class="text-2xl font-bold text-gray-800 mt-2">Tambah Admin Gudang Baru</h2>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
        <form action="{{ route('users.store') }}" method="POST">
            @csrf
            
            <div class="space-y-6">
                <!-- Input Nama -->
                <div>
                    <label for="name" class="block text-sm font-semibold text-gray-700 mb-1">Nama Lengkap</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" 
                        class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm" 
                        placeholder="Masukkan nama lengkap admin" required>
                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Input Email -->
                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-1">Alamat Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" 
                        class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm" 
                        placeholder="admin@example.com" required>
                    @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Input Password -->
                <div>
                    <label for="password" class="block text-sm font-semibold text-gray-700 mb-1">Password</label>
                    <input type="password" name="password" id="password" 
                        class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm" 
                        placeholder="Minimal 8 karakter" required>
                    @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="pt-4 border-t border-gray-100">
                    <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-4 rounded-lg shadow-lg transition duration-200 transform hover:-translate-y-0.5">
                        Daftarkan Admin
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection