@extends('layouts.app')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div x-data="diseaseComponent" class="max-w-5xl mx-auto space-y-6">
    <div class="mb-8">
        <h1 class="text-2xl font-black text-slate-800 dark:text-white">Klasifikasi Penyakit</h1>
        <p class="text-slate-500 dark:text-slate-400 text-sm mt-1">Unggah foto daun tanaman yang terkena penyakit untuk diagnosis Vision AI.</p>
    </div>

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

            calculateLaplacianVariance(canvas, ctx) {
                const width = canvas.width;
                const height = canvas.height;
                const imageData = ctx.getImageData(0, 0, width, height);
                const data = imageData.data;

                // Ubah gambar ke format Grayscale terlebih dahulu
                const grayscale = new Uint8ClampedArray(width * height);
                for (let i = 0; i < data.length; i += 4) {
                    grayscale[i / 4] = 0.299 * data[i] + 0.587 * data[i + 1] + 0.114 * data[i + 2];
                }

                let mean = 0;
                let laplacianValues = [];

                // Terapkan matriks kernel Laplacian 3x3 untuk mendeteksi tepian
                for (let y = 1; y < height - 1; y++) {
                    for (let x = 1; x < width - 1; x++) {
                        const idx = y * width + x;
                        const laplacian = grayscale[(y - 1) * width + x] + // Atas
                                          grayscale[(y + 1) * width + x] + // Bawah
                                          grayscale[y * width + (x - 1)] + // Kiri
                                          grayscale[y * width + (x + 1)] - // Kanan
                                          4 * grayscale[idx];              // Tengah

                        laplacianValues.push(laplacian);
                        mean += laplacian;
                    }
                }

                if (laplacianValues.length === 0) return 0;
                mean /= laplacianValues.length;

                // Hitung nilai Variance dari hasil filter Laplacian
                let variance = 0;
                for (let i = 0; i < laplacianValues.length; i++) {
                    variance += Math.pow(laplacianValues[i] - mean, 2);
                }
                
                return variance / laplacianValues.length;
            },

            handleFileUpload(event) {
                const file = event.target.files[0];
                if (file) {
                    const canvas = document.createElement('canvas');
                    const ctx = canvas.getContext('2d');
                    const img = new Image();
                    
                    img.onload = () => {
                        // PENTING: Perkecil resolusi (resize) di background agar browser tidak hang 
                        const MAX_DIMENSION = 350; 
                        let scale = 1;
                        if (img.width > MAX_DIMENSION || img.height > MAX_DIMENSION) {
                            scale = Math.min(MAX_DIMENSION / img.width, MAX_DIMENSION / img.height);
                        }
                        
                        canvas.width = img.width * scale;
                        canvas.height = img.height * scale;
                        ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
                        
                        // Eksekusi fungsi deteksi buram
                        const laplacianVariance = this.calculateLaplacianVariance(canvas, ctx);
                        
                        // Nilai ambang batas (threshold) buram. 
                        if (laplacianVariance < 50) { 
                            Swal.fire({
                                icon: 'warning',
                                title: 'Gambar Buram!',
                                text: 'Gambar terlalu buram (fokus tidak jelas). Silakan ambil foto ulang agar AI bisa mendiagnosis dengan akurat.',
                                confirmButtonColor: '#16a34a',
                            });
                            
                            // Reset input agar pengguna bisa upload file lain
                            document.getElementById('fileUploader').value = '';
                            return;
                        }

                        // Jika gambar tajam dan lolos deteksi
                        this.hasImage = true;
                        this.showResult = false;
                        this.imagePreview = URL.createObjectURL(file);
                        this.selectedFile = file;
                    };
                    img.src = URL.createObjectURL(file);
                }
            },

            async analyzeImage() {
                if (!this.selectedFile) return;

                // 1. Ubah UI ke mode Loading
                this.isAnalyzing = true;
                this.showResult = false;

                try {
                    // Simulasi delay fetch ke Backend/LLM selama 2 detik
                    await new Promise(resolve => setTimeout(resolve, 2000));
                    
                    // Nanti logika fetch axios/fetch API yang asli dimasukkan ke sini 
                    // dan meng-update 'this.diseaseResult.disease' serta 'this.$refs.llmExplanation.innerHTML'
                    
                    // Simulasi hasil respons:
                    this.diseaseResult = { disease: 'Blight (Hawar Daun)' };
                    this.$refs.llmExplanation.innerHTML = `
                        <p class="mb-2">Berdasarkan analisis citra, daun ini menunjukkan gejala khas penyakit Hawar Daun (Blight).</p>
                        <ul class="list-disc pl-5 space-y-1 text-xs">
                            <li><b>Penyebab:</b> Infeksi jamur atau bakteri akibat kelembapan tinggi.</li>
                            <li><b>Tindakan:</b> Pangkas daun yang terinfeksi dan semprotkan fungisida.</li>
                        </ul>
                    `;
                    
                    // 2. Tampilkan area hasil
                    this.showResult = true;

                } catch (error) {
                    console.error("Error analyzing image:", error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal Memproses',
                        text: 'Terjadi kesalahan saat menghubungi server AI.',
                        confirmButtonColor: '#ef4444',
                    });
                } finally {
                    // 3. Matikan mode loading agar tombol kembali normal
                    this.isAnalyzing = false;
                }
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

            calculateLaplacianVariance(canvas, ctx) {
                const width = canvas.width;
                const height = canvas.height;
                const imageData = ctx.getImageData(0, 0, width, height);
                const data = imageData.data;

                const grayscale = new Uint8ClampedArray(width * height);
                for (let i = 0; i < data.length; i += 4) {
                    grayscale[i / 4] = 0.299 * data[i] + 0.587 * data[i + 1] + 0.114 * data[i + 2];
                }

                let mean = 0;
                let laplacianValues = [];

                for (let y = 1; y < height - 1; y++) {
                    for (let x = 1; x < width - 1; x++) {
                        const idx = y * width + x;
                        const laplacian = 
                            grayscale[(y - 1) * width + (x - 1)] + grayscale[(y - 1) * width + x] + grayscale[(y - 1) * width + (x + 1)] +
                            grayscale[y * width + (x - 1)]       +                                  grayscale[y * width + (x + 1)] +
                            grayscale[(y + 1) * width + (x - 1)] + grayscale[(y + 1) * width + x] + grayscale[(y + 1) * width + (x + 1)] - 
                            (8 * grayscale[idx]);

                        laplacianValues.push(laplacian);
                        mean += laplacian;
                    }
                }

                if (laplacianValues.length === 0) return 0;
                mean /= laplacianValues.length;

                let variance = 0;
                for (let i = 0; i < laplacianValues.length; i++) {
                    variance += Math.pow(laplacianValues[i] - mean, 2);
                }
                
                return variance / laplacianValues.length;
            },

            handleFileUpload(event) {
                const file = event.target.files[0];
                if (file) {
                    const canvas = document.createElement('canvas');
                    const ctx = canvas.getContext('2d');
                    const img = new Image();
                    
                    img.onload = () => {
                        const MAX_DIMENSION = 400; 
                        let scale = 1;
                        if (img.width > MAX_DIMENSION || img.height > MAX_DIMENSION) {
                            scale = Math.min(MAX_DIMENSION / img.width, MAX_DIMENSION / img.height);
                        }
                        
                        canvas.width = img.width * scale;
                        canvas.height = img.height * scale;
                        ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
                        
                        const laplacianVariance = this.calculateLaplacianVariance(canvas, ctx);
                        const finalScore = Math.round(laplacianVariance);
                        
                        // KITA NAIKKAN THRESHOLD KE 12.000 
                        // Karena gambar buram Anda bernilai 7.660
                        const THRESHOLD = 12000; 

                        if (finalScore < THRESHOLD) { 
                            // Tampilkan skor di alert agar mudah dites
                            Swal.fire({
                                icon: 'warning',
                                title: 'Gambar Terlalu Buram!',
                                html: `Foto tidak fokus atau kurang cahaya.<br><br>
                                       <span class="text-xs text-slate-500">Skor Ketajaman Anda: <b>${finalScore}</b><br>
                                       Standar Minimum: <b>${THRESHOLD}</b></span>`,
                                confirmButtonColor: '#16a34a',
                            });
                            
                            document.getElementById('fileUploader').value = '';
                            return;
                        }

                        this.hasImage = true;
                        this.showResult = false;
                        this.imagePreview = URL.createObjectURL(file);
                        this.selectedFile = file;
                    };
                    img.src = URL.createObjectURL(file);
                }
            },

            async analyzeImage() {
                if (!this.selectedFile) return;

                this.isAnalyzing = true;
                this.showResult = false;

                try {
                    await new Promise(resolve => setTimeout(resolve, 2000));
                    
                    this.diseaseResult = { disease: 'Blight (Hawar Daun)' };
                    this.$refs.llmExplanation.innerHTML = `
                        <p class="mb-2">Berdasarkan analisis citra, daun ini menunjukkan gejala khas penyakit Hawar Daun (Blight).</p>
                        <ul class="list-disc pl-5 space-y-1 text-xs">
                            <li><b>Penyebab:</b> Infeksi jamur atau bakteri akibat kelembapan tinggi.</li>
                            <li><b>Tindakan:</b> Pangkas daun yang terinfeksi dan semprotkan fungisida.</li>
                        </ul>
                    `;
                    
                    this.showResult = true;
                } catch (error) {
                    console.error("Error analyzing image:", error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal Memproses',
                        text: 'Terjadi kesalahan saat menghubungi server AI.',
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
                document.getElementById('fileUploader').value = '';
            }
        }));
    });
</script>
@endsection