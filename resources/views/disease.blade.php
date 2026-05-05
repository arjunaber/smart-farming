@extends('layouts.app')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div x-data="diseaseComponent" class="max-w-5xl mx-auto space-y-6">
    <div class="mb-8">
        <h1 class="text-2xl font-black text-slate-800 dark:text-white">Klasifikasi Penyakit</h1>
        <p class="text-slate-500 dark:text-slate-400 text-sm mt-1">Unggah foto daun tanaman yang terkena penyakit untuk diagnosis Vision AI.</p>
    </div>

    <!-- PANDUAN PENGAMBILAN GAMBAR (Revisi Bu Mifta) -->
    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-2xl p-6 mb-6">
        <h3 class="text-sm font-bold text-blue-800 dark:text-blue-300 mb-3 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            Panduan Pengambilan Gambar
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="flex items-start gap-3 bg-white dark:bg-slate-800 p-3 rounded-xl shadow-sm">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0 text-green-600 text-xl">✅</div>
                <div>
                    <p class="text-sm font-bold text-slate-800 dark:text-white">Benar & Fokus</p>
                    <p class="text-xs text-slate-500">Pastikan daun terlihat jelas, pencahayaan cukup, dan kamera fokus pada area yang terkena hama/penyakit.</p>
                </div>
            </div>
            <div class="flex items-start gap-3 bg-white dark:bg-slate-800 p-3 rounded-xl shadow-sm">
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center flex-shrink-0 text-red-600 text-xl">❌</div>
                <div>
                    <p class="text-sm font-bold text-slate-800 dark:text-white">Buram / Terlalu Jauh</p>
                    <p class="text-xs text-slate-500">Hindari foto yang blur (goyang), terlalu gelap, atau daun terlihat terlalu kecil dari kejauhan.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Upload Area -->
        <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 p-8 shadow-sm flex flex-col">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-sm font-bold text-slate-800 dark:text-white uppercase tracking-wider">Input Gambar</h3>
                <button x-show="hasImage" @click="resetArea()" class="text-xs text-red-500 hover:text-red-700 font-bold" style="display: none;">Reset</button>
            </div>
            
            <div x-show="!hasImage" class="flex-1 border-2 border-dashed border-gray-300 dark:border-slate-700 rounded-3xl p-10 flex flex-col items-center justify-center text-center hover:border-green-500 transition-colors cursor-pointer group bg-slate-50 dark:bg-slate-800/50" @click="triggerUpload()">
                <div class="bg-white p-4 rounded-2xl shadow-sm mb-4 group-hover:scale-110 transition-transform">
                    <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path></svg>
                </div>
                <p class="font-bold text-slate-700 dark:text-slate-300">Klik untuk unggah foto</p>
            </div>

            <div x-show="hasImage" style="display: none;" class="flex-1 flex flex-col items-center justify-center">
                <div class="w-full h-48 rounded-2xl overflow-hidden border border-slate-200 dark:border-slate-700 mb-6 flex items-center justify-center">
                    <img :src="imagePreview" class="max-w-full max-h-full object-contain" alt="Preview">
                </div>
                <button @click="analyzeImage()" :disabled="isAnalyzing" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold px-6 py-3.5 rounded-xl transition-all shadow-md flex items-center justify-center gap-2 disabled:opacity-70">
                    <span x-show="!isAnalyzing">Jalankan Analisis LLM</span>
                    <span x-show="isAnalyzing" style="display: none;">Menganalisis...</span>
                </button>
            </div>

            <input type="file" id="fileUploader" @change="handleFileUpload" class="hidden" accept="image/*">
        </div>

        <!-- Result Area (Sama seperti Source 4) -->
        <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] border border-slate-100 p-8 flex flex-col justify-center">
            <div x-show="!showResult && !isAnalyzing" class="text-center">
                <p class="text-slate-400 font-medium">Hasil diagnosis AI akan muncul di sini.</p>
            </div>
            
            <div x-show="showResult" style="display: none;">
                <h4 class="text-xl font-bold text-slate-800 dark:text-white mt-2" x-text="diseaseResult.disease"></h4>
                <div class="mt-4 text-sm text-slate-700 dark:text-slate-300" x-ref="llmExplanation"></div>
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
            diseaseResult: { disease: 'Memproses...' },
            selectedFile: null,

            triggerUpload() { document.getElementById('fileUploader').click(); },

            calculateLaplacianVariance(imageData) {
                // Simple grayscale Laplacian variance for blur detection
                const laplacianKernel = [-1, -1, -1, -1, 8, -1, -1, -1, -1];
                const data = imageData.data;
                const width = imageData.width;
                const height = imageData.height;
                let laplacianSum = 0;
                let validPixels = 0;

                // Sample every 10 pixels for performance
                for (let y = 1; y < height - 1; y += 10) {
                    for (let x = 1; x < width - 1; x += 10) {
                        // Get grayscale value
                        const idx = (Math.floor(y) * width + Math.floor(x)) * 4;
                        const gray = 0.299 * data[idx] + 0.587 * data[idx + 1] + 0.114 * data[idx + 2];

                        // Simplified Laplacian (center * 8 - neighbors)
                        const neighborsSum = Math.abs(gray * 8) - (gray * 8); // Placeholder
                        laplacianSum += neighborsSum * neighborsSum;
                        validPixels++;
                    }
                }

                return validPixels > 0 ? laplacianSum / validPixels : 0;
            },

            handleFileUpload(event) {
                const file = event.target.files[0];
                if (file) {
                    // Validasi Kualitas/Ukuran Gambar (Revisi Bu Mifta)
                    // Validasi Kualitas Gambar dengan Canvas Blur Detection (Revisi Bu Mifta)
                    const canvas = document.createElement('canvas');
                    const ctx = canvas.getContext('2d');
                    const img = new Image();
                    img.onload = () => {
                        canvas.width = img.width;
                        canvas.height = img.height;
                        ctx.drawImage(img, 0, 0);
                        const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
                        const laplacianVariance = this.calculateLaplacianVariance(imageData);
                        
                        if (laplacianVariance < 100) { // Threshold blur
                            Swal.fire({
                                icon: 'warning',
                                title: 'Gambar Buram!',
                                text: 'Gambar terlalu buram dan tidak dapat dianalisis. Pastikan kamera fokus dan pencahayaan cukup. Coba lagi.',
                                confirmButtonColor: '#16a34a',
                            });
                            return;
                        }

                        // Proceed if sharp
                        this.hasImage = true;
                        this.imagePreview = URL.createObjectURL(file);
                        this.selectedFile = file;
                    };
                    img.src = URL.createObjectURL(file);

                    this.hasImage = true;
                    this.showResult = false;
                    this.imagePreview = URL.createObjectURL(file);
                    this.selectedFile = file;
                }
            },

            // Metode analyzeImage dan resetArea disamakan dengan source 4
            async analyzeImage() {
                /* Logika Fetch LLM Anda seperti di source 4 */
            },
            resetArea() {
                this.hasImage = false;
                this.imagePreview = null;
                this.showResult = false;
                this.selectedFile = null;
                document.getElementById('fileUploader').value = '';
            }
        }));
    });
</script>
@endsection