<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AGA - Sistem Pertanian Pintar</title>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
        .dark ::-webkit-scrollbar-thumb { background: #334155; }
    </style>

    {{-- Script Pencegah Flash Putih --}}
    <script>
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        }
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-[#FDFCFB] dark:bg-slate-950 text-slate-800 dark:text-slate-200 font-sans antialiased transition-colors duration-300">
    
    {{-- Membungkus seluruh aplikasi dengan data Alpine CSP-Compliant --}}
    <div x-data="layout" class="flex h-screen overflow-hidden">

        {{-- SIDEBAR --}}
        <aside class="w-72 bg-white dark:bg-slate-900 border-r border-gray-100 dark:border-slate-800 flex-shrink-0 flex flex-col shadow-[4px_0_24px_rgba(0,0,0,0.02)] z-20 relative">
            <div class="p-8">
                <div class="flex items-center gap-3">
                    <div class="bg-gradient-to-br from-green-600 to-green-700 p-2.5 rounded-xl shadow-lg shadow-green-100 dark:shadow-none">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 3v19M5 8h14M5 16h14"></path>
                        </svg>
                    </div>
                    <div class="flex flex-col">
                        <span class="font-black text-2xl tracking-tighter text-slate-800 dark:text-white leading-none">AGA</span>
                        <span class="text-[10px] font-bold text-green-600 tracking-[0.1em] uppercase">Asisten Generatif Agrikultur</span>
                    </div>
                </div>
            </div>

            <nav class="flex-1 px-6 space-y-1.5 py-4 overflow-y-auto">
                <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em] px-4 mb-2">Beranda</p>
                <a href="/dashboard" class="flex items-center gap-3 px-4 py-3.5 rounded-2xl transition-all font-medium group {{ request()->is('dashboard') || request()->is('/') ? 'bg-green-50 dark:bg-green-500/10 text-green-700 dark:text-green-400 font-bold border-r-4 border-green-600' : 'text-slate-500 dark:text-slate-400 hover:bg-gray-50 dark:hover:bg-slate-800/50' }}">
                    <svg class="w-5 h-5 {{ request()->is('dashboard') || request()->is('/') ? '' : 'group-hover:text-green-600 transition-colors' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    Beranda
                </a>

                <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em] px-4 mt-6 mb-2">Klasifikasi</p>
                <a href="/disease" class="flex items-center gap-3 px-4 py-3.5 rounded-2xl transition-all font-medium group {{ request()->is('disease') ? 'bg-green-50 dark:bg-green-500/10 text-green-700 dark:text-green-400 font-bold border-r-4 border-green-600' : 'text-slate-500 dark:text-slate-400 hover:bg-gray-50 dark:hover:bg-slate-800/50' }}">
                    <svg class="w-5 h-5 {{ request()->is('disease') ? '' : 'group-hover:text-green-600 transition-colors' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    Penyakit Tanaman
                </a>

                <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em] px-4 mt-6 mb-2">Prediksi</p>
                <a href="/growth" class="flex items-center gap-3 px-4 py-3.5 rounded-2xl transition-all font-medium group {{ request()->is('growth') ? 'bg-green-50 dark:bg-green-500/10 text-green-700 dark:text-green-400 font-bold border-r-4 border-green-600' : 'text-slate-500 dark:text-slate-400 hover:bg-gray-50 dark:hover:bg-slate-800/50' }}">
                    <svg class="w-5 h-5 {{ request()->is('growth') ? '' : 'group-hover:text-green-600 transition-colors' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                    Pertumbuhan
                </a>

                <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em] px-4 mt-6 mb-2">Asisten AI</p>
                <a href="/chatbot" class="flex items-center gap-3 px-4 py-3.5 rounded-2xl transition-all font-medium group {{ request()->is('chatbot') ? 'bg-green-50 dark:bg-green-500/10 text-green-700 dark:text-green-400 font-bold border-r-4 border-green-600' : 'text-slate-500 dark:text-slate-400 hover:bg-gray-50 dark:hover:bg-slate-800/50' }}">
                    <svg class="w-5 h-5 {{ request()->is('chatbot') ? '' : 'group-hover:text-green-600 transition-colors' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                    Chatbot Tanya
                </a>

                {{-- REPORTS & SETTINGS DIKEMBALIKAN --}}
                <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em] px-4 mt-6 mb-2">Kelola</p>
                <a href="/reports" class="flex items-center gap-3 px-4 py-3.5 rounded-2xl transition-all font-medium group {{ request()->is('reports') ? 'bg-green-50 dark:bg-green-500/10 text-green-700 dark:text-green-400 font-bold border-r-4 border-green-600' : 'text-slate-500 dark:text-slate-400 hover:bg-gray-50 dark:hover:bg-slate-800/50' }}">
                    <svg class="w-5 h-5 {{ request()->is('reports') ? '' : 'group-hover:text-green-600 transition-colors' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Laporan
                </a>
                <a href="/settings" class="flex items-center gap-3 px-4 py-3.5 rounded-2xl transition-all font-medium group {{ request()->is('settings') ? 'bg-green-50 dark:bg-green-500/10 text-green-700 dark:text-green-400 font-bold border-r-4 border-green-600' : 'text-slate-500 dark:text-slate-400 hover:bg-gray-50 dark:hover:bg-slate-800/50' }}">
                    <svg class="w-5 h-5 {{ request()->is('settings') ? '' : 'group-hover:text-green-600 transition-colors' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    Pengaturan
                </a>
            </nav>

            {{-- PROFILE ADMINISTRATOR INTERAKTIF --}}
            <div class="relative p-6 border-t border-slate-50 dark:border-slate-800">
                
                <!-- Dropdown Menu Profile -->
                <div x-show="profileOpen" 
                     @click.away="closeProfile()"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 translate-y-4"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 translate-y-0"
                     x-transition:leave-end="opacity-0 translate-y-4"
                     class="absolute bottom-full left-6 right-6 mb-3 bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-2xl shadow-xl overflow-hidden z-50" style="display: none;">
                    
                    <div class="px-4 py-3 border-b border-slate-100 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50">
                        <p class="text-xs font-bold text-slate-800 dark:text-white">samuel@agrisystem.com</p>
                    </div>
                    <a href="#" class="flex items-center gap-2 px-4 py-3 text-sm text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        Profil Saya
                    </a>
                    <a href="/settings" class="flex items-center gap-2 px-4 py-3 text-sm text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        Pengaturan
                    </a>
                    <div class="h-px bg-slate-100 dark:bg-slate-700"></div>
                    <form method="POST" action="#">
                        <!-- @csrf -->
                        <button type="submit" class="w-full flex items-center gap-2 px-4 py-3 text-sm text-red-600 dark:text-red-400 font-medium hover:bg-red-50 dark:hover:bg-red-500/10 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                            Keluar
                        </button>
                    </form>
                </div>

                <!-- Tombol Profile -->
                <button @click="toggleProfile()" class="w-full flex items-center gap-3 bg-slate-50 dark:bg-slate-800/50 hover:bg-slate-100 dark:hover:bg-slate-800 p-3 rounded-2xl border border-slate-100 dark:border-slate-800 hover:ring-2 hover:ring-green-500/20 transition-all text-left outline-none">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-green-500 to-green-700 flex items-center justify-center text-white font-bold text-xs border-2 border-white dark:border-slate-700 flex-shrink-0">SA</div>
                    <div class="flex flex-col overflow-hidden flex-1">
                        <span class="text-xs font-bold text-slate-800 dark:text-slate-200 truncate">Samuel Arjuna</span>
                        <span class="text-[10px] text-slate-400 font-medium">Pengelola Sistem</span>
                    </div>
                    <svg class="w-4 h-4 text-slate-400 flex-shrink-0 transition-transform" :class="profileOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path></svg>
                </button>
            </div>
        </aside>

        {{-- MAIN CONTENT AREA --}}
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            <header class="h-20 bg-white/80 dark:bg-slate-900/80 backdrop-blur-md border-b border-gray-100 dark:border-slate-800 flex items-center justify-between px-8 z-10 flex-shrink-0">
                <div class="flex items-center gap-4">
                    <h2 class="text-sm font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest hidden md:block">
                        Sistem / <span class="text-slate-800 dark:text-white capitalize">{{ request()->path() == '/' ? 'Beranda' : request()->path() }}</span>
                    </h2>
                </div>

                <div class="flex items-center gap-4">
                    
                    {{-- SEARCH BAR INTERAKTIF --}}
                    <div class="relative hidden sm:block">
                        <div class="relative flex items-center">
                            <svg class="absolute left-3.5 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            <input type="text" 
                                   x-model="searchQuery" 
                                   @focus="openSearch()" 
                                   @click.away="closeSearch()"
                                   x-ref="searchInput"
                                   class="bg-slate-50 dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-xl py-2 pl-10 pr-4 text-xs w-64 dark:text-slate-200 focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500/50 shadow-inner transition-all placeholder-slate-400" 
                                   placeholder="Cari data, penyakit, sensor...">
                            
                            <!-- Search clear button -->
                            <button x-show="searchQuery.length > 0" @click="clearSearch()" class="absolute right-3 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200" style="display: none;">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>

                        <!-- Dropdown Hasil Pencarian -->
                        <div x-show="searchOpen && searchQuery.length > 0"
                             x-transition
                             class="absolute top-full right-0 mt-2 w-80 bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-2xl shadow-xl overflow-hidden z-50" style="display: none;">

                            <div class="p-3 text-[10px] font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50">
                                Hasil untuk "<span x-text="searchQuery" class="text-green-600 dark:text-green-400"></span>"
                            </div>

                            <!-- Hasil Pencarian -->
                            <div class="py-2 max-h-80 overflow-y-auto">
                                <template x-for="item in filteredResults" :key="item.url">
                                    <a :href="item.url" class="flex items-center gap-4 px-4 py-2 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                                        <div :class="getColor(item.icon)" class="p-2 rounded-lg" x-html="getIcon(item.icon)"></div>
                                        <div class="flex-1">
                                            <p class="text-sm font-bold text-slate-800 dark:text-white" x-text="item.title"></p>
                                            <p class="text-xs text-slate-500" x-text="item.desc"></p>
                                        </div>
                                    </a>
                                </template>

                                <!-- Pesan jika tidak ada hasil -->
                                <div x-show="filteredResults.length === 0" class="px-4 py-6 text-center" style="display: none;">
                                    <svg class="w-10 h-10 mx-auto text-slate-300 dark:text-slate-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <p class="text-sm text-slate-500 dark:text-slate-400">Tidak ditemukan hasil untuk "<span x-text="searchQuery"></span>"</p>
                                    <p class="text-xs text-slate-400 mt-1">Coba kata kunci lain: beranda, penyakit, panen, chatbot</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- DARK MODE TOGGLE INTERAKTIF --}}
                    <button @click="toggleTheme()" class="p-2.5 rounded-xl bg-slate-50 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:ring-2 hover:ring-green-500/20 transition-all duration-300 relative overflow-hidden outline-none">
                        <!-- Icon Sun (Mode Dark) -->
                        <svg x-show="darkMode" style="display: none;" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m12.728 0l-.707-.707M6.343 6.343l-.707-.707m12.728 12.728L5.121 5.121M19 12a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        
                        <!-- Icon Moon (Mode Light) -->
                        <svg x-show="!darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                    </button>

                    <div class="h-8 w-[1px] bg-gray-100 dark:bg-slate-800 mx-1"></div>

                    <!-- Notifikasi -->
                    <button class="relative p-2.5 rounded-xl bg-slate-50 dark:bg-slate-800 text-slate-400 hover:text-green-600 transition-all outline-none">
                        <span class="absolute top-2 right-2.5 w-2 h-2 bg-red-500 rounded-full border-2 border-white dark:border-slate-800"></span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                    </button>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto p-6 md:p-10 bg-[#FDFCFB] dark:bg-slate-950 transition-colors duration-300">
                @yield('content')
            </main>
        </div>
    </div>

    {{-- SCRIPT ALPINE CSP-COMPLIANT --}}
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('layout', () => ({
                darkMode: false,
                profileOpen: false,
                searchOpen: false,
                searchQuery: '',

                // Data pencarian untuk semua halaman, sensor, dan metrik
                searchItems: [
                    // === HALAMAN UTAMA ===
                    {
                        title: 'Beranda',
                        desc: 'Tinjauan kebun dan data sensor real-time',
                        icon: 'home',
                        url: '/dashboard',
                        keywords: ['beranda', 'dashboard', 'utama', 'home', 'kebun', 'sensor', 'realtime']
                    },
                    {
                        title: 'Klasifikasi Penyakit',
                        desc: 'Diagnosis penyakit tanaman dengan Vision AI',
                        icon: 'disease',
                        url: '/disease',
                        keywords: ['penyakit', 'klasifikasi', 'diagnosis', 'daun', 'hama', 'wereng', 'ai', 'vision', 'gambar', 'foto']
                    },
                    {
                        title: 'Prediksi Pertumbuhan',
                        desc: 'Pantau siklus tanaman dan estimasi panen',
                        icon: 'growth',
                        url: '/growth',
                        keywords: ['pertumbuhan', 'prediksi', 'panen', 'tanaman', 'padi', 'siklus', 'hari', 'estimasi']
                    },
                    {
                        title: 'Chatbot AGA',
                        desc: 'Tanya jawab dengan asisten AI tentang irigasi dan cuaca',
                        icon: 'chat',
                        url: '/chatbot',
                        keywords: ['chatbot', 'tanya', 'chat', 'asisten', 'ai', 'bantuan', 'aga', 'pesan', 'diskusi']
                    },
                    {
                        title: 'Laporan',
                        desc: 'Ekspor data sensor ke Excel atau PDF',
                        icon: 'report',
                        url: '/reports',
                        keywords: ['laporan', 'report', 'ekspor', 'data', 'sensor', 'excel', 'pdf', 'download', 'log']
                    },
                    {
                        title: 'Pengaturan',
                        desc: 'Konfigurasi lokasi, API BMKG, dan IoT',
                        icon: 'settings',
                        url: '/settings',
                        keywords: ['pengaturan', 'settings', 'konfigurasi', 'api', 'bmkg', 'iot', 'esp32', 'setup', 'config']
                    },

                    // === SENSOR IoT ===
                    {
                        title: 'Kelembapan Tanah',
                        desc: 'Sensor IoT - Data real-time kelembapan tanah',
                        icon: 'moisture',
                        url: '/dashboard',
                        keywords: ['kelembapan', 'tanah', 'moisture', 'soil', 'air', 'water', 'sensor', 'kadar', 'air', 'basah', 'kering']
                    },
                    {
                        title: 'pH Tanah',
                        desc: 'Sensor IoT - Tingkat keasaman tanah',
                        icon: 'ph',
                        url: '/dashboard',
                        keywords: ['ph', 'tanah', 'asam', 'basa', 'soil', 'sensor', 'derajat', 'keasaman', 'netral']
                    },

                    // === DATA BMKG ===
                    {
                        title: 'Suhu Udara',
                        desc: 'API BMKG - Suhu udara area sekitar',
                        icon: 'temp',
                        url: '/dashboard',
                        keywords: ['suhu', 'udara', 'temperatur', 'panas', 'celcius', 'bmkg', 'derajat', 'hangat', 'dingin']
                    },
                    {
                        title: 'Curah Hujan',
                        desc: 'API BMKG - Prediksi dan data curah hujan',
                        icon: 'rain',
                        url: '/dashboard',
                        keywords: ['hujan', 'curah', 'hmm', 'bmkg', 'cuaca', 'air', 'hujan', 'prediksi', 'mm']
                    },
                    {
                        title: 'Kelembapan Udara',
                        desc: 'API BMKG - Tingkat kelembapan udara',
                        icon: 'humidity',
                        url: '/dashboard',
                        keywords: ['kelembapan', 'udara', 'humidity', 'bmkg', 'cuaca', 'persen', '%', 'basah', 'kering']
                    },

                    // === FITUR LAINNYA ===
                    {
                        title: 'Rekomendasi Irigasi',
                        desc: 'Saran penyiraman dari AI berdasarkan sensor dan BMKG',
                        icon: 'irrigation',
                        url: '/dashboard',
                        keywords: ['irigasi', 'penyiraman', 'air', 'pompa', 'rekomendasi', 'saran', 'siram', 'manual', 'otomatis']
                    },
                    {
                        title: 'Lokasi Kebun',
                        desc: 'Pengaturan lokasi lahan untuk data BMKG',
                        icon: 'location',
                        url: '/settings',
                        keywords: ['lokasi', 'kebun', 'lahan', 'kota', 'kabupaten', 'bmkg', 'area', 'daerah', 'tempat', 'alamat']
                    },

                    // === ENTRI KHUSUS UNTUK KATA UMUM (Agar mudah ditemukan) ===
                    {
                        title: 'Tanah - Kelembapan',
                        desc: 'Lihat data kelembapan tanah di Beranda',
                        icon: 'moisture',
                        url: '/dashboard',
                        keywords: ['tanah']
                    },
                    {
                        title: 'Tanah - pH',
                        desc: 'Lihat data pH tanah di Beranda',
                        icon: 'ph',
                        url: '/dashboard',
                        keywords: ['tanah', 'ph']
                    },
                    {
                        title: 'Udara - Suhu',
                        desc: 'Lihat data suhu udara di Beranda',
                        icon: 'temp',
                        url: '/dashboard',
                        keywords: ['udara', 'suhu']
                    },
                    {
                        title: 'Udara - Kelembapan',
                        desc: 'Lihat data kelembapan udara di Beranda',
                        icon: 'humidity',
                        url: '/dashboard',
                        keywords: ['udara', 'kelembapan']
                    },
                    {
                        title: 'Hujan - Curah',
                        desc: 'Lihat data curah hujan di Beranda',
                        icon: 'rain',
                        url: '/dashboard',
                        keywords: ['hujan', 'curah']
                    },
                    {
                        title: 'Air - Irigasi',
                        desc: 'Lihat rekomendasi irigasi di Beranda',
                        icon: 'irrigation',
                        url: '/dashboard',
                        keywords: ['air', 'irigasi']
                    }
                ],

                get filteredResults() {
                    if (!this.searchQuery || this.searchQuery.length < 2) return [];

                    const query = this.searchQuery.toLowerCase().trim();

                    // Filter item yang mengandung query di title, keywords, atau desc
                    return this.searchItems.filter(item => {
                        const titleLower = item.title.toLowerCase();
                        const descLower = item.desc.toLowerCase();
                        const keywordsLower = item.keywords.map(k => k.toLowerCase());

                        // Cek exact match di title (prioritas utama)
                        const titleExact = titleLower.includes(query);

                        // Cek exact match di desc
                        const descExact = descLower.includes(query);

                        // Cek match di keywords - harus match sebagian dari keyword
                        const keywordMatch = keywordsLower.some(k => {
                            // Jika query adalah bagian dari keyword ATAU keyword adalah bagian dari query
                            return k.includes(query) || query.includes(k);
                        });

                        // Cek word-by-word match di title
                        const titleWords = titleLower.split(/\s+/);
                        const wordMatch = titleWords.some(word => word.includes(query));

                        return titleExact || descExact || keywordMatch || wordMatch;
                    });
                },

                getIcon(type) {
                    const icons = {
                        home: '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>',
                        disease: '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>',
                        growth: '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>',
                        chat: '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>',
                        report: '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>',
                        settings: '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>',
                        moisture: '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14C19 17.866 15.866 21 12 21C8.13401 21 5 17.866 5 14C5 10.134 12 3 12 3C12 3 19 10.134 19 14Z"></path></svg>',
                        ph: '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>',
                        temp: '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19c-5 1.5-5-2.5-7-3m14 6v-3.87a3.37 3.37 0 0 0-.94-2.61c3.14-.35 6.44-1.54 6.44-7A5.44 5.44 0 0 0 20 4.77 5.07 5.07 0 0 0 19.91 1S18.73.65 16 2.48a13.38 13.38 0 0 0-7 0C6.27.65 5.09 1 5.09 1A5.07 5.07 0 0 0 5 4.77a5.44 5.44 0 0 0-1.5 3.78c0 5.42 3.3 6.61 6.44 7A3.37 3.37 0 0 0 9 18.13V22"></path></svg>',
                        rain: '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 16.2A4.5 4.5 0 0 0 17.5 8h-1.8c-.7-2.8-3.2-5-6.2-5C5.7 3 2.8 5.8 2.8 9.3c0 .4 0 .8.1 1.2C1.2 11.2 0 13 0 15c0 2.8 2.2 5 5 5h14c1.7 0 3-1.3 3-3s-1.3-3-3-3z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 19v2m4-3v3m4-2v2"></path></svg>',
                        humidity: '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"></path></svg>',
                        irrigation: '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>',
                        location: '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>'
                    };
                    return icons[type] || icons.home;
                },

                getColor(type) {
                    const colors = {
                        home: 'bg-blue-100 text-blue-600 dark:bg-blue-500/20 dark:text-blue-400',
                        disease: 'bg-red-100 text-red-600 dark:bg-red-500/20 dark:text-red-400',
                        growth: 'bg-green-100 text-green-600 dark:bg-green-500/20 dark:text-green-400',
                        chat: 'bg-purple-100 text-purple-600 dark:bg-purple-500/20 dark:text-purple-400',
                        report: 'bg-amber-100 text-amber-600 dark:bg-amber-500/20 dark:text-amber-400',
                        settings: 'bg-slate-100 text-slate-600 dark:bg-slate-500/20 dark:text-slate-400',
                        moisture: 'bg-blue-100 text-blue-600 dark:bg-blue-500/20 dark:text-blue-400',
                        ph: 'bg-purple-100 text-purple-600 dark:bg-purple-500/20 dark:text-purple-400',
                        temp: 'bg-orange-100 text-orange-600 dark:bg-orange-500/20 dark:text-orange-400',
                        rain: 'bg-cyan-100 text-cyan-600 dark:bg-cyan-500/20 dark:text-cyan-400',
                        humidity: 'bg-teal-100 text-teal-600 dark:bg-teal-500/20 dark:text-teal-400',
                        irrigation: 'bg-indigo-100 text-indigo-600 dark:bg-indigo-500/20 dark:text-indigo-400',
                        location: 'bg-pink-100 text-pink-600 dark:bg-pink-500/20 dark:text-pink-400'
                    };
                    return colors[type] || colors.home;
                },

                init() {
                    // Deteksi tema awal
                    const savedTheme = localStorage.getItem('theme');
                    const systemDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                    this.darkMode = savedTheme === 'dark' || (!savedTheme && systemDark);

                    // Dengarkan perubahan dan terapkan class ke HTML
                    this.$watch('darkMode', (val) => {
                        if (val) {
                            document.documentElement.classList.add('dark');
                            localStorage.setItem('theme', 'dark');
                        } else {
                            document.documentElement.classList.remove('dark');
                            localStorage.setItem('theme', 'light');
                        }
                    });
                },

                // Metode ini diperlukan agar lolos keamanan CSP @alpinejs/csp
                toggleTheme() { this.darkMode = !this.darkMode; },
                toggleProfile() { this.profileOpen = !this.profileOpen; },
                closeProfile() { this.profileOpen = false; },
                openSearch() { this.searchOpen = true; },
                closeSearch() { this.searchOpen = false; },
                clearSearch() {
                    this.searchQuery = '';
                    this.$refs.searchInput.focus();
                }
            }));
        });
    </script>
</body>

</html>