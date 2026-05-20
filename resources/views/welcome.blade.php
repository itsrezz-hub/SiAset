
@extends('layouts.dashboard')
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SI-ASET | Sistem Informasi Inventaris Sekolah</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/logo1.svg') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800" rel="stylesheet" />

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .glass-nav {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
        }
        .hero-gradient {
            background: radial-gradient(circle at 50% 50%, rgba(79, 70, 229, 0.05) 0%, transparent 50%);
        }
    </style>
</head>
<body class="bg-[#f8fafc] text-slate-900 min-h-screen flex flex-col selection:bg-indigo-100 selection:text-indigo-700">
    
    <!-- NAVIGATION -->
    <nav class="glass-nav sticky top-0 z-50 border-b border-slate-200/60">
        <div class="max-w-7xl mx-auto px-6 h-20 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <!-- Digital Cube Logo -->
                <div class="w-10 h-10 flex items-center justify-center transform hover:rotate-12 transition-transform duration-300">
                    <img src="{{ asset('images/logo1.svg') }}" alt="logo1 SI-ASET" class="w-full h-auto drop-shadow-sm">
                </div>
                <div>
                    <h1 class="font-extrabold text-xl tracking-tight text-slate-800">SI-ASET</h1>
                    <p class="text-[9px] text-indigo-600 font-bold uppercase tracking-[0.2em] leading-none">Informatics Dev</p>
                </div>
            </div>
            
            <div class="flex items-center gap-6">
                <!-- Tombol khusus visitor: Selalu Login -->
                @if (Route::has('login'))
                    <a href="{{ route('login') }}" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-sm font-bold transition-all shadow-lg shadow-indigo-200 active:scale-95">
                        Log in
                    </a>
                @endif
            </div>
        </div>
    </nav>

    <main class="flex-grow max-w-7xl mx-auto w-full py-12 px-6 hero-gradient">
        <!-- HEADER SECTION -->
        <header class="text-center max-w-3xl mx-auto mb-16">
            <div class="inline-flex items-center gap-2 px-4 py-1.5 bg-white text-indigo-600 text-xs font-bold rounded-full uppercase tracking-widest border border-slate-200 shadow-sm mb-6">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-indigo-500"></span>
                </span>
                Selamat Datang Di Sistem informasi
            </div>
            <h2 class="text-4xl md:text-6xl font-black mt-6 mb-6 tracking-tight text-slate-900 leading-[1.1]">
                Inventaris <span class="text-indigo-600 italic font-medium">&</span> Aset
            </h2>
            <p class="text-lg text-slate-500 leading-relaxed font-medium">
                Sistem Informasi Inventaris Sekolah (SI-ASET) memberikan akses mudah untuk melihat, mencari, dan memantau aset sekolah secara transparan. Dengan data yang selalu diperbarui, SI-ASET membantu memastikan pengelolaan aset yang efisien dan akuntabel.
            </p>
        </header>

        <!-- STATS CARDS -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-16">
            <div class="bg-white p-7 rounded-[2rem] border border-slate-200/60 shadow-sm hover:shadow-xl hover:shadow-indigo-500/5 transition-all duration-300 text-center group">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2 group-hover:text-indigo-500 transition-colors">Total Aset</p>
                <h3 class="text-3xl font-black text-slate-800">{{ $assets->sum('jumlah') }}</h3>
            </div>
            <div class="bg-white p-7 rounded-[2rem] border border-slate-200/60 shadow-sm hover:shadow-xl hover:shadow-emerald-500/5 transition-all duration-300 text-center group">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2 group-hover:text-emerald-500 transition-colors">Kondisi Baik</p>
                <h3 class="text-3xl font-black text-emerald-500">{{ $assets->where('condition', 'Baik')->count() }}</h3>
            </div>
            <div class="bg-white p-7 rounded-[2rem] border border-slate-200/60 shadow-sm hover:shadow-xl transition-all duration-300 text-center group">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2 group-hover:text-indigo-500 transition-colors">Kategori</p>
                <h3 class="text-3xl font-black text-slate-800">{{ $assets->unique('category')->count() }}</h3>
            </div>
            <div class="bg-white p-7 rounded-[2rem] border border-slate-200/60 shadow-sm hover:shadow-xl transition-all duration-300 text-center group">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2 group-hover:text-indigo-500 transition-colors">Ruangan</p>
                <h3 class="text-3xl font-black text-slate-800">{{ $assets->unique('room')->count() }}</h3>
            </div>
        </div>

        <!-- SEARCH AREA -->
        <div class="mb-8 flex flex-col md:flex-row gap-4">
            <div class="relative flex-1 group">
                <span class="absolute inset-y-0 left-5 flex items-center text-slate-400 group-focus-within:text-indigo-500 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </span>
                <input type="text" placeholder="Cari nama barang atau kode aset..." class="w-full pl-14 pr-6 py-5 bg-white border border-slate-200 rounded-[1.5rem] focus:ring-4 focus:ring-indigo-500/5 focus:border-indigo-500 transition-all shadow-sm outline-none font-medium">
            </div>
        </div>

        <!-- TABLE SECTION -->
        <div class="bg-white rounded-[2.5rem] shadow-2xl shadow-slate-200/60 border border-slate-200/60 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50 border-b border-slate-100">
                            <th class="px-8 py-6 text-[10px] uppercase font-black text-slate-400 tracking-[0.2em]">Informasi Aset</th>
                            <th class="px-8 py-6 text-[10px] uppercase font-black text-slate-400 tracking-[0.2em] text-center">Kuantitas</th>
                            <th class="px-8 py-6 text-[10px] uppercase font-black text-slate-400 tracking-[0.2em]">Kategori</th>
                            <th class="px-8 py-6 text-[10px] uppercase font-black text-slate-400 tracking-[0.2em]">Penempatan</th>
                            <th class="px-8 py-6 text-[10px] uppercase font-black text-slate-400 tracking-[0.2em] text-center">Status Kondisi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($assets as $asset)
                            <tr class="hover:bg-slate-50/80 transition-colors group">
                                <td class="px-8 py-7">
                                    <div class="font-bold text-slate-800 group-hover:text-indigo-600 transition-colors text-base">{{ $asset->name_asset }}</div>
                                    <div class="text-[10px] font-mono text-slate-400 mt-1.5 uppercase tracking-wider flex items-center gap-1.5">
                                        <span class="w-1 h-1 bg-indigo-400 rounded-full"></span> {{ $asset->asset_code }}
                                    </div>
                                </td>
                                <td class="px-8 py-7 text-center">
                                    <span class="inline-flex items-center justify-center w-11 h-11 rounded-2xl bg-slate-100 text-slate-700 font-black text-sm group-hover:bg-indigo-600 group-hover:text-white transition-all duration-300 shadow-sm">
                                        {{ $asset->jumlah }}
                                    </span>
                                </td>
                                <td class="px-8 py-7">
                                    <span class="px-4 py-1.5 bg-indigo-50 text-indigo-600 rounded-xl text-[11px] font-bold uppercase tracking-tight border border-indigo-100 shadow-sm">
                                        {{ $asset->category }}
                                    </span>
                                </td>
                                <td class="px-8 py-7">
                                    <div class="flex items-center gap-2.5 text-sm text-slate-600 font-bold">
                                        <div class="w-8 h-8 rounded-lg bg-slate-50 flex items-center justify-center text-xs shadow-inner">📍</div>
                                        {{ $asset->room }}
                                    </div>
                                </td>
                                <td class="px-8 py-7 text-center">
                                    @php
                                        $style = match($asset->condition) {
                                            'Baik' => 'bg-emerald-50 text-emerald-600 border-emerald-100 shadow-emerald-100/50',
                                            'Rusak Ringan' => 'bg-amber-50 text-amber-600 border-amber-100 shadow-amber-100/50',
                                            default => 'bg-rose-50 text-rose-600 border-rose-100 shadow-rose-100/50',
                                        };
                                    @endphp
                                    <span class="inline-flex items-center px-5 py-2 rounded-xl text-[10px] font-black uppercase border shadow-sm {{ $style }}">
                                        {{ $asset->condition }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-8 py-24 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center mb-6 animate-pulse">
                                            <span class="text-4xl text-slate-300">📦</span>
                                        </div>
                                        <p class="text-slate-400 font-bold italic tracking-wide uppercase text-xs">Data inventaris belum tersedia di katalog publik.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <!-- FOOTER -->
    <footer class="bg-white border-t border-slate-200/60 py-16 px-6">
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-center gap-10">
            <div class="flex items-center gap-4 group cursor-default">
                <div class="w-10 h-10 flex items-center justify-center opacity-40 grayscale group-hover:opacity-100 group-hover:grayscale-0 transition-all duration-500">
                    <img src="{{ asset('images/logo1.svg') }}" alt="logo1" class="w-full h-auto">
                </div>
                <div class="opacity-40 group-hover:opacity-100 transition-opacity">
                    <span class="font-black text-lg tracking-tighter text-slate-800">SI-ASET  SYSTEM</span>
                    <p class="text-[10px] font-bold text-indigo-600 uppercase tracking-widest leading-none">Intelligence Hub</p>
                </div>
            </div>
            
            <div class="text-center">
                <p class="text-[11px] font-black text-slate-400 uppercase tracking-[0.3em] mb-2">
                    &copy; {{ date('Y') }} — School Asset Management
                </p>
                <div class="h-1 w-12 bg-indigo-500 mx-auto rounded-full"></div>
            </div>

            <div class="flex gap-4">
                <div class="px-4 py-2 rounded-2xl bg-slate-50 border border-slate-100 text-sm font-bold flex items-center gap-2">
                    <span class="text-lg">🇮🇩</span> <span class="text-slate-400 text-xs">ID</span>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>