@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-800 dark:text-white">Manajemen Keuangan</h1>
            <p class="text-slate-500 dark:text-slate-400 text-sm mt-1">Lacak pengeluaran pupuk, obat hama, dan operasional lahan.</p>
        </div>
        <a href="{{ route('keuangan.create') }}" class="bg-green-600 text-white px-6 py-2.5 rounded-xl font-bold hover:bg-green-700 transition-all shadow-lg flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Catat Pengeluaran
        </a>
    </div>

    @if (session('success'))
        <div class=\"bg-green-100 border border-green-200 text-green-800 px-6 py-3 rounded-2xl\">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white dark:bg-slate-900 rounded-3xl p-6 border border-slate-100 dark:border-slate-800 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 bg-red-100 text-red-600 dark:bg-red-500/20 dark:text-red-400 rounded-2xl flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path></svg>
            </div>
            <div>
                <p class="text-sm font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Total Pengeluaran</p>
                <h3 class="text-xl font-black text-slate-800 dark:text-white mt-1">Rp 2.450.000</h3>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-900 rounded-3xl p-6 border border-slate-100 dark:border-slate-800 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 bg-amber-100 text-amber-600 dark:bg-amber-500/20 dark:text-amber-400 rounded-2xl flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
            </div>
            <div>
                <p class="text-sm font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Beban Pupuk</p>
                <h3 class="text-xl font-black text-slate-800 dark:text-white mt-1">Rp 1.200.000</h3>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-900 rounded-3xl p-6 border border-slate-100 dark:border-slate-800 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 bg-purple-100 text-purple-600 dark:bg-purple-500/20 dark:text-purple-400 rounded-2xl flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
            </div>
            <div>
                <p class="text-sm font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Beban Obat Hama</p>
                <h3 class="text-xl font-black text-slate-800 dark:text-white mt-1">Rp 450.000</h3>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-900 rounded-[2rem] border border-slate-100 dark:border-slate-800 p-8 shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-200 dark:border-slate-700">
                        <th class="py-4 font-bold text-slate-700 dark:text-slate-300 text-sm">Tanggal</th>
                        <th class="py-4 font-bold text-slate-700 dark:text-slate-300 text-sm">Lahan</th>
                        <th class="py-4 font-bold text-slate-700 dark:text-slate-300 text-sm">Kategori</th>
                        <th class="py-4 font-bold text-slate-700 dark:text-slate-300 text-sm">Keterangan</th>
                        <th class="py-4 font-bold text-slate-700 dark:text-slate-300 text-sm text-right">Nominal</th>
                        <th class="py-4 font-bold text-slate-700 dark:text-slate-300 text-sm text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="border-b border-slate-100 dark:border-slate-800/50 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                        <td class="py-4 text-sm text-slate-600 dark:text-slate-400">12 Mei 2026</td>
                        <td class="py-4 text-sm font-bold text-slate-800 dark:text-white">Blok A-1</td>
                        <td class="py-4">
                            <span class="bg-amber-100 text-amber-700 dark:bg-amber-500/20 dark:text-amber-400 px-3 py-1 rounded-lg text-xs font-bold">Pupuk</span>
                        </td>
                        <td class="py-4 text-sm text-slate-600 dark:text-slate-400">Pembelian Urea 2 Karung</td>
                        <td class="py-4 text-sm font-bold text-red-600 dark:text-red-400 text-right">- Rp 600.000</td>
                        <td class="py-4 text-center">
                            <button class="text-slate-400 hover:text-red-500 transition-colors">
                                <svg class="w-5 h-5 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </td>
                    </tr>
                    
                    <tr class="border-b border-slate-100 dark:border-slate-800/50 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                        <td class="py-4 text-sm text-slate-600 dark:text-slate-400">10 Mei 2026</td>
                        <td class="py-4 text-sm font-bold text-slate-800 dark:text-white">Blok B-2</td>
                        <td class="py-4">
                            <span class="bg-purple-100 text-purple-700 dark:bg-purple-500/20 dark:text-purple-400 px-3 py-1 rounded-lg text-xs font-bold">Obat Hama</span>
                        </td>
                        <td class="py-4 text-sm text-slate-600 dark:text-slate-400">Pestisida anti wereng coklat</td>
                        <td class="py-4 text-sm font-bold text-red-600 dark:text-red-400 text-right">- Rp 250.000</td>
                        <td class="py-4 text-center">
                            <button class="text-slate-400 hover:text-red-500 transition-colors">
                                <svg class="w-5 h-5 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection