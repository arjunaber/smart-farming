<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmarTani - Landing & Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 antialiased overflow-hidden h-screen flex flex-col md:flex-row">

    {{-- SISI KIRI: Scrollable (Edukasi, Katalog, Keunggulan) --}}
    <div class="flex-1 h-[50vh] md:h-screen overflow-y-auto bg-gradient-to-br from-green-50 to-green-100 p-6 md:p-12 hidden sm:block">
        <div class="max-w-2xl mx-auto">
            <div class="flex items-center gap-3 mb-10">
                <div class="bg-green-600 p-2.5 rounded-xl shadow-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 3v19M5 8h14M5 16h14"></path></svg>
                </div>
                <span class="font-black text-2xl tracking-tighter text-slate-800">AGA SmarTani</span>
            </div>

            <h1 class="text-3xl md:text-5xl font-black text-slate-800 leading-tight mb-6">
                Sistem Pertanian Pintar & Manajemen Lahan Biofarmaka
            </h1>
            <p class="text-slate-600 text-lg mb-8 leading-relaxed">
                Tingkatkan hasil panen Anda dengan integrasi IoT, prediksi cuaca BMKG, dan analisis cerdas Large Language Model (LLM).
            </p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-green-100">
                    <h3 class="font-bold text-green-700 mb-2">Manajemen Multi-Lahan</h3>
                    <p class="text-sm text-slate-500">Kelola berbagai petak sawah dan lahan biofarmaka dalam satu akun terpusat.</p>
                </div>
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-green-100">
                    <h3 class="font-bold text-green-700 mb-2">Analisis Kesesuaian</h3>
                    <p class="text-sm text-slate-500">Rekomendasi cerdas kecocokan lahan untuk berbagai jenis komoditas tanaman.</p>
                </div>
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-green-100">
                    <h3 class="font-bold text-green-700 mb-2">Deteksi Penyakit AI</h3>
                    <p class="text-sm text-slate-500">Unggah foto daun dan deteksi hama atau penyakit secara otomatis dengan Vision AI.</p>
                </div>
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-green-100">
                    <h3 class="font-bold text-green-700 mb-2">Logbook Pertanian</h3>
                    <p class="text-sm text-slate-500">Pencatatan siklus tanam, pemupukan, hingga panen yang terstruktur.</p>
                </div>
            </div>
        </div>
    </div>

    {{-- SISI KANAN: Static Form Login (Responsif iPhone SE) --}}
    <div class="w-full md:w-[450px] bg-white h-[50vh] md:h-screen flex-shrink-0 flex flex-col justify-center px-8 md:px-12 shadow-2xl relative z-10 overflow-y-auto">
        <div class="w-full max-w-sm mx-auto my-auto">
            <div class="text-center mb-8">
                <h2 class="text-2xl font-black text-slate-800">Selamat Datang</h2>
                <p class="text-sm text-slate-500 mt-1">Masuk untuk mengelola lahan Anda</p>
            </div>

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">Email</label>
                    <input type="email" name="email" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-green-500 outline-none" placeholder="petani@smartani.com" required>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">Password</label>
                    <input type="password" name="password" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-green-500 outline-none" placeholder="••••••••" required>
                </div>
                
                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" class="w-4 h-4 text-green-600 rounded border-slate-300 focus:ring-green-500">
                        <span class="text-sm text-slate-600">Ingat Saya</span>
                    </label>
                    <a href="#" class="text-sm font-bold text-green-600 hover:text-green-700">Lupa Password?</a>
                </div>

                <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3.5 rounded-xl transition-all shadow-lg shadow-green-200 mt-4">
                    Masuk ke Sistem
                </button>
            </form>
        </div>
    </div>

</body>
</html>