<!DOCTYPE html>
<html lang="en" x-data="darkModeHandler" :class="darkMode ? 'dark' : ''">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AGA - Smart Farming System</title>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        ::-webkit-scrollbar {
            width: 5px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: #e2e8f0;
            border-radius: 10px;
        }

        .dark ::-webkit-scrollbar-thumb {
            background: #334155;
        }
    </style>

    {{-- Script Pencegah Flash Putih (Inline Ringan) --}}
    <script>
        (function() {
            const t = localStorage.getItem('theme');
            const s = window.matchMedia('(prefers-color-scheme: dark)').matches;
            if (t === 'dark' || (!t && s)) document.documentElement.classList.add('dark');
        })();
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body
    class="bg-[#FDFCFB] dark:bg-slate-950 text-slate-800 dark:text-slate-200 font-sans antialiased transition-colors duration-300">
    <div class="flex h-screen overflow-hidden">

        {{-- SIDEBAR: Hapus 'hidden lg:flex' agar muncul di semua layar --}}
        <aside
            class="w-72 bg-white dark:bg-slate-900 border-r border-gray-100 dark:border-slate-800 flex-shrink-0 flex flex-col shadow-[4px_0_24px_rgba(0,0,0,0.02)] z-20">
            <div class="p-8">
                <div class="flex items-center gap-3">
                    <div
                        class="bg-gradient-to-br from-green-600 to-green-700 p-2.5 rounded-xl shadow-lg shadow-green-100 dark:shadow-none">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M12 3v19M5 8h14M5 16h14"></path>
                        </svg>
                    </div>
                    <div class="flex flex-col">
                        <span
                            class="font-black text-2xl tracking-tighter text-slate-800 dark:text-white leading-none">AGA</span>
                        <span class="text-[10px] font-bold text-green-600 tracking-[0.3em] uppercase">System</span>
                    </div>
                </div>
            </div>

            <nav class="flex-1 px-6 space-y-1.5 py-4">
                <p
                    class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em] px-4 mb-2">
                    Dashboard
                </p>
                <a href="#"
                    class="flex items-center gap-3 px-4 py-3.5 text-slate-500 dark:text-slate-400 hover:bg-gray-50 dark:hover:bg-slate-800/50 rounded-2xl transition-all font-medium group">
                    <svg class="w-5 h-5 group-hover:text-green-600 transition-colors" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                        </path>
                    </svg>
                    Dashboard
                </a>

                <p
                    class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em] px-4 mt-6 mb-2">
                    Classification
                </p>
                <a href="#"
                    class="flex items-center gap-3 px-4 py-3.5 text-slate-500 dark:text-slate-400 hover:bg-gray-50 dark:hover:bg-slate-800/50 rounded-2xl transition-all font-medium group">
                    <svg class="w-5 h-5 group-hover:text-green-600 transition-colors" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                        </path>
                    </svg>
                    Disease
                </a>

                <p
                    class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em] px-4 mt-6 mb-2">
                    Prediction
                </p>
                <a href="#"
                    class="flex items-center gap-3 px-4 py-3.5 text-slate-500 dark:text-slate-400 hover:bg-gray-50 dark:hover:bg-slate-800/50 rounded-2xl transition-all font-medium group">
                    <svg class="w-5 h-5 group-hover:text-green-600 transition-colors" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                    Growth
                </a>

                <p
                    class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em] px-4 mt-6 mb-2">
                    Chatbot
                </p>
                <a href="#"
                    class="flex items-center gap-3 px-4 py-3.5 bg-green-50 dark:bg-green-500/10 text-green-700 dark:text-green-400 rounded-2xl font-bold transition-all border-r-4 border-green-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z">
                        </path>
                    </svg>
                    Chatbot
                </a>
            </nav>

            <div class="p-6 border-t border-slate-50 dark:border-slate-800">
                <div
                    class="flex items-center gap-3 bg-slate-50 dark:bg-slate-800/50 p-3 rounded-2xl border border-slate-100 dark:border-slate-800">
                    <div
                        class="w-10 h-10 rounded-full bg-gradient-to-tr from-green-500 to-green-700 flex items-center justify-center text-white font-bold text-xs border-2 border-white dark:border-slate-700">
                        SA</div>
                    <div class="flex flex-col overflow-hidden">
                        <span class="text-xs font-bold text-slate-800 dark:text-slate-200 truncate">Samuel Arjuna</span>
                        <span class="text-[10px] text-slate-400 font-medium">Administrator</span>
                    </div>
                </div>
            </div>
        </aside>

        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            <header
                class="h-20 bg-white/80 dark:bg-slate-900/80 backdrop-blur-md border-b border-gray-100 dark:border-slate-800 flex items-center justify-between px-8 z-10 flex-shrink-0">
                <div class="flex items-center gap-4">
                    <h2
                        class="text-sm font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest hidden md:block">
                        System / <span class="text-slate-800 dark:text-white">Dashboard</span>
                    </h2>
                </div>

                <div class="flex items-center gap-4">
                    <div class="relative hidden sm:block">
                        <input type="text"
                            class="bg-slate-50 dark:bg-slate-800 border-none rounded-xl py-2 pl-4 pr-4 text-xs w-64 dark:text-slate-200 focus:ring-2 focus:ring-green-500/20"
                            placeholder="Search data...">
                    </div>

                    {{-- TOGGLE DARK MODE --}}
                    <button @click="toggle"
                        class="p-2.5 rounded-xl bg-slate-50 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:ring-2 hover:ring-green-500/20 transition-all duration-300">
                        <template x-if="darkMode">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m12.728 0l-.707-.707M6.343 6.343l-.707-.707m12.728 12.728L5.121 5.121M19 12a7 7 0 11-14 0 7 7 0 0114 0z">
                                </path>
                            </svg>
                        </template>
                        <template x-if="!darkMode">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z">
                                </path>
                            </svg>
                        </template>
                    </button>

                    <div class="h-8 w-[1px] bg-gray-100 dark:bg-slate-800 mx-1"></div>

                    <button
                        class="p-2.5 rounded-xl bg-slate-50 dark:bg-slate-800 text-slate-400 hover:text-green-600 transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                            </path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </button>
                </div>
            </header>

            <main
                class="flex-1 overflow-y-auto p-6 md:p-10 bg-[#FDFCFB] dark:bg-slate-950 transition-colors duration-300">
                @yield('content')
            </main>
        </div>
    </div>
</body>

</html>
