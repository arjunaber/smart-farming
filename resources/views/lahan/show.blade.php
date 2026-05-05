@extends('layouts.app')

@section('content')
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-6 text-slate-800 dark:text-slate-200">
        <div class="space-y-6">

            {{-- Header Section --}}
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div class="flex items-center gap-4">
                    <a href="{{ route('lahan.index') }}"
                        class="p-3 bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 hover:bg-slate-50 transition">
                        <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </a>
                    <div>
                        <div
                            class="flex items-center gap-2 text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">
                            <span>Lahan</span>
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M9 5l7 7-7 7" stroke-width="3"></path>
                            </svg>
                            <span>Detail Monitoring</span>
                        </div>
                        <h1 class="text-2xl md:text-3xl font-black text-slate-800 dark:text-white uppercase tracking-tight">
                            {{ $lahan->nama_lahan }}
                        </h1>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <a href="{{ route('lahan.edit', $lahan) }}"
                        class="px-4 py-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg font-bold text-sm hover:bg-slate-50 transition">Edit</a>
                    <a href="{{ route('logbook.create', ['lahan_id' => $lahan->id]) }}"
                        class="px-4 py-2 bg-green-600 text-white rounded-lg font-bold text-sm hover:bg-green-700 shadow-lg shadow-green-600/20 transition">+
                        Logbook</a>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- Kiri: Info Utama --}}
                <div class="lg:col-span-2 space-y-6">

                    {{-- Parameter Card --}}
                    <div
                        class="bg-white dark:bg-slate-900 rounded-2xl p-6 border border-slate-100 dark:border-slate-800 shadow-sm">
                        <div class="flex items-center gap-2 mb-6">
                            <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                            <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest">Parameter Utama Lahan
                            </h3>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                            <div>
                                <label class="text-[10px] font-black text-slate-400 uppercase">Luas Area</label>
                                <p class="text-3xl font-black mt-1">{{ number_format($lahan->luas, 2) }} <span
                                        class="text-sm font-medium text-slate-400">Ha</span></p>
                            </div>
                            <div>
                                <label class="text-[10px] font-black text-slate-400 uppercase">Status Operasional</label>
                                <div class="mt-2">
                                    <span
                                        class="px-3 py-1 bg-green-100 dark:bg-green-500/10 text-green-600 dark:text-green-400 text-xs font-black rounded-full border border-green-200 dark:border-green-500/20 uppercase">
                                        {{ $lahan->status ?? 'AKTIF' }}
                                    </span>
                                </div>
                            </div>
                            <div>
                                <label class="text-[10px] font-black text-slate-400 uppercase">Lokasi Presisi</label>
                                <p class="text-sm font-bold mt-1 text-slate-600 dark:text-slate-300">{{ $lahan->lokasi }}
                                </p>
                            </div>
                        </div>

                        <div class="mt-8 pt-6 border-t border-slate-50 dark:border-slate-800">
                            <label class="text-[10px] font-black text-slate-400 uppercase block mb-2">Catatan /
                                Deskripsi</label>
                            <div
                                class="bg-slate-50 dark:bg-slate-800/50 p-4 rounded-xl text-sm italic text-slate-500 dark:text-slate-400">
                                "{{ $lahan->deskripsi ?? 'Tidak ada deskripsi.' }}"
                            </div>
                        </div>
                    </div>

                    {{-- Komoditas Card --}}
                    <div
                        class="bg-white dark:bg-slate-900 rounded-2xl p-6 border border-slate-100 dark:border-slate-800 shadow-sm">
                        <h3 class="text-sm font-black mb-5 uppercase tracking-tight">Komoditas Teranam</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @forelse($lahan->komoditas as $item)
                                <div
                                    class="flex items-center gap-4 p-4 bg-slate-50 dark:bg-slate-800/40 rounded-2xl border border-transparent hover:border-green-500/30 transition-all">
                                    <div class="text-3xl">{{ $item['emoji'] ?? '🌱' }}</div>
                                    <div>
                                        <p class="font-black text-slate-800 dark:text-white">{{ $item['nama'] ?? '-' }}</p>
                                        <p class="text-[10px] font-bold text-green-600 uppercase tracking-widest">Fase:
                                            {{ $item['fase'] ?? '-' }}</p>
                                    </div>
                                </div>
                            @empty
                                <p class="text-sm text-slate-400 col-span-full text-center py-4">Belum ada data tanaman.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- Kanan: Logbook & IoT --}}
                <div class="space-y-6">

                    {{-- Timeline Logbook (Fixed Overlap) --}}
                    <div
                        class="bg-white dark:bg-slate-900 rounded-2xl p-6 border border-slate-100 dark:border-slate-800 shadow-sm">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-sm font-black uppercase">Riwayat Logbook</h3>
                            <span
                                class="text-[10px] bg-slate-100 dark:bg-slate-800 px-2 py-1 rounded font-bold text-slate-400">TERBARU</span>
                        </div>

                        <div class="space-y-6 relative border-l-2 border-slate-100 dark:border-slate-800 ml-2">
                            @forelse($lahan->logbookEntries as $entry)
                                <div class="relative pl-6">
                                    {{-- Dot Indicator --}}
                                    <div
                                        class="absolute -left-[9px] top-1 w-4 h-4 bg-white dark:bg-slate-900 border-2 {{ $entry->tipe == 'hama' ? 'border-red-500' : 'border-green-500' }} rounded-full">
                                    </div>

                                    <div class="flex flex-col">
                                        <span class="text-[10px] font-black text-slate-400 uppercase mb-1">
                                            {{ $entry->tanggal->format('d M Y') }}
                                        </span>
                                        <h4 class="text-sm font-black text-slate-800 dark:text-white leading-tight">
                                            {{ ucfirst($entry->tipe) }}
                                        </h4>
                                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 italic leading-relaxed">
                                            "{{ $entry->detail }}"
                                        </p>
                                    </div>
                                </div>
                            @empty
                                <p class="text-xs text-slate-500 text-center py-2 pl-4">Belum ada aktivitas.</p>
                            @endforelse
                        </div>
                    </div>

                    {{-- AGA IoT Widget --}}
                    <div class="bg-slate-900 rounded-[2.5rem] p-8 shadow-2xl relative overflow-hidden group">
                        <div class="absolute -top-12 -right-12 w-32 h-32 bg-green-500/20 blur-3xl rounded-full"></div>

                        <div class="relative z-10">
                            <p class="text-[10px] font-black text-green-500 uppercase tracking-[0.3em] mb-6">AGA Smart
                                Farming</p>

                            <div class="space-y-6">
                                <div>
                                    <div class="flex justify-between mb-2">
                                        <span class="text-xs text-slate-400 font-bold">Kelembapan Tanah</span>
                                        <span class="text-lg font-mono font-black text-white">65%</span>
                                    </div>
                                    <div class="w-full bg-slate-800 h-2 rounded-full">
                                        <div class="bg-green-500 h-full rounded-full" style="width: 65%"></div>
                                    </div>
                                </div>

                                <div>
                                    <div class="flex justify-between mb-2">
                                        <span class="text-xs text-slate-400 font-bold">pH Tanah</span>
                                        <span class="text-lg font-mono font-black text-white">6.2</span>
                                    </div>
                                    <div class="w-full bg-slate-800 h-2 rounded-full">
                                        <div class="bg-orange-500 h-full rounded-full" style="width: 50%"></div>
                                    </div>
                                </div>

                                <div class="pt-6 flex items-center justify-center border-t border-slate-800/50">
                                    <span class="flex h-2 w-2 rounded-full bg-green-500 mr-3 animate-ping"></span>
                                    <p class="text-[9px] text-slate-500 font-black tracking-widest uppercase">Sistem Online
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
