@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        {{-- Header Section --}}
        <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-4">
            <div>
                <h1 class="text-2xl font-black text-slate-800 dark:text-white">Tinjauan Lahan</h1>
                <p class="text-slate-500 dark:text-slate-400 text-sm mt-1">
                    Lokasi: <span class="text-green-600 font-bold">{{ $area }}</span>
                </p>
            </div>

            {{-- Tombol Ganti Lahan --}}
            <button type="button" onclick="document.getElementById('lahanModal').classList.remove('hidden')"
                class="group bg-white dark:bg-slate-800 border-2 border-slate-100 dark:border-slate-700 p-1.5 pr-5 rounded-2xl flex items-center gap-3 shadow-sm hover:border-green-500 transition-all">
                <div class="bg-green-500 text-white p-2 rounded-xl shadow-lg shadow-green-500/30">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7">
                        </path>
                    </svg>
                </div>
                <div class="text-left">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Lahan Terpilih</p>
                    <p class="text-sm font-black text-slate-700 dark:text-white">{{ $lahan->nama_lahan ?? 'Pilih Lahan' }}
                    </p>
                </div>
            </button>
        </div>

        {{-- Info Utama --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div
                class="bg-white dark:bg-slate-900 rounded-[2rem] border border-slate-100 dark:border-slate-800 p-6 flex items-center gap-4">
                <div class="bg-green-100 dark:bg-green-500/20 p-4 rounded-2xl text-3xl">🌾</div>
                <div>
                    <h3 class="text-xs font-bold text-slate-400 uppercase mb-1">Komoditas</h3>
                    <p class="text-xl font-black text-slate-800 dark:text-white">{{ $lahan->komoditas_utama ?? 'N/A' }}</p>
                    <span
                        class="text-xs bg-green-100 text-green-700 px-2 py-0.5 rounded-lg font-bold">{{ $lahan->fase_saat_ini ?? 'N/A' }}</span>
                </div>
            </div>

            <div
                class="bg-white dark:bg-slate-900 rounded-[2rem] border border-slate-100 dark:border-slate-800 p-6 flex items-center gap-4">
                <div class="bg-blue-100 dark:bg-blue-500/20 p-4 rounded-2xl text-3xl">📊</div>
                <div>
                    <h3 class="text-xs font-bold text-slate-400 uppercase mb-1">Kesesuaian</h3>
                    <p class="text-xl font-black text-slate-800 dark:text-white">{{ $lahan->kesesuaian_score ?? 0 }}%</p>
                    <p class="text-xs font-bold text-blue-600">Teroptimasi IoT</p>
                </div>
            </div>
        </div>

        {{-- Rekomendasi (Z-Index fix: z-0) --}}
        <div
            class="bg-gradient-to-br from-green-600 to-emerald-700 rounded-[2.5rem] p-8 text-white relative overflow-hidden z-0 shadow-xl shadow-green-900/20">
            <div class="relative z-10 flex gap-6 items-center">
                <div class="bg-white/20 p-4 rounded-3xl backdrop-blur-md">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold">Rekomendasi Tindakan</h3>
                    <p class="opacity-90 text-sm leading-relaxed">
                        Berdasarkan sensor di <b>{{ $lahan->nama_lahan }}</b> dan suhu <b>{{ $temp }}°C</b>,
                        sistem menyarankan optimasi penyiraman pada fase {{ $lahan->fase_saat_ini }}.
                    </p>
                </div>
            </div>
        </div>

        {{-- Weather Widgets --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @foreach ([['Suhu', $temp . '°C'], ['Kelembapan', $humidity . '%'], ['Kondisi', $condition], ['Luas', ($lahan->luas ?? 0) . ' m²']] as $item)
                <div
                    class="bg-white dark:bg-slate-900 p-6 rounded-3xl border border-slate-100 dark:border-slate-800 shadow-sm">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">{{ $item[0] }}</p>
                    <p class="text-xl font-black text-slate-800 dark:text-white">{{ $item[1] }}</p>
                </div>
            @endforeach
        </div>
    </div>
@endsection

{{-- MODAL SECTION - Diletakkan di stack agar di-render di luar container utama --}}
@push('modals')
    <div id="lahanModal" class="hidden fixed inset-0 w-screen h-screen" style="z-index: 99999;">
        {{-- Backdrop super solid --}}
        <div class="absolute inset-0 bg-slate-950/80 backdrop-blur-md"
            onclick="document.getElementById('lahanModal').classList.add('hidden')"></div>

        <div class="relative flex items-center justify-center min-h-screen p-4 pointer-events-none">
            <div
                class="bg-white dark:bg-slate-900 rounded-[2.5rem] w-full max-w-md shadow-2xl border border-slate-200 dark:border-slate-800 pointer-events-auto overflow-hidden">
                <div class="p-8 text-center">
                    <div
                        class="w-20 h-20 bg-green-100 dark:bg-green-500/20 rounded-3xl flex items-center justify-center mx-auto mb-6">
                        <span class="text-4xl">🪴</span>
                    </div>
                    <h3 class="text-2xl font-black text-slate-800 dark:text-white mb-2">Pilih Lahan</h3>
                    <p class="text-slate-500 dark:text-slate-400 text-sm mb-8">Pilih data lahan yang ingin Anda pantau
                        secara intensif.</p>

                    <div class="space-y-3">
                        @forelse($userLahan as $l)
                            <a href="?lahan_id={{ $l->id }}"
                                class="flex items-center justify-between p-4 rounded-2xl border-2 {{ $lahan->id == $l->id ? 'border-green-500 bg-green-50 dark:bg-green-500/10' : 'border-slate-100 dark:border-slate-800 hover:border-slate-300 dark:hover:border-slate-600' }} transition-all group">
                                <div class="flex items-center gap-3">
                                    <span class="text-xl">📍</span>
                                    <div class="text-left">
                                        <p
                                            class="font-bold text-slate-800 dark:text-white group-hover:text-green-600 transition-colors">
                                            {{ $l->nama_lahan }}</p>
                                        <p class="text-xs text-slate-400">{{ $l->komoditas_utama }}</p>
                                    </div>
                                </div>
                                @if ($lahan->id == $l->id)
                                    <div class="bg-green-500 rounded-full p-1 text-white">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>
                                @endif
                            </a>
                        @empty
                            <p class="text-slate-400 italic">Belum ada data lahan.</p>
                        @endforelse
                    </div>

                    <button onclick="document.getElementById('lahanModal').classList.add('hidden')"
                        class="mt-8 w-full py-4 text-slate-400 font-bold hover:text-slate-600 dark:hover:text-white transition-colors">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
@endpush
