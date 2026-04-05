@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header Welcome -->
    <div class="flex justify-between items-end">
        <div>
            <h1 class="text-2xl font-black text-slate-800 dark:text-white">Tinjauan Kebun</h1>
            <p class="text-slate-500 dark:text-slate-400 text-sm mt-1">Pemantauan lahan Blok A-1 terintegrasi IoT & BMKG</p>
        </div>
        <span class="text-xs font-bold bg-green-100 text-green-700 dark:bg-green-500/20 dark:text-green-400 px-3 py-1.5 rounded-full flex items-center gap-2 shadow-sm border border-green-200 dark:border-green-800">
            <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span> Sistem Online
        </span>
    </div>

    <!-- AI Recommendation Card -->
    {{-- NANTI: Teks ini akan diganti dengan variabel dari Backend --}}
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
                        Berdasarkan <strong>Sensor IoT</strong>, kelembapan tanah Anda saat ini tergolong kering (42%). Mempertimbangkan prediksi <strong>BMKG</strong> bahwa hari ini suhu mencapai 31°C dan tidak ada potensi hujan, LLM menyarankan Anda untuk <strong>segera melakukan penyiraman lahan secara manual</strong> pada sore hari untuk menjaga fase pertumbuhan vegetatif padi.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Sensor & API Grid (Sekarang 4 Kolom) -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        
        <!-- Soil Moisture (IoT) -->
        <div class="bg-white dark:bg-slate-900 rounded-[2rem] border border-slate-100 dark:border-slate-800 p-6 shadow-sm hover:shadow-md transition-all relative overflow-hidden flex flex-col">
            <div class="absolute top-0 right-0 bg-blue-500 text-white text-[10px] font-bold px-3 py-1 rounded-bl-xl z-10">DATA IOT</div>
            <div class="flex justify-between items-start mb-4 mt-2">
                <div class="p-3 bg-blue-50 dark:bg-blue-500/10 rounded-2xl">
                    <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14C19 17.866 15.866 21 12 21C8.13401 21 5 17.866 5 14C5 10.134 12 3 12 3C12 3 19 10.134 19 14Z"></path></svg>
                </div>
                <span class="text-xs font-bold text-red-500 bg-red-50 dark:bg-red-500/10 px-2.5 py-1 rounded-lg border border-red-100 dark:border-red-900/50">Kering</span>
            </div>
            <div class="mt-auto">
                <h3 class="text-slate-400 dark:text-slate-500 text-xs font-bold tracking-widest uppercase mb-1">Kelembapan Tanah</h3>
                <p class="text-3xl font-black text-slate-800 dark:text-white">42<span class="text-lg text-slate-400 font-semibold">%</span></p>
            </div>
        </div>

        <!-- Soil pH (IoT) -->
        <div class="bg-white dark:bg-slate-900 rounded-[2rem] border border-slate-100 dark:border-slate-800 p-6 shadow-sm hover:shadow-md transition-all relative overflow-hidden flex flex-col">
            <div class="absolute top-0 right-0 bg-blue-500 text-white text-[10px] font-bold px-3 py-1 rounded-bl-xl z-10">DATA IOT</div>
            <div class="flex justify-between items-start mb-4 mt-2">
                <div class="p-3 bg-purple-50 dark:bg-purple-500/10 rounded-2xl">
                    <svg class="w-6 h-6 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                </div>
                <span class="text-xs font-bold text-slate-400 bg-slate-100 dark:bg-slate-800 px-2.5 py-1 rounded-lg">Normal</span>
            </div>
            <div class="mt-auto">
                <h3 class="text-slate-400 dark:text-slate-500 text-xs font-bold tracking-widest uppercase mb-1">pH Tanah</h3>
                <p class="text-3xl font-black text-slate-800 dark:text-white">6.2</p>
            </div>
        </div>

        <!-- Temperature (BMKG) -->
        <div class="bg-white dark:bg-slate-900 rounded-[2rem] border border-slate-100 dark:border-slate-800 p-6 shadow-sm hover:shadow-md transition-all relative overflow-hidden flex flex-col">
            <div class="absolute top-0 right-0 bg-orange-500 text-white text-[10px] font-bold px-3 py-1 rounded-bl-xl z-10">API BMKG</div>
            <div class="flex justify-between items-start mb-4 mt-2">
                <div class="p-3 bg-orange-50 dark:bg-orange-500/10 rounded-2xl">
                    <svg class="w-6 h-6 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19c-5 1.5-5-2.5-7-3m14 6v-3.87a3.37 3.37 0 0 0-.94-2.61c3.14-.35 6.44-1.54 6.44-7A5.44 5.44 0 0 0 20 4.77 5.07 5.07 0 0 0 19.91 1S18.73.65 16 2.48a13.38 13.38 0 0 0-7 0C6.27.65 5.09 1 5.09 1A5.07 5.07 0 0 0 5 4.77a5.44 5.44 0 0 0-1.5 3.78c0 5.42 3.3 6.61 6.44 7A3.37 3.37 0 0 0 9 18.13V22"></path></svg>
                </div>
                <span class="text-xs font-bold text-slate-400 bg-slate-100 dark:bg-slate-800 px-2.5 py-1 rounded-lg">Bekasi</span>
            </div>
            <div class="mt-auto">
                <h3 class="text-slate-400 dark:text-slate-500 text-xs font-bold tracking-widest uppercase mb-1">Suhu Udara Area</h3>
                <p class="text-3xl font-black text-slate-800 dark:text-white">31.0<span class="text-lg text-slate-400 font-semibold">°C</span></p>
            </div>
        </div>

        <!-- Weather Forecast (BMKG) -->
        <div class="bg-white dark:bg-slate-900 rounded-[2rem] border border-slate-100 dark:border-slate-800 p-6 shadow-sm hover:shadow-md transition-all relative overflow-hidden flex flex-col">
            <div class="absolute top-0 right-0 bg-teal-500 text-white text-[10px] font-bold px-3 py-1 rounded-bl-xl z-10">API BMKG</div>
            <div class="flex justify-between items-start mb-4 mt-2">
                <div class="p-3 bg-teal-50 dark:bg-teal-500/10 rounded-2xl">
                    <svg class="w-6 h-6 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"></path></svg>
                </div>
            </div>
            <div class="mt-auto">
                <h3 class="text-slate-400 dark:text-slate-500 text-xs font-bold tracking-widest uppercase mb-1">Prediksi Cuaca</h3>
                <p class="text-3xl font-black text-slate-800 dark:text-white">Cerah<span class="text-lg text-slate-400 font-semibold text-[0.6em]"> (0% Hujan)</span></p>
            </div>
        </div>

    </div>
</div>
@endsection