<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SI-ASET</title>
    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/logo1.svg') }}">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    
    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        
        .glass-card {
            background: rgba(30, 41, 59, 0.7);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .glow-input:focus {
            box-shadow: 0 0 20px rgba(79, 70, 229, 0.3);
            border-color: rgba(99, 102, 241, 0.5);
        }

        @keyframes blob {
            0% { transform: translate(0px, 0px) scale(1); }
            33% { transform: translate(30px, -50px) scale(1.1); }
            66% { transform: translate(-20px, 20px) scale(0.9); }
            100% { transform: translate(0px, 0px) scale(1); }
        }
        .animate-blob {
            animation: blob 7s infinite;
        }
        .animation-delay-2000 {
            animation-delay: 2s;
        }
    </style>
</head>
<body class="bg-[#0f172a] flex items-center justify-center min-h-screen overflow-hidden selection:bg-indigo-500/30 selection:text-indigo-200">
    
    <!-- Dekorasi Background -->
    <div class="absolute top-0 -left-4 w-96 h-96 bg-indigo-600 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob"></div>
    <div class="absolute bottom-0 -right-4 w-96 h-96 bg-purple-600 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-2000"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-64 h-64 bg-blue-600 rounded-full mix-blend-multiply filter blur-3xl opacity-10 animate-blob animation-delay-4000"></div>

    <div class="w-full max-w-md px-6 z-10">
        <!-- logo1 Digital Cube -->
        <div class="text-center mb-10 animate__animated animate__fadeInDown">
            <div class="inline-flex items-center justify-center w-20 h-20 mb-6 transform hover:scale-110 transition-transform duration-500 group">
                <!-- SVG logo1 Replacement -->
                <img src="{{ asset('images/logo1.svg') }}" 
                     class="w-full h-full drop-shadow-[0_0_20px_rgba(79,70,229,0.6)] group-hover:drop-shadow-[0_0_30px_rgba(79,70,229,0.8)]" 
                     alt="logo1 SI-ASET">
            </div>
            <h1 class="text-white text-3xl font-extrabold tracking-tight">SI-ASET</h1>
            <div class="flex items-center justify-center gap-2 mt-2">
                <span class="h-[1px] w-4 bg-indigo-500/50"></span>
                <p class="text-indigo-400 text-[10px] tracking-[0.3em] uppercase font-black">Portal Manajemen Aset</p>
                <span class="h-[1px] w-4 bg-indigo-500/50"></span>
            </div>
        </div>

        <!-- Form Card -->
        <div class="glass-card p-8 rounded-[2.5rem] shadow-2xl animate__animated animate__fadeInUp border-t border-white/20">
            <div class="mb-8">
                <h2 class="text-white text-xl font-bold">Selamat Datang Kembali</h2>
                <p class="text-slate-400 text-xs mt-1">Silakan masuk untuk mengelola inventaris.</p>
            </div>

            <form method="POST" action="{{ route('login') }}" x-data="{ loading: false }" @submit="loading = true">
                @csrf

                <!-- Email Address -->
                <div class="mb-5">
                    <label class="block text-slate-300 text-[10px] font-black mb-2 ml-1 uppercase tracking-widest">Email Admin</label>
                    <div class="relative group">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-500 group-focus-within:text-indigo-400 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path></svg>
                        </span>
                        <input type="email" name="email" value="{{ old('email') }}" required autofocus
                            class="w-full pl-11 pr-4 py-3.5 bg-slate-900/40 border border-slate-700/50 rounded-2xl text-white text-sm placeholder-slate-600 focus:outline-none transition-all glow-input"
                            placeholder="admin@informatika.com">
                    </div>
                    @error('email')
                        <p class="text-rose-400 text-[10px] mt-2 ml-1 font-bold animate__animated animate__headShake">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div class="mb-6" x-data="{ show: false }">
                    <label class="block text-slate-300 text-[10px] font-black mb-2 ml-1 uppercase tracking-widest">Password</label>
                    <div class="relative group">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-500 group-focus-within:text-indigo-400 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        </span>
                        <input :type="show ? 'text' : 'password'" name="password" required
                            class="w-full pl-11 pr-12 py-3.5 bg-slate-900/40 border border-slate-700/50 rounded-2xl text-white text-sm placeholder-slate-600 focus:outline-none transition-all glow-input"
                            placeholder="••••••••">
                        <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-500 hover:text-indigo-400 transition-colors">
                            <template x-if="!show">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            </template>
                            <template x-if="show">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.05 10.05 0 014.13-5.326m9.403 9.403l3.96 3.96m-3.96-3.96l-1.42-1.42m-1.335-1.335L9.172 9.172m-5.12 5.12l-3.96 3.96m3.96-3.96l1.42-1.42m1.335-1.335L4.828 4.828M10 10a3 3 0 013 3m0 0a3 3 0 01-3 3m0 0a3 3 0 01-3-3m0 0a3 3 0 013-3z"></path></svg>
                            </template>
                        </button>
                    </div>
                    @error('password')
                        <p class="text-rose-400 text-[10px] mt-2 ml-1 font-bold animate__animated animate__headShake">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Remember & Forgot -->
                <div class="flex items-center justify-between mb-8 px-1">
                    <label class="inline-flex items-center group cursor-pointer">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded-lg border-slate-700 bg-slate-900 text-indigo-600 focus:ring-indigo-500 focus:ring-offset-slate-900 transition-all cursor-pointer">
                        <span class="ml-2 text-[11px] font-bold text-slate-400 group-hover:text-slate-300 transition-colors uppercase tracking-wider">Ingat Sesi</span>
                    </label>
                   
                </div>

                <!-- Submit Button -->
                <button type="submit" 
                        class="w-full py-4 bg-indigo-600 hover:bg-indigo-500 text-white rounded-2xl font-black text-xs tracking-[0.2em] shadow-xl shadow-indigo-600/30 transition-all active:scale-[0.98] flex items-center justify-center group overflow-hidden relative"
                        :disabled="loading">
                    <span x-show="!loading" class="flex items-center transition-all duration-300 group-hover:gap-3">
                        MASUK KE SISTEM <span class="group-hover:translate-x-1 transition-transform">🚀</span>
                    </span>
                    
                    <span x-show="loading" x-cloak class="flex items-center">
                        <svg class="animate-spin h-5 w-5 mr-3 text-white" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        MENGAUTENTIKASI...
                    </span>
                </button>
            </form>
        </div>

        <!-- Footer -->
        <div class="text-center mt-10 animate__animated animate__fadeIn animate__delay-1s">
            <p class="text-slate-500 text-[10px] font-bold uppercase tracking-[0.2em]">
                &copy; {{ date('Y') }} Informatics Lab Team
            </p>
            <div class="flex items-center justify-center gap-2 mt-2">
                <span class="px-2 py-0.5 bg-slate-800 text-indigo-400 text-[9px] font-black rounded-md border border-slate-700">FARIN NEXUS TECH</span>
                <span class="w-1 h-1 bg-slate-700 rounded-full"></span>
                <span class="text-slate-600 text-[9px] font-bold">STABLE V2.0</span>
            </div>
        </div>
    </div>
</body>
</html>