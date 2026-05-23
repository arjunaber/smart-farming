@extends('layouts.app')

@section('content')
    <div class="max-w-3xl mx-auto px-4 py-6">
        {{-- Header Section --}}
        <div class="mb-8">
            <h1 class="text-3xl font-black text-slate-800 dark:text-white mb-2 uppercase tracking-tight">Manajemen Knowledge RAG</h1>
            <p class="text-sm font-bold text-slate-500 uppercase tracking-widest">Update Basis Pengetahuan AI Asisten</p>
        </div>

        {{-- Form Container --}}
        <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] p-6 md:p-10 shadow-2xl border border-slate-100 dark:border-slate-800">
            
            <form action="{{ url('/rag/upload') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-8">
                    <label class="block text-xs font-black text-slate-400 uppercase tracking-[0.2em] mb-4 text-center">
                        Upload Dokumen Referensi (PDF / TXT)
                    </label>

                    {{-- Area Drag & Drop dengan ID Khusus --}}
                    <div id="dropZone" 
                         class="relative w-full h-72 border-2 border-dashed border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-3xl flex flex-col items-center justify-center transition-all cursor-pointer overflow-hidden"
                         onclick="document.getElementById('fileInput').click()">

                        <input type="file" name="rag_document" id="fileInput" class="hidden" accept=".pdf,.txt,.docx" required>

                        {{-- Tampilan Awal (Kosong) --}}
                        <div id="emptyState" class="text-center pointer-events-none">
                            <div class="bg-white dark:bg-slate-800 p-5 rounded-full shadow-sm mb-4 inline-block">
                                <svg class="w-10 h-10 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                </svg>
                            </div>
                            <p class="text-base font-bold text-slate-600 dark:text-slate-300">Klik atau Drag & Drop file ke sini</p>
                            <p class="text-xs text-slate-400 mt-2 font-medium">Mendukung format: PDF, TXT (Max: 10MB)</p>
                        </div>

                        {{-- Tampilan Setelah File Dimasukkan --}}
                        <div id="filledState" class="text-center pointer-events-none hidden">
                            <div class="bg-green-100 dark:bg-green-500/20 p-5 rounded-full mb-4 inline-block">
                                <svg class="w-10 h-10 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <p id="fileNameText" class="text-base font-black text-green-600 dark:text-green-400"></p>
                            <p class="text-xs text-slate-400 mt-2 font-bold uppercase tracking-widest">Siap Diunggah</p>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end pt-2">
                    <button type="submit" 
                            class="w-full sm:w-auto bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white font-black py-4 px-8 rounded-2xl shadow-xl shadow-green-600/20 active:scale-[0.98] transition-all uppercase tracking-widest text-xs">
                        Proses ke LLM Knowledge
                    </button>
                </div>
            </form>

        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const dropZone = document.getElementById('dropZone');
        const fileInput = document.getElementById('fileInput');
        const emptyState = document.getElementById('emptyState');
        const filledState = document.getElementById('filledState');
        const fileNameText = document.getElementById('fileNameText');

        // Matikan fungsi standar browser yang akan membuka file jika di-drop di luar kotak
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, preventDefaults, false);
            document.body.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        // Tambahkan efek visual (hover) saat file ditarik melintasi kotak
        ['dragenter', 'dragover'].forEach(eventName => {
            dropZone.addEventListener(eventName, highlight, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, unhighlight, false);
        });

        function highlight(e) {
            dropZone.classList.add('border-green-500', 'bg-green-50', 'dark:bg-green-500/10', 'scale-[1.02]');
            dropZone.classList.remove('border-slate-300', 'dark:border-slate-700');
        }

        function unhighlight(e) {
            dropZone.classList.remove('border-green-500', 'bg-green-50', 'dark:bg-green-500/10', 'scale-[1.02]');
            dropZone.classList.add('border-slate-300', 'dark:border-slate-700');
        }

        // Tangkap data file jika dilepas (drop) di dalam kotak
        dropZone.addEventListener('drop', function(e) {
            let dt = e.dataTransfer;
            let files = dt.files;

            if (files.length > 0) {
                fileInput.files = files; // Salin data file ke input hidden
                updateUI(files[0].name);
            }
        });

        // Tangkap data file jika pengguna melakukan klik kotak (pilih manual)
        fileInput.addEventListener('change', function() {
            if (this.files.length > 0) {
                updateUI(this.files[0].name);
            }
        });

        // Perbarui tampilan interface setelah file berhasil masuk
        function updateUI(fileName) {
            emptyState.classList.add('hidden');
            filledState.classList.remove('hidden');
            fileNameText.textContent = fileName;
        }
    });
</script>
@endpush