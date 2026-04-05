@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="mb-8">
        <h1 class="text-2xl font-black text-slate-800 dark:text-white">Pengaturan Sistem</h1>
        <p class="text-slate-500 dark:text-slate-400 text-sm mt-1">Konfigurasi lokasi kebun untuk API BMKG dan parameter LLM.</p>
    </div>

    <!-- Form Container -->
    <!-- NANTI: Tambahkan tag <form action="..." method="POST"> yang mengarah ke Controller -->
    <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden divide-y divide-slate-100 dark:divide-slate-800">
        
        <!-- Section 1 -->
        <div class="p-8 md:p-10">
            <h3 class="text-lg font-bold text-slate-800 dark:text-white mb-4">Profil & Lokasi Lahan (API BMKG)</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Nama Lahan</label>
                    <input type="text" value="Blok A-1 (Ciherang)" class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-green-500/20 outline-none transition-all dark:text-slate-200">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Kabupaten/Kota Lokasi Kebun</label>
                    <!-- Nanti BE bisa loop daftar kota dari BMKG di sini -->
                    <select class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-green-500/20 outline-none transition-all dark:text-slate-200 cursor-pointer">
                        <option selected>Kota Bekasi, Jawa Barat</option>
                        <option>Kabupaten Bekasi, Jawa Barat</option>
                        <option>Kabupaten Karawang, Jawa Barat</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Section 2 -->
        <div class="p-8 md:p-10">
            <h3 class="text-lg font-bold text-slate-800 dark:text-white mb-4">Parameter IoT & LLM</h3>
            <div class="space-y-6">
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Batas Kritis Kelembapan Tanah (%)</label>
                    <input type="number" value="40" class="w-full md:w-1/3 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-green-500/20 outline-none transition-all dark:text-slate-200">
                    <p class="text-xs text-slate-400 mt-2">LLM akan memberikan peringatan penyiraman manual jika data sensor jatuh di bawah batas ini (Kecuali BMKG memprediksi hujan).</p>
                </div>
                
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">API Key LLM (Gemini/OpenAI)</label>
                    <input type="password" value="************************" class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-green-500/20 outline-none transition-all dark:text-slate-200 font-mono">
                </div>
            </div>
        </div>

        <!-- Footer Actions -->
        <div class="p-8 md:p-10 bg-slate-50 dark:bg-slate-800/30 flex justify-end gap-4">
            <button class="px-6 py-3 rounded-xl text-sm font-bold text-slate-500 hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors">Batal</button>
            <button class="px-6 py-3 rounded-xl text-sm font-bold text-white bg-green-600 hover:bg-green-700 transition-colors shadow-sm">Simpan Pengaturan</button>
        </div>

    </div>
</div>
@endsection