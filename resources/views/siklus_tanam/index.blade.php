@extends('layouts.app')

@section('content')
    <div class="max-w-6xl mx-auto px-4 py-8">
        {{-- Header Section --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
            <div>
                <h1 class="text-3xl font-black text-slate-800 dark:text-white uppercase tracking-tight">Monitoring Siklus
                </h1>
                <p class="text-sm font-bold text-slate-500 uppercase tracking-widest">Daftar Aktivitas Tanam Lahan Anda</p>
            </div>
            <a href="{{ route('siklus-tanam.create') }}"
                class="inline-flex items-center justify-center bg-green-600 hover:bg-green-700 text-white font-black px-6 py-4 rounded-2xl transition-all shadow-lg shadow-green-600/20 uppercase tracking-widest text-xs">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path>
                </svg>
                Mulai Siklus Baru
            </a>
        </div>

        {{-- Stats Overview (Optional) --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
            <div
                class="bg-white dark:bg-slate-900 p-6 rounded-[2rem] border border-slate-100 dark:border-slate-800 shadow-sm">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Total Siklus</p>
                <p class="text-3xl font-black text-slate-800 dark:text-white">{{ $siklus->total() }}</p>
            </div>
            {{-- Anda bisa menambahkan hitungan status aktif di sini jika perlu --}}
        </div>

        {{-- Table / Card List --}}
        <div
            class="bg-white dark:bg-slate-900 rounded-[2.5rem] shadow-2xl border border-slate-100 dark:border-slate-800 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-slate-50 dark:border-slate-800">
                            <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Lahan &
                                Komoditas</th>
                            <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Periode
                                Tanam</th>
                            <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Status
                            </th>
                            <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 dark:divide-slate-800">
                        @forelse ($siklus as $item)
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/50 transition-colors">
                                <td class="px-8 py-6">
                                    <div class="flex items-center">
                                        <div
                                            class="w-12 h-12 rounded-2xl bg-green-100 dark:bg-green-500/10 flex items-center justify-center mr-4">
                                            <span class="text-xl">{{ $item->komoditas->emoji ?? '🌿' }}</span>
                                        </div>
                                        <div>
                                            <p
                                                class="font-black text-slate-800 dark:text-white uppercase text-sm leading-tight">
                                                {{ $item->lahan->nama_lahan }}
                                            </p>
                                            <p class="text-xs font-bold text-green-600 uppercase tracking-wider">
                                                {{ $item->komoditas->nama_komoditas }}
                                            </p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="space-y-1">
                                        <div
                                            class="flex items-center text-xs font-bold text-slate-600 dark:text-slate-400 uppercase">
                                            <svg class="w-3 h-3 mr-2 text-slate-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                </path>
                                            </svg>
                                            {{ \Carbon\Carbon::parse($item->tanggal_mulai)->translatedFormat('d M Y') }}
                                        </div>
                                        <div
                                            class="flex items-center text-[10px] font-black text-slate-400 uppercase tracking-tighter">
                                            <span class="mr-2">Estimasi Panen:</span>
                                            <span
                                                class="text-slate-500 italic">{{ $item->estimasi_panen ? \Carbon\Carbon::parse($item->estimasi_panen)->translatedFormat('d M Y') : 'Belum ditentukan' }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    @if ($item->status == 'aktif')
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest bg-green-100 text-green-700 dark:bg-green-500/10 dark:text-green-400 border border-green-200 dark:border-green-500/20">
                                            <span class="w-1.5 h-1.5 rounded-full bg-green-500 mr-2 animate-pulse"></span>
                                            Aktif
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest bg-slate-100 text-slate-500 dark:bg-slate-800 dark:text-slate-400 border border-slate-200 dark:border-slate-700">
                                            Selesai
                                        </span>
                                    @endif
                                </td>
                                <td class="px-8 py-6">
                                    <div class="flex items-center gap-2">
                                        <a href="#"
                                            class="p-2 bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 rounded-xl hover:text-green-600 transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                </path>
                                            </svg>
                                        </a>
                                        {{-- Tambahkan tombol hapus/edit jika perlu --}}
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-8 py-20 text-center">
                                    <div class="flex flex-col items-center">
                                        <div
                                            class="w-20 h-20 bg-slate-50 dark:bg-slate-800 rounded-full flex items-center justify-center mb-4">
                                            <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0a2 2 0 00-2 2v1a2 2 0 00-2 2H10a2 2 0 00-2-2V15a2 2 0 00-2-2m16 0h-2M4 13H6">
                                                </path>
                                            </svg>
                                        </div>
                                        <h3
                                            class="text-lg font-black text-slate-800 dark:text-white uppercase tracking-tight">
                                            Belum Ada Siklus</h3>
                                        <p class="text-sm text-slate-400 font-bold uppercase tracking-widest mt-1">Silakan
                                            mulai tanam pertama Anda.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if ($siklus instanceof \Illuminate\Pagination\LengthAwarePaginator && $siklus->hasPages())
                <div class="px-8 py-6 bg-slate-50/50 dark:bg-slate-800/30 border-t border-slate-50 dark:border-slate-800">
                    {{ $siklus->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
