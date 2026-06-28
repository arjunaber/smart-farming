@extends('layouts.app')

@section('content')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('disease', () => ({
                hasImage: false,
                imagePreview: null,
                isAnalyzing: false,
                showResult: false,
                selectedFile: null,
                isDragging: false,
                result: {
                    disease_name: '',
                    confidence: null,
                    explanation: '',
                    cause: '',
                    treatment: '',
                    prevention: '',
                    raw_text: '',
                },

                triggerUpload() {
                    document.getElementById('fileUploader').click();
                },

                handleDrop(event) {
                    this.isDragging = false;
                    const files = event.dataTransfer.files;
                    if (files.length > 0) {
                        this.processFile(files[0]);
                    }
                },

                processFile(file) {
                    const allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
                    if (!allowedTypes.includes(file.type)) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Format tidak didukung',
                            text: 'Hanya JPG, PNG, dan WEBP yang diperbolehkan.',
                            confirmButtonColor: '#16a34a',
                        });
                        return;
                    }
                    if (file.size > 5 * 1024 * 1024) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Ukuran terlalu besar',
                            text: 'Maksimal 5 MB.',
                            confirmButtonColor: '#16a34a',
                        });
                        return;
                    }

                    // Langsung set file tanpa validasi ketajaman/blur
                    this.setSelectedFile(file);
                },

                setSelectedFile(file) {
                    this.hasImage = true;
                    this.showResult = false;
                    this.imagePreview = URL.createObjectURL(file);
                    this.selectedFile = file;
                },

                handleFileUpload(event) {
                    const file = event.target.files[0];
                    if (!file) return;
                    this.processFile(file);
                },

                async analyzeImage() {
                    if (!this.selectedFile) return;

                    this.isAnalyzing = true;
                    this.showResult = false;
                    this.result = {
                        disease_name: '',
                        confidence: null,
                        explanation: '',
                        cause: '',
                        treatment: '',
                        prevention: '',
                        raw_text: ''
                    };

                    try {
                        const formData = new FormData();
                        formData.append('image', this.selectedFile);
                        formData.append('_token', document.querySelector('meta[name="csrf-token"]')
                            .content);

                        const response = await fetch('{{ route('disease.analyze') }}', {
                            method: 'POST',
                            body: formData,
                        });

                        const json = await response.json();

                        if (!response.ok || json.status === 'error') {
                            throw new Error(json.message || 'Server error');
                        }

                        this.result.disease_name = json.disease_name ?? 'Tidak Diketahui';
                        this.result.confidence = json.confidence ?? null;
                        this.result.explanation = json.explanation ?? '';
                        this.result.cause = json.cause ?? '';
                        this.result.treatment = json.treatment ?? '';
                        this.result.prevention = json.prevention ?? '';
                        this.result.raw_text = json.raw_text ?? '';

                        this.showResult = true;

                    } catch (err) {
                        console.error(err);
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal Memproses',
                            text: err.message ||
                                'Terjadi kesalahan saat menghubungi server AI.',
                            confirmButtonColor: '#ef4444',
                        });
                    } finally {
                        this.isAnalyzing = false;
                    }
                },

                resetArea() {
                    this.hasImage = false;
                    this.imagePreview = null;
                    this.showResult = false;
                    this.selectedFile = null;
                    this.result = {
                        disease_name: '',
                        confidence: null,
                        explanation: '',
                        cause: '',
                        treatment: '',
                        prevention: '',
                        raw_text: ''
                    };
                    document.getElementById('fileUploader').value = '';
                },
            }));
        });
    </script>

    <div x-data="disease" class="max-w-5xl mx-auto space-y-6">
        <div class="mb-8">
            <h1 class="text-2xl font-black text-slate-800 dark:text-white">Klasifikasi Penyakit</h1>
            <p class="text-slate-500 dark:text-slate-400 text-sm mt-1">Unggah foto daun tanaman yang terkena penyakit untuk
                diagnosis Vision AI.</p>
        </div>

        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-2xl p-6 mb-6">
            <h3 class="text-sm font-bold text-blue-800 dark:text-blue-300 mb-3 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Panduan Pengambilan Gambar
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="flex items-start gap-3 bg-white dark:bg-slate-800 p-3 rounded-xl shadow-sm">
                    <div
                        class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0 text-green-600 text-xl">
                        ✅</div>
                    <div>
                        <p class="text-sm font-bold text-slate-800 dark:text-white">Benar & Fokus</p>
                        <p class="text-xs text-slate-500">Pastikan daun terlihat jelas, pencahayaan cukup, dan kamera fokus
                            pada area yang terkena hama/penyakit.</p>
                    </div>
                </div>
                <div class="flex items-start gap-3 bg-white dark:bg-slate-800 p-3 rounded-xl shadow-sm">
                    <div
                        class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center flex-shrink-0 text-red-600 text-xl">
                        ❌</div>
                    <div>
                        <p class="text-sm font-bold text-slate-800 dark:text-white">Buram / Terlalu Jauh</p>
                        <p class="text-xs text-slate-500">Hindari foto yang blur (goyang), terlalu gelap, atau daun terlihat
                            terlalu kecil dari kejauhan.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

            {{-- ===== KOLOM KIRI: Upload ===== --}}
            <div
                class="bg-white dark:bg-slate-900 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 p-8 shadow-sm flex flex-col">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-sm font-bold text-slate-800 dark:text-white uppercase tracking-wider">Input Gambar</h3>
                    <button x-show="hasImage" @click="resetArea()" class="text-xs text-red-500 hover:text-red-700 font-bold"
                        style="display:none;">Reset</button>
                </div>

                {{-- Drop Zone --}}
                <div x-show="!hasImage"
                    class="flex-1 border-2 border-dashed border-gray-300 dark:border-slate-700 rounded-3xl p-10 flex flex-col items-center justify-center text-center transition-colors cursor-pointer group bg-slate-50 dark:bg-slate-800/50"
                    :class="{
                        'border-green-500 bg-green-50 dark:bg-green-900/20': isDragging,
                        'hover:border-green-500': !isDragging
                    }"
                    @click="triggerUpload()" @dragover.prevent="isDragging = true" @dragleave.prevent="isDragging = false"
                    @drop.prevent="handleDrop($event)">
                    <div class="bg-white p-4 rounded-2xl shadow-sm mb-4 group-hover:scale-110 transition-transform">
                        <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z">
                            </path>
                        </svg>
                    </div>
                    <p class="font-bold text-slate-700 dark:text-slate-300">Klik atau seret foto ke sini</p>
                    <p class="text-xs text-slate-400 mt-1">JPG, PNG, WEBP – maks 5 MB</p>
                </div>

                {{-- Preview + Tombol --}}
                <div x-show="hasImage" style="display:none;" class="flex-1 flex flex-col items-center justify-center gap-4">
                    <div class="w-full rounded-2xl overflow-hidden border border-slate-200 dark:border-slate-700 flex items-center justify-center bg-slate-50"
                        style="max-height:220px;">
                        <img :src="imagePreview" class="max-w-full max-h-52 object-contain" alt="Preview">
                    </div>
                    <button @click="analyzeImage()" :disabled="isAnalyzing"
                        class="w-full bg-green-600 hover:bg-green-700 text-white font-bold px-6 py-3.5 rounded-xl transition-all shadow-md flex items-center justify-center gap-2 disabled:opacity-60 disabled:cursor-not-allowed">
                        <span x-show="!isAnalyzing" class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.347.356A9.012 9.012 0 0112 21a9.012 9.012 0 01-3.79-.748l-.347-.356z">
                                </path>
                            </svg>
                            Jalankan Analisis AI
                        </span>
                        <span x-show="isAnalyzing" style="display:none;" class="flex items-center gap-2">
                            <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                            </svg>
                            Menganalisis...
                        </span>
                    </button>
                </div>

                <input type="file" id="fileUploader" @change="handleFileUpload($event)" class="hidden" accept="image/*">
            </div>

            {{-- ===== KOLOM KANAN: Hasil ===== --}}
            <div
                class="bg-white dark:bg-slate-900 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 p-8 flex flex-col">

                {{-- Status Kosong --}}
                <div x-show="!showResult && !isAnalyzing"
                    class="flex-1 flex flex-col items-center justify-center text-center">
                    <div class="w-16 h-16 bg-slate-100 dark:bg-slate-800 rounded-2xl flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                    </div>
                    <p class="text-slate-400 font-medium text-sm">Hasil diagnosis AI akan muncul di sini.</p>
                </div>

                {{-- Loading Skeleton --}}
                <div x-show="isAnalyzing" style="display:none;"
                    class="flex-1 flex flex-col justify-center space-y-4 animate-pulse">
                    <div class="h-4 bg-slate-200 dark:bg-slate-700 rounded w-3/4"></div>
                    <div class="h-4 bg-slate-200 dark:bg-slate-700 rounded w-full"></div>
                    <div class="h-4 bg-slate-200 dark:bg-slate-700 rounded w-5/6"></div>
                    <div class="h-4 bg-slate-200 dark:bg-slate-700 rounded w-2/3"></div>
                    <p class="text-center text-xs text-slate-400 pt-2">AI sedang menganalisis gambar...</p>
                </div>

                {{-- Hasil Diagnosis --}}
                <div x-show="showResult" style="display:none;" class="flex-1 flex flex-col">

                    {{-- Header --}}
                    <div class="flex items-start justify-between mb-5">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-widest text-green-600 mb-1">Hasil Diagnosis</p>
                            <h4 class="text-xl font-black text-slate-800 dark:text-white" x-text="result.disease_name">
                            </h4>
                        </div>
                        <template x-if="result.confidence">
                            <div
                                class="flex-shrink-0 bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800 rounded-xl px-3 py-2 text-center ml-3">
                                <p class="text-lg font-black text-green-600" x-text="result.confidence"></p>
                                <p class="text-xs text-green-500">Akurasi</p>
                            </div>
                        </template>
                    </div>

                    {{-- Chat Bubbles --}}
                    <div class="flex-1 space-y-3 overflow-y-auto max-h-80 pr-1">

                        <template x-if="result.explanation">
                            <div class="flex gap-3">
                                <div
                                    class="w-8 h-8 rounded-full bg-green-500 flex items-center justify-center flex-shrink-0 text-white text-xs font-bold">
                                    AI</div>
                                <div
                                    class="bg-slate-100 dark:bg-slate-800 rounded-2xl rounded-tl-none px-4 py-3 text-sm text-slate-700 dark:text-slate-300">
                                    <p class="font-bold text-slate-800 dark:text-white text-xs mb-1">📋 Gejala yang
                                        Terlihat</p>
                                    <p x-text="result.explanation"></p>
                                </div>
                            </div>
                        </template>

                        <template x-if="result.cause">
                            <div class="flex gap-3">
                                <div
                                    class="w-8 h-8 rounded-full bg-green-500 flex items-center justify-center flex-shrink-0 text-white text-xs font-bold">
                                    AI</div>
                                <div
                                    class="bg-slate-100 dark:bg-slate-800 rounded-2xl rounded-tl-none px-4 py-3 text-sm text-slate-700 dark:text-slate-300">
                                    <p class="font-bold text-slate-800 dark:text-white text-xs mb-1">🦠 Penyebab</p>
                                    <p x-text="result.cause"></p>
                                </div>
                            </div>
                        </template>

                        <template x-if="result.treatment">
                            <div class="flex gap-3">
                                <div
                                    class="w-8 h-8 rounded-full bg-green-500 flex items-center justify-center flex-shrink-0 text-white text-xs font-bold">
                                    AI</div>
                                <div
                                    class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-2xl rounded-tl-none px-4 py-3 text-sm text-slate-700 dark:text-slate-300">
                                    <p class="font-bold text-green-700 dark:text-green-400 text-xs mb-1">💊 Penanganan</p>
                                    <p x-text="result.treatment"></p>
                                </div>
                            </div>
                        </template>

                        <template x-if="result.prevention">
                            <div class="flex gap-3">
                                <div
                                    class="w-8 h-8 rounded-full bg-green-500 flex items-center justify-center flex-shrink-0 text-white text-xs font-bold">
                                    AI</div>
                                <div
                                    class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-2xl rounded-tl-none px-4 py-3 text-sm text-slate-700 dark:text-slate-300">
                                    <p class="font-bold text-blue-700 dark:text-blue-400 text-xs mb-1">🛡️ Pencegahan</p>
                                    <p x-text="result.prevention"></p>
                                </div>
                            </div>
                        </template>

                        {{-- Fallback jika LLM tidak return JSON terstruktur --}}
                        <template x-if="result.raw_text && !result.explanation">
                            <div class="flex gap-3">
                                <div
                                    class="w-8 h-8 rounded-full bg-green-500 flex items-center justify-center flex-shrink-0 text-white text-xs font-bold">
                                    AI</div>
                                <div class="bg-slate-100 dark:bg-slate-800 rounded-2xl rounded-tl-none px-4 py-3 text-sm text-slate-700 dark:text-slate-300"
                                    x-html="result.raw_text"></div>
                            </div>
                        </template>

                    </div>

                    <button @click="resetArea()"
                        class="mt-4 w-full border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400 font-bold px-6 py-3 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800 transition-all text-sm">
                        Analisis Gambar Lain
                    </button>
                </div>

            </div>
        </div>
    </div>
@endsection
