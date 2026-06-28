<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmarTani - Solusi Pertanian Cerdas</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #e2e8f0;
            border-radius: 10px;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.6);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.7);
        }
    </style>
</head>

<body class="bg-white antialiased overflow-hidden h-screen flex flex-col md:flex-row">

    {{-- SISI KIRI: Scrollable Information Hub --}}
    <div
        class="hidden md:flex flex-1 h-screen overflow-y-auto custom-scrollbar bg-[radial-gradient(ellipse_at_top_left,_var(--tw-gradient-stops))] from-green-50 via-white to-emerald-50 relative">

        <div class="max-w-2xl mx-auto py-16 px-12 lg:px-20 relative z-10">

            <!-- Logo Section -->
            <div class="flex items-center gap-4 mb-12 group">
                <div
                    class="bg-green-600 p-3 rounded-2xl shadow-xl shadow-green-100 transform group-hover:rotate-6 transition-transform">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M12 3v19M5 8h14M5 16h14"></path>
                    </svg>
                </div>
                <span class="font-extrabold text-2xl tracking-tighter text-slate-800">AGA <span
                        class="text-green-600">SmarTani</span></span>
            </div>

            <!-- KOMPONEN AUTO SLIDER GAMBAR -->
            <div x-data="{
                activeSlide: 1,
                slides: [
                    { id: 1, url: 'https://images.unsplash.com/photo-1560493676-04071c5f467b?q=80&w=800', title: 'AI-Powered Agriculture' },
                    { id: 2, url: 'https://images.unsplash.com/photo-1677442136019-21780ecad995?q=80&w=800', title: 'Large Language Model Integration' },
                    { id: 3, url: 'https://images.unsplash.com/photo-1485827404703-89b55fcc595e?q=80&w=800', title: 'Smart Farming Bot' },
                    { id: 4, url: 'https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?q=80&w=800', title: 'Precision IoT Sensors' },
                    { id: 5, url: 'https://images.unsplash.com/photo-1558444479-c8a51792bd2a?q=80&w=800', title: 'Data-Driven Harvest' }
                ],
                loop() {
                    setInterval(() => {
                        this.activeSlide = this.activeSlide === this.slides.length ? 1 : this.activeSlide + 1
                    }, 4000)
                }
            }" x-init="loop"
                class="relative w-full h-72 mb-12 rounded-[2.5rem] overflow-hidden shadow-2xl shadow-green-200/50">

                <template x-for="slide in slides" :key="slide.id">
                    <div x-show="activeSlide === slide.id" x-transition:enter="transition ease-out duration-1000"
                        x-transition:enter-start="opacity-0 scale-105" x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-1000" x-transition:leave-start="opacity-100"
                        x-transition:leave-end="opacity-0" class="absolute inset-0">
                        <img :src="slide.url" :alt="slide.title" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent">
                        </div>
                        <div class="absolute bottom-6 left-8">
                            <p class="text-white font-bold text-lg tracking-wide" x-text="slide.title"></p>
                        </div>
                    </div>
                </template>

                <div class="absolute bottom-6 right-8 flex gap-2">
                    <template x-for="slide in slides" :key="slide.id">
                        <div @click="activeSlide = slide.id"
                            :class="activeSlide === slide.id ? 'w-8 bg-green-400' : 'w-2 bg-white/50'"
                            class="h-2 rounded-full cursor-pointer transition-all duration-500"></div>
                    </template>
                </div>
            </div>

            <!-- Hero Text -->
            <div class="space-y-4 mb-16">
                <span
                    class="px-4 py-1.5 bg-green-100 text-green-700 text-xs font-bold rounded-full uppercase tracking-widest">Next-Gen
                    Farming</span>
                <h1 class="text-5xl font-black text-slate-900 leading-[1.1] tracking-tight">
                    Ekosistem Pertanian <br /> <span
                        class="text-transparent bg-clip-text bg-gradient-to-r from-green-600 to-emerald-500">Berbasis
                        Generative AI.</span>
                </h1>
                <p class="text-slate-500 text-lg leading-relaxed max-w-lg">
                    Menggabungkan kekuatan <strong>Large Language Model (LLM)</strong> dan IoT untuk memberikan
                    rekomendasi penanaman yang dipersonalisasi.
                </p>
            </div>

            <!-- Feature Grid -->
            <div class="grid grid-cols-1 gap-6 mb-16">
                <!-- LLM Feature -->
                <div
                    class="glass-card p-8 rounded-[2rem] hover:shadow-xl transition-all duration-300 border-l-4 border-l-green-500">
                    <div class="flex items-start gap-6">
                        <div
                            class="w-14 h-14 bg-green-600 text-white rounded-2xl flex items-center justify-center shrink-0 shadow-lg shadow-green-100">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-900 text-xl mb-2">Decision Support System (LLM)</h3>
                            <p class="text-slate-500 leading-relaxed">Sistem cerdas yang menganalisis data sensor tanah
                                dan cuaca untuk memberikan instruksi logis terkait tindakan preventif hama dan pemupukan
                                presisi.</p>
                        </div>
                    </div>
                </div>

                <!-- Chatbot Feature -->
                <div
                    class="glass-card p-8 rounded-[2rem] hover:shadow-xl transition-all duration-300 border-l-4 border-l-blue-500">
                    <div class="flex items-start gap-6">
                        <div
                            class="w-14 h-14 bg-blue-600 text-white rounded-2xl flex items-center justify-center shrink-0 shadow-lg shadow-blue-100">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-900 text-xl mb-2">Asisten Virtual Tani</h3>
                            <p class="text-slate-500 leading-relaxed">Konsultasi masalah lahan kapan saja melalui
                                Chatbot interaktif. Cukup tanya mengenai kondisi tanaman, dan AI akan memberikan solusi
                                instan berbasis basis pengetahuan botani.</p>
                        </div>
                    </div>
                </div>

                <!-- Vision AI Feature -->
                <div
                    class="glass-card p-8 rounded-[2rem] hover:shadow-xl transition-all duration-300 border-l-4 border-l-emerald-500">
                    <div class="flex items-start gap-6">
                        <div
                            class="w-14 h-14 bg-emerald-600 text-white rounded-2xl flex items-center justify-center shrink-0 shadow-lg shadow-emerald-100">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z">
                                </path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-900 text-xl mb-2">Deteksi Hama via Vision AI</h3>
                            <p class="text-slate-500 leading-relaxed">Integrasi kamera untuk mendeteksi serangan OPT
                                (Organisme Pengganggu Tumbuhan) secara dini dengan akurasi tinggi menggunakan model
                                deteksi objek terkini.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer Kiri -->
            <div class="border-t border-slate-100 pt-8 flex items-center justify-between">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Powered by AGA Group</p>
                <div class="flex gap-4">
                    <div class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">System Online</p>
                </div>
            </div>

            <div class="h-20"></div>
        </div>
    </div>

    {{-- SISI KANAN: Login Form (Statis) --}}
    <div
        class="w-full md:w-[480px] lg:w-[520px] bg-white h-screen flex-shrink-0 flex flex-col justify-center px-8 sm:px-16 lg:px-20 shadow-[-20px_0_60px_-15px_rgba(0,0,0,0.05)] relative z-20">
        <div class="w-full max-w-sm mx-auto">
            <div class="mb-10">
                <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">Selamat Datang</h2>
                <p class="text-slate-500 mt-2 font-medium">Masuk untuk mengelola ekosistem tani Anda</p>
            </div>

            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

                @if ($errors->any())
                    <div
                        class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-2xl text-sm font-medium flex items-center gap-2">
                        <svg class="w-5 h-5 text-red-500 shrink-0" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                            </path>
                        </svg>
                        <span>
                            {{ $errors->first() }}
                        </span>
                    </div>
                @endif
                <div class="space-y-2">
                    <label
                        class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}"
                        class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-5 py-4 text-sm focus:ring-4 focus:ring-green-500/10 focus:border-green-500 transition-all outline-none"
                        placeholder="user@smartani.com" required>
                </div>

                <div class="space-y-2">
                    <label
                        class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Password</label>
                    <input type="password" name="password"
                        class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-5 py-4 text-sm focus:ring-4 focus:ring-green-500/10 focus:border-green-500 transition-all outline-none"
                        placeholder="••••••••" required>
                </div>

                <button type="submit"
                    class="w-full bg-slate-900 hover:bg-green-700 text-white font-bold py-4 rounded-2xl transition-all duration-300 shadow-xl flex items-center justify-center gap-3 active:scale-[0.98]">
                    Akses Dashboard
                </button>
            </form>
        </div>
    </div>

</body>

</html>
