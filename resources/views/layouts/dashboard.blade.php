<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistem Inventasi Sekolah')</title>
    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/logo1.svg') }}">
    
    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Libraries -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        
        .sidebar-link {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .sidebar-link:hover:not(.active) {
            transform: translateX(8px);
            background-color: rgba(79, 70, 229, 0.15);
        }

        .glass-header {
            background: rgba(248, 250, 252, 0.8);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

        @keyframes loading {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }
        @keyframes wave {
            0%, 100% { transform: rotate(0deg); }
            20% { transform: rotate(-10deg); }
            40% { transform: rotate(10deg); }
            60% { transform: rotate(-10deg); }
            80% { transform: rotate(10deg); }
        }
    </style>
</head>
<body class="bg-[#f8fafc] text-slate-900" x-data="{ pageLoading: true }" x-init="setTimeout(() => pageLoading = false, 800)">

    <!-- PRE-LOADER -->
    <div x-show="pageLoading" 
         x-transition:leave="transition ease-in duration-500"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-[9999] flex items-center justify-center bg-[#0f172a]">
        <div class="relative flex flex-col items-center">
            <!-- Spinner Ring -->
            <div class="w-24 h-24 border-4 border-indigo-500/20 border-t-indigo-500 rounded-full animate-spin"></div>
            
            <!-- logo1 SVG (Bouncing) -->
            <div class="absolute top-6 w-12 h-12 animate-bounce flex items-center justify-center">
                <img src="{{ asset('images/logo1.svg') }}" class="w-full h-full drop-shadow-[0_0_15px_rgba(79,70,229,0.8)]" alt="logo1">
            </div>

            <div class="mt-8 flex flex-col items-center">
                <p class="text-white font-bold tracking-[0.4em] text-[10px] uppercase animate-pulse">Memuat Sistem</p>
                <div class="w-32 h-[2px] bg-slate-800 mt-2 rounded-full overflow-hidden">
                    <div class="h-full bg-indigo-500 animate-[loading_1.5s_infinite]"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="flex h-screen overflow-hidden" x-data="{ mobileMenu: false }">
        
        <!-- SIDEBAR (Desktop) -->
        <aside class="w-72 bg-[#0f172a] text-slate-300 flex-shrink-0 hidden md:flex flex-col shadow-2xl z-20 animate__animated animate__fadeInLeft">
            <div class="p-8">
                <div class="flex items-center space-x-3">
                    <!-- logo1 Area -->
                    <div class="w-10 h-10 flex items-center justify-center transform hover:rotate-12 transition-transform duration-300">
                        <img src="{{ asset('images/logo1.svg') }}" alt="logo1 SI-ASET" class="w-full h-auto drop-shadow-md">
                    </div>
                    <div>
                        <h1 class="text-white font-extrabold text-xl tracking-tight">SI-ASET</h1>
                        <p class="text-[10px] text-slate-500 uppercase tracking-[0.2em] font-bold">Informatics Dev</p>
                    </div>
                </div>
            </div>
            
            <nav class="flex-1 px-4 space-y-1 overflow-y-auto custom-scrollbar">
                <div class="px-4 py-3 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Utama</div>
                
                <a href="{{ route('dashboard') }}" 
                   class="sidebar-link group flex items-center py-3 px-4 rounded-xl text-sm font-medium {{ request()->routeIs('dashboard') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30 active' : 'hover:text-white' }}">
                    <span class="mr-3 text-lg group-hover:scale-125 transition-transform duration-300">🏠</span> 
                    Dashboard
                </a>

                @auth
                    @if(auth()->user()->role === 'superadmin')
                        <div class="px-4 pt-6 pb-2 text-[10px] font-bold text-slate-500 uppercase tracking-widest border-t border-slate-800/50 mt-4">Sistem</div>
                        <a href="{{ route('users.index') }}" 
                           class="sidebar-link group flex items-center py-3 px-4 rounded-xl text-sm font-medium {{ request()->routeIs('users.*') ? 'bg-indigo-600 text-white shadow-lg active' : 'hover:text-white' }}">
                            <span class="mr-3 text-lg group-hover:rotate-12 transition-transform">👥</span> 
                            Kelola Admin
                        </a>
                    @endif

                    @if(auth()->user()->role === 'admin')
                        <div class="px-4 pt-6 pb-2 text-[10px] font-bold text-slate-500 uppercase tracking-widest border-t border-slate-800/50 mt-4">Inventaris</div>
                        <a href="{{ route('aset.create') }}" 
                           class="sidebar-link group flex items-center py-3 px-4 rounded-xl text-sm font-medium {{ request()->routeIs('aset.create') ? 'bg-indigo-600 text-white shadow-lg active' : 'hover:text-white' }}">
                            <span class="mr-3 text-lg group-hover:scale-125 transition-transform">➕</span> 
                            Input Aset Baru
                        </a>
                    @endif
                @endauth

                @guest
                    <div class="mt-8 px-4">
                        <div class="bg-slate-800/40 p-5 rounded-2xl border border-slate-700/50 backdrop-blur-sm">
                            <p class="text-[11px] text-slate-400 mb-4 text-center leading-relaxed">Akses terbatas. Login untuk kelola aset.</p>
                            <a href="{{ route('login') }}" class="block py-2.5 px-4 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-center text-xs font-bold shadow-lg transition-all active:scale-95">
                                🔑 Login Petugas
                            </a>
                        </div>
                    </div>
                @endguest
            </nav>

            @auth
            <div class="p-6 bg-[#0a0f1d]/80 border-t border-slate-800 backdrop-blur-md">
                <div class="flex items-center space-x-3 mb-4 px-2">
                    <div class="relative">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-indigo-600 to-purple-500 flex items-center justify-center text-white font-bold text-sm shadow-md border-2 border-slate-800">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                        <div class="absolute bottom-0 right-0 w-3 h-3 bg-emerald-500 border-2 border-[#0a0f1d] rounded-full"></div>
                    </div>
                    <div class="overflow-hidden">
                        <p class="text-sm font-bold text-white truncate">{{ auth()->user()->name }}</p>
                        <p class="text-[10px] text-indigo-400 font-bold tracking-tighter uppercase">{{ auth()->user()->role }}</p>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-2">
                    <a href="{{ route('profile.edit') }}" class="text-[11px] py-2.5 px-3 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-xl text-center transition-all">Profil</a>
                    <form method="POST" action="{{ route('logout') }}" x-data>
                        @csrf
                        <button type="submit" @click.prevent="if(confirm('Keluar sistem?')) $el.closest('form').submit()" class="w-full text-[11px] py-2.5 px-3 bg-red-500/10 hover:bg-red-500/20 text-red-500 rounded-xl text-center transition-all font-bold">Logout</button>
                    </form>
                </div>
            </div>
            @endauth
        </aside>

        <!-- CONTENT AREA -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            
            <!-- NAVBAR -->
            <header class="glass-header h-16 border-b border-slate-200/60 px-6 flex items-center justify-between sticky top-0 z-10 shadow-sm">
                <div class="flex items-center">
                    <button @click="mobileMenu = true" class="md:hidden mr-4 p-2 text-slate-600 hover:bg-slate-100 rounded-xl transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </button>
                    <div class="hidden sm:block">
                        <h2 class="text-slate-800 font-bold text-base flex items-center">
                            Halo, <span class="text-indigo-600 ml-1.5">{{ auth()->check() ? explode(' ', auth()->user()->name)[0] : 'Guest' }}</span> 
                            <span class="ml-2 animate-[wave_2.5s_infinite]">👋</span>
                        </h2>
                    </div>
                </div>
                
                <div class="flex items-center space-x-4">
                    <div class="bg-white border border-slate-200 py-1.5 px-4 rounded-full shadow-sm">
                        <p class="text-[11px] font-bold text-slate-500 uppercase tracking-widest">
                            <span class="text-indigo-600">●</span> {{ now()->translatedFormat('d F Y') }}
                        </p>
                    </div>
                </div>
            </header>

            <!-- MAIN CONTENT -->
            <main class="flex-1 overflow-y-auto bg-[#f8fafc] scroll-smooth">
                <div class="p-6 md:p-10 max-w-7xl mx-auto">
                    
                    @if(session('success'))
                        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
                             class="mb-8 p-4 bg-emerald-500 text-white rounded-2xl shadow-lg shadow-emerald-500/20 flex items-center justify-between animate__animated animate__fadeInDown">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center mr-3">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                </div>
                                <span class="font-bold text-sm">{{ session('success') }}</span>
                            </div>
                            <button @click="show = false" class="text-white/60 hover:text-white uppercase text-[10px] font-bold">Tutup</button>
                        </div>
                    @endif

                    <div x-show="!pageLoading" class="animate__animated animate__fadeIn animate__faster">
                        @yield('content')
                    </div>

                    <!-- Skeleton Placeholder -->
                    <div x-show="pageLoading" class="space-y-6">
                        <div class="h-10 bg-slate-200 rounded-xl w-1/4 animate-pulse"></div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="h-32 bg-slate-200 rounded-3xl animate-pulse"></div>
                            <div class="h-32 bg-slate-200 rounded-3xl animate-pulse"></div>
                            <div class="h-32 bg-slate-200 rounded-3xl animate-pulse"></div>
                        </div>
                        <div class="h-64 bg-slate-200 rounded-3xl animate-pulse w-full"></div>
                    </div>
                    
                </div>
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>