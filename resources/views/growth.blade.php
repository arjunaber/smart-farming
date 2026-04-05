@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    <div class="mb-8">
        <h1 class="text-2xl font-black text-slate-800 dark:text-white">Prediksi Pertumbuhan</h1>
        <p class="text-slate-500 dark:text-slate-400 text-sm mt-1">Pantau siklus tanaman dan perkiraan tanggal panen.</p>
    </div>

    <!-- Timeline Banner -->
    <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 p-8 shadow-sm mb-8">
        <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
            <div>
                <h2 class="text-lg font-bold text-slate-800 dark:text-white">Siklus Tanaman Padi</h2>
                <p class="text-slate-500 dark:text-slate-400 text-sm">Hari 45 dari 120 (Fase Vegetatif)</p>
            </div>
            <div class="text-right">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Perkiraan Panen</p>
                <p class="text-xl font-black text-green-600 dark:text-green-400">15 Agu 2026</p>
            </div>
        </div>

        <!-- Custom Progress Bar -->
        <div class="relative w-full h-4 bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden mb-2">
            <div class="absolute top-0 left-0 h-full bg-gradient-to-r from-green-400 to-green-600 rounded-full" style="width: 37%"></div>
        </div>
        <div class="flex justify-between text-xs font-bold text-slate-400">
            <span>Penanaman</span>
            <span class="text-green-600 dark:text-green-400">Vegetatif</span>
            <span>Reproduktif</span>
            <span>Pematangan</span>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Task Card 1 -->
        <div class="bg-white dark:bg-slate-900 rounded-[2rem] border border-slate-100 dark:border-slate-800 p-6 shadow-sm">
            <div class="w-12 h-12 bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 rounded-2xl flex items-center justify-center mb-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14C19 17.866 15.866 21 12 21C8.13401 21 5 17.866 5 14C5 10.134 12 3 12 3C12 3 19 10.134 19 14Z"></path></svg>
            </div>
            <h3 class="font-bold text-slate-800 dark:text-white mb-2">Kebutuhan Air</h3>
            <p class="text-sm text-slate-500 dark:text-slate-400">Kebutuhan tinggi selama anakan aktif. Pertahankan kedalaman air 3-5 cm.</p>
        </div>

        <!-- Task Card 2 -->
        <div class="bg-white dark:bg-slate-900 rounded-[2rem] border border-slate-100 dark:border-slate-800 p-6 shadow-sm">
            <div class="w-12 h-12 bg-green-50 dark:bg-green-500/10 text-green-600 dark:text-green-400 rounded-2xl flex items-center justify-center mb-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
            </div>
            <h3 class="font-bold text-slate-800 dark:text-white mb-2">Jadwal Pemupukan</h3>
            <p class="text-sm text-slate-500 dark:text-slate-400">Pemberian urea direkomendasikan dalam 5 hari untuk inisiasi malai maksimal.</p>
        </div>
    </div>
</div>
@endsection