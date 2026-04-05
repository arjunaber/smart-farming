@extends('layouts.app')

@section('content')
<div x-data="diseaseComponent" class="max-w-5xl mx-auto space-y-6">
    <div class="mb-8">
        <h1 class="text-2xl font-black text-slate-800 dark:text-white">Klasifikasi Penyakit</h1>
        <p class="text-slate-500 dark:text-slate-400 text-sm mt-1">Unggah foto daun tanaman yang terkena penyakit untuk diagnosis Vision AI.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Upload Area -->
        <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 p-8 shadow-sm flex flex-col">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-sm font-bold text-slate-800 dark:text-white uppercase tracking-wider">Input Gambar</h3>
                <button x-show="hasImage" @click="resetArea()" class="text-xs text-red-500 hover:text-red-700 font-bold" style="display: none;">Reset</button>
            </div>
            
            <!-- Jika belum ada gambar -->
            <div x-show="!hasImage" class="flex-1 border-2 border-dashed border-gray-300 dark:border-slate-700 rounded-3xl p-10 flex flex-col items-center justify-center text-center hover:border-green-500 dark:hover:border-green-500 transition-colors cursor-pointer group bg-slate-50 dark:bg-slate-800/50" @click="triggerUpload()">
                <div class="bg-white dark:bg-slate-800 p-4 rounded-2xl shadow-sm mb-4 group-hover:scale-110 transition-transform">
                    <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                </div>
                <p class="font-bold text-slate-700 dark:text-slate-300">Klik untuk unggah foto</p>
                <p class="text-sm text-slate-500 dark:text-slate-500 mt-2">Pastikan foto daun atau hama terlihat jelas</p>
                
                <!-- Tombol Fallback -->
                <button class="mt-6 bg-green-600 hover:bg-green-700 text-white font-medium px-6 py-2.5 rounded-xl transition-colors pointer-events-none">
                    Pilih File
                </button>
            </div>

            <!-- Jika sudah ada gambar (Preview) -->
            <div x-show="hasImage" style="display: none;" class="flex-1 flex flex-col items-center justify-center">
                <div class="w-full h-48 rounded-2xl overflow-hidden border border-slate-200 dark:border-slate-700 mb-6 bg-slate-100 dark:bg-slate-800">
                    <img :src="imagePreview" class="w-full h-full object-contain" alt="Preview">
                </div>
                
                <button @click="analyzeImage()" 
                        :disabled="isAnalyzing"
                        class="w-full bg-green-600 hover:bg-green-700 text-white font-bold px-6 py-3.5 rounded-xl transition-all shadow-md flex items-center justify-center gap-2 disabled:opacity-70 disabled:cursor-not-allowed outline-none">
                    <span x-show="!isAnalyzing">Jalankan Analisis LLM</span>
                    <span x-show="isAnalyzing" style="display: none;" class="flex items-center gap-2">
                        <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        Menganalisis...
                    </span>
                </button>
            </div>

            <input type="file" id="fileUploader" @change="handleFileUpload" class="hidden" accept="image/*">
        </div>

        <!-- Result Area -->
        <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 p-8 shadow-sm flex flex-col justify-center">
            
            <!-- State: Empty / Waiting -->
            <div x-show="!showResult && !isAnalyzing" class="text-center">
                <div class="w-20 h-20 bg-slate-50 dark:bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-slate-300 dark:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002 2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                </div>
                <p class="text-slate-400 font-medium">Hasil diagnosis AI akan muncul di sini.</p>
            </div>

            <!-- State: Analyzing (Skeleton Loader) -->
            <div x-show="isAnalyzing" style="display: none;" class="space-y-6 animate-pulse">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 bg-slate-200 dark:bg-slate-700 rounded-2xl"></div>
                    <div class="flex-1 space-y-2">
                        <div class="h-4 bg-slate-200 dark:bg-slate-700 rounded w-1/4"></div>
                        <div class="h-6 bg-slate-200 dark:bg-slate-700 rounded w-3/4"></div>
                    </div>
                </div>
                <div class="h-24 bg-slate-200 dark:bg-slate-700 rounded-2xl w-full"></div>
            </div>

            <!-- State: Result Ready -->
            <div x-show="showResult" style="display: none;" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-y-4" x-transition:enter-end="opacity-100 transform translate-y-0">
                <h3 class="text-sm font-bold text-slate-800 dark:text-white mb-6 uppercase tracking-wider flex items-center gap-2">
                    <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    Hasil Diagnosis AI
                </h3>

                <div class="bg-slate-50 dark:bg-slate-800/50 rounded-3xl p-6 border border-slate-100 dark:border-slate-700">
                    <div class="flex items-start gap-4 mb-6">
                        <div class="mt-1">
                            <span class="bg-red-100 text-red-700 dark:bg-red-500/20 dark:text-red-400 text-xs font-bold px-2.5 py-1 rounded-md uppercase tracking-wider">Hama Terdeteksi</span>
                            <!-- NANTI: Teks ini diganti variabel BE  {{-- {{ $nama_hama }} --}}-->
                            <h4 class="text-xl font-bold text-slate-800 dark:text-white mt-2">Wereng Coklat</h4>
                            <p class="text-sm text-slate-500 dark:text-slate-400 italic">Nilaparvata lugens</p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="bg-white dark:bg-slate-900 p-5 rounded-2xl border border-slate-100 dark:border-slate-700 shadow-sm">
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Rekomendasi LLM</p>
                            <!-- NANTI: Teks ini diganti variabel BE  {{-- {{ $rekomendasi }} --}}-->
                            <p class="text-sm text-slate-700 dark:text-slate-300 leading-relaxed">
                                Tanaman terserang wereng coklat tingkat sedang. Segera gunakan pestisida berbahan aktif <strong class="text-green-600 dark:text-green-400">Pimetrozin 50%</strong>. <br><br>
                                <strong>Dosis:</strong> 1.5 gram per liter air. Semprotkan pada pangkal batang padi di sore hari.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('diseaseComponent', () => ({
            hasImage: false,
            imagePreview: null,
            isAnalyzing: false,
            showResult: false,

            triggerUpload() {
                document.getElementById('fileUploader').click();
            },

            handleFileUpload(event) {
                const file = event.target.files[0];
                if (file) {
                    this.hasImage = true;
                    this.showResult = false;
                    this.imagePreview = URL.createObjectURL(file);
                }
            },

            analyzeImage() {
                if(!this.hasImage) return;
                this.isAnalyzing = true;
                this.showResult = false;
                
                // Simulasi Backend Processing 3 detik
                setTimeout(() => {
                    this.isAnalyzing = false;
                    this.showResult = true;
                }, 3000); 
            },

            resetArea() {
                this.hasImage = false;
                this.imagePreview = null;
                this.showResult = false;
                document.getElementById('fileUploader').value = '';
            }
        }));
    });
</script>
@endsection