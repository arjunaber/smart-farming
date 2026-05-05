@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        {{-- Header Section & Multi-Lahan (Revisi Pak Sinung) --}}
        <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-4">
            <div>
                <h1 class="text-2xl font-black text-slate-800 dark:text-white">Tinjauan Kebun</h1>
                <div class="flex items-center gap-3 mt-2">
                    <p class="text-slate-500 dark:text-slate-400 text-sm">Pemantauan lahan terintegrasi IoT & BMKG</p>
                </div>
            </div>
            
            <div class="flex flex-wrap gap-2 w-full md:w-auto">
                {{-- Dropdown Manajemen Multi-Lahan --}}
                <select id="lahan-select" class="text-sm font-bold bg-white dark:bg-slate-800 text-slate-700 dark:text-white px-4 py-2 rounded-xl border border-slate-200 dark:border-slate-700 focus:ring-2 focus:ring-green-500 outline-none shadow-sm cursor-pointer">
@forelse($lahanList ?? collect() as $l)
                        <option value="{{ $l->id }}" {{ ($lahan->id ?? '') == $l->id ? 'selected' : '' }}>{{ $l->emoji ?? '🌱' }} {{ $l->nama_lahan }}</option>
                    @empty
                        <option value="">Tidak ada lahan</option>
                    @endforelse
                </select>
                @if(($lahanList ?? collect())->isEmpty())
                    <a href="{{ route('lahan.create') }}" class="ml-2 bg-green-600 text-white px-4 py-2 rounded-xl text-sm font-bold hover:bg-green-700">+ Buat Lahan Pertama</a>
                @endif

                <button onclick="document.getElementById('locationModal').classList.remove('hidden')"
                    class="text-sm font-bold bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-400 px-4 py-2 rounded-xl border border-slate-200 dark:border-slate-700 hover:bg-slate-50 transition-all shadow-sm">
                    📍 <span id="header-area-btn" class="text-orange-500">{{ $area ?? 'Soreang' }}</span>
                </button>
            </div>
        </div>

        {{-- Katalog Komoditas & Kesesuaian Lahan --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Katalog Komoditas Lahan Saat Ini --}}
            <div class="bg-white dark:bg-slate-900 rounded-[2rem] border border-slate-100 dark:border-slate-800 p-6 shadow-sm flex items-center gap-4">
                <div class="bg-green-100 dark:bg-green-500/20 p-4 rounded-2xl">
                    <span class="text-3xl">🌾</span>
                </div>
                <div>
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Komoditas Aktif</h3>
                    <p class="text-xl font-black text-slate-800 dark:text-white">Padi Varietas IR64</p>
                    <p class="text-sm text-green-600 font-medium mt-1">Fase Vegetatif (Hari ke-45)</p>
                </div>
            </div>

            {{-- Analisis Kesesuaian Lahan --}}
            <div class="bg-white dark:bg-slate-900 rounded-[2rem] border border-slate-100 dark:border-slate-800 p-6 shadow-sm flex items-center gap-4">
                <div class="bg-blue-100 dark:bg-blue-500/20 p-4 rounded-2xl">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Kesesuaian Lahan</h3>
                    <p class="text-xl font-black text-slate-800 dark:text-white">{{ $lahan->kesesuaian_score ?? 92 }}%</p>
                    <p class="text-sm text-slate-500 font-medium mt-1">{{ $lahan->kesesuaian_score > 80 ? 'Sangat Layak - Optimal' : 'Perlu perbaikan' }} untuk {{ strtolower($lahan->komoditas_utama ?? 'padi') }}.</p>
                </div>
            </div>
        </div>


        {{-- LLM Recommendation Card (Tetap Sama) --}}
        <div class="bg-gradient-to-r from-amber-500 to-orange-500 rounded-[2.5rem] p-8 md:p-10 shadow-lg shadow-orange-500/20 text-white relative overflow-hidden">
            <svg class="absolute right-0 top-0 w-64 h-64 text-white/10 -mt-10 -mr-10" fill="currentColor" viewBox="0 0 24 24"><path d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
            <div class="relative z-10 flex flex-col md:flex-row gap-6 items-center justify-between">
                <div class="flex items-center gap-6">
                    <div class="bg-white/20 p-5 rounded-3xl backdrop-blur-sm shadow-inner flex-shrink-0">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold mb-2">Rekomendasi Tindakan Irigasi Manual</h3>
                        <p class="text-orange-50 leading-relaxed text-sm max-w-3xl">
                            Berdasarkan <strong>Sensor IoT</strong>, kelembapan tanah Anda saat ini tergolong kering (42%).
                            Mempertimbangkan prediksi <strong>BMKG</strong> di area <strong id="card-area">{{ $area ?? 'Lokasi' }}</strong> 
                            bahwa hari ini suhu mencapai <span id="card-temp">{{ $temp ?? '--' }}</span>°C dengan kondisi <span id="card-cuaca">{{ $cuaca ?? '--' }}</span>, 
                            LLM menyarankan Anda untuk <strong>segera melakukan penyiraman</strong>.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Metrics Grid (Diambil dari kode sebelumnya, dipersingkat di respon ini agar tidak terlalu panjang, Anda bisa menempelkan div metrics grid dari source asli di sini) --}}
        
    </div>

    {{-- Script Location Modal (Tetap Sama seperti Source 3) --}}
@endsection
