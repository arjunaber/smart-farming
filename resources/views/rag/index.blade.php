@extends('layouts.app')

@section('content')
    <div class="max-w-5xl mx-auto px-4 py-8 space-y-8">

        {{-- ================================================================
         HEADER
    ================================================================ --}}
        <div>
            <h1 class="text-3xl font-black text-slate-800 dark:text-white uppercase tracking-tight">
                Manajemen Knowledge RAG
            </h1>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-[0.2em] mt-1">
                Update Basis Pengetahuan AI Asisten
            </p>
        </div>

        {{-- ================================================================
         FLASH MESSAGES
    ================================================================ --}}
        @if (session('success'))
            <div id="flashSuccess"
                class="flex items-start gap-3 bg-green-50 dark:bg-green-500/10 border border-green-200 dark:border-green-500/30 text-green-700 dark:text-green-400 rounded-2xl px-5 py-4 text-sm font-semibold">
                <svg class="w-5 h-5 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if (session('error') || $errors->any())
            <div
                class="flex items-start gap-3 bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-500/30 text-red-700 dark:text-red-400 rounded-2xl px-5 py-4 text-sm font-semibold">
                <svg class="w-5 h-5 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div>
                    @if (session('error'))
                        <p>{{ session('error') }}</p>
                    @endif
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- ================================================================
         UPLOAD CARD
    ================================================================ --}}
        <div
            class="bg-white dark:bg-slate-900 rounded-[2rem] p-6 md:p-10 shadow-xl border border-slate-100 dark:border-slate-800">

            <h2 class="text-xs font-black text-slate-400 uppercase tracking-[0.2em] mb-6 text-center">
                Upload Dokumen Referensi
            </h2>

            <form action="{{ route('rag.upload') }}" method="POST" enctype="multipart/form-data" id="uploadForm">
                @csrf

                {{-- Drop Zone --}}
                <div id="dropZone"
                    class="relative w-full h-64 border-2 border-dashed border-slate-300 dark:border-slate-700
                        bg-slate-50 dark:bg-slate-800/50 hover:bg-slate-100 dark:hover:bg-slate-800
                        rounded-3xl flex flex-col items-center justify-center transition-all duration-200
                        cursor-pointer overflow-hidden"
                    onclick="document.getElementById('fileInput').click()">

                    <input type="file" name="rag_document" id="fileInput" class="hidden" accept=".pdf,.txt,.docx"
                        required>

                    {{-- Empty State --}}
                    <div id="emptyState" class="text-center pointer-events-none select-none">
                        <div class="bg-white dark:bg-slate-800 p-5 rounded-full shadow-sm mb-4 inline-block">
                            <svg class="w-10 h-10 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                            </svg>
                        </div>
                        <p class="text-sm font-bold text-slate-600 dark:text-slate-300">
                            Klik atau seret & lepas file ke sini
                        </p>
                        <p class="text-xs text-slate-400 mt-1.5 font-medium">
                            PDF · TXT · DOCX &nbsp;·&nbsp; Maks. 10 MB
                        </p>
                    </div>

                    {{-- Filled State --}}
                    <div id="filledState" class="text-center pointer-events-none select-none hidden">
                        <div class="bg-green-100 dark:bg-green-500/20 p-5 rounded-full mb-4 inline-block">
                            <svg class="w-10 h-10 text-green-600 dark:text-green-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <p id="fileNameText"
                            class="text-sm font-black text-green-600 dark:text-green-400 max-w-xs truncate px-4"></p>
                        <p id="fileSizeText" class="text-xs text-slate-400 mt-1 font-medium"></p>
                    </div>
                </div>

                {{-- Collection Name --}}
                <div class="mt-5">
                    <label for="collectionName"
                        class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">
                        Nama Koleksi <span class="text-slate-300 normal-case tracking-normal">(opsional)</span>
                    </label>
                    <input type="text" name="collection_name" id="collectionName" placeholder="default"
                        class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700
                              rounded-xl px-4 py-2.5 text-sm font-medium text-slate-700 dark:text-slate-200
                              placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-green-500
                              focus:border-transparent transition">
                </div>

                {{-- Submit --}}
                <div class="mt-6 flex justify-end">
                    <button type="submit" id="submitBtn"
                        class="w-full sm:w-auto inline-flex items-center justify-center gap-2
                               bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800
                               text-white font-black py-3.5 px-8 rounded-2xl shadow-lg shadow-green-600/20
                               active:scale-[0.98] transition-all uppercase tracking-widest text-xs
                               disabled:opacity-60 disabled:cursor-not-allowed">
                        <svg id="submitIcon" class="w-4 h-4 shrink-0" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" />
                        </svg>
                        <svg id="loadingIcon" class="w-4 h-4 shrink-0 hidden animate-spin" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        <span id="submitLabel">Proses ke LLM Knowledge</span>
                    </button>
                </div>
            </form>
        </div>

        {{-- ================================================================
         DOCUMENT TABLE
    ================================================================ --}}
        <div
            class="bg-white dark:bg-slate-900 rounded-[2rem] shadow-xl border border-slate-100 dark:border-slate-800 overflow-hidden">

            <div class="px-6 py-5 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
                <h2 class="text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-[0.2em]">
                    Riwayat Dokumen
                </h2>
                <span class="text-xs font-bold text-slate-400">
                    {{ $documents->total() }} dokumen
                </span>
            </div>

            @if ($documents->isEmpty())
                <div class="py-16 text-center">
                    <p class="text-slate-400 text-sm font-medium">Belum ada dokumen yang diunggah.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="text-left bg-slate-50 dark:bg-slate-800/50">
                                <th class="px-6 py-3 text-[0.65rem] font-black text-slate-400 uppercase tracking-widest">
                                    Nama File</th>
                                <th class="px-4 py-3 text-[0.65rem] font-black text-slate-400 uppercase tracking-widest">
                                    Tipe</th>
                                <th class="px-4 py-3 text-[0.65rem] font-black text-slate-400 uppercase tracking-widest">
                                    Ukuran</th>
                                <th class="px-4 py-3 text-[0.65rem] font-black text-slate-400 uppercase tracking-widest">
                                    Koleksi</th>
                                <th class="px-4 py-3 text-[0.65rem] font-black text-slate-400 uppercase tracking-widest">
                                    Status</th>
                                <th class="px-4 py-3 text-[0.65rem] font-black text-slate-400 uppercase tracking-widest">
                                    Diunggah</th>
                                <th class="px-4 py-3 text-[0.65rem] font-black text-slate-400 uppercase tracking-widest">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                            @foreach ($documents as $doc)
                                @php $badge = $doc->status_badge; @endphp
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/40 transition-colors">

                                    {{-- Nama File --}}
                                    <td class="px-6 py-4 max-w-[14rem]">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-800 flex items-center justify-center shrink-0">
                                                @if ($doc->file_type === 'pdf')
                                                    <svg class="w-4 h-4 text-red-500" fill="currentColor"
                                                        viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd"
                                                            d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                @elseif($doc->file_type === 'docx')
                                                    <svg class="w-4 h-4 text-blue-500" fill="currentColor"
                                                        viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd"
                                                            d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                @else
                                                    <svg class="w-4 h-4 text-slate-500" fill="currentColor"
                                                        viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd"
                                                            d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                @endif
                                            </div>
                                            <span class="font-semibold text-slate-700 dark:text-slate-200 truncate text-xs"
                                                title="{{ $doc->original_filename }}">
                                                {{ $doc->original_filename }}
                                            </span>
                                        </div>
                                    </td>

                                    {{-- Tipe --}}
                                    <td class="px-4 py-4">
                                        <span
                                            class="text-xs font-bold uppercase text-slate-400">{{ $doc->file_type }}</span>
                                    </td>

                                    {{-- Ukuran --}}
                                    <td class="px-4 py-4">
                                        <span
                                            class="text-xs text-slate-500 dark:text-slate-400">{{ $doc->file_size_human }}</span>
                                    </td>

                                    {{-- Koleksi --}}
                                    <td class="px-4 py-4">
                                        <span
                                            class="inline-block bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300
                                                 text-xs font-bold px-2.5 py-1 rounded-lg">
                                            {{ $doc->collection_name }}
                                        </span>
                                    </td>

                                    {{-- Status --}}
                                    <td class="px-4 py-4">
                                        @php
                                            $colorMap = [
                                                'blue' =>
                                                    'bg-blue-100 text-blue-700 dark:bg-blue-500/20 dark:text-blue-400',
                                                'yellow' =>
                                                    'bg-yellow-100 text-yellow-700 dark:bg-yellow-500/20 dark:text-yellow-400',
                                                'green' =>
                                                    'bg-green-100 text-green-700 dark:bg-green-500/20 dark:text-green-400',
                                                'red' => 'bg-red-100 text-red-700 dark:bg-red-500/20 dark:text-red-400',
                                                'gray' =>
                                                    'bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-400',
                                            ];
                                        @endphp
                                        <span
                                            class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold
                                                 {{ $colorMap[$badge['color']] ?? $colorMap['gray'] }}">
                                            @if ($doc->status === 'processing')
                                                <span
                                                    class="w-1.5 h-1.5 rounded-full bg-yellow-500 animate-pulse inline-block"></span>
                                            @elseif($doc->status === 'processed')
                                                <span class="w-1.5 h-1.5 rounded-full bg-green-500 inline-block"></span>
                                            @elseif($doc->status === 'failed')
                                                <span class="w-1.5 h-1.5 rounded-full bg-red-500 inline-block"></span>
                                            @else
                                                <span class="w-1.5 h-1.5 rounded-full bg-blue-500 inline-block"></span>
                                            @endif
                                            {{ $badge['label'] }}
                                        </span>
                                    </td>

                                    {{-- Tanggal --}}
                                    <td class="px-4 py-4">
                                        <span class="text-xs text-slate-400"
                                            title="{{ $doc->created_at->format('d M Y H:i') }}">
                                            {{ $doc->created_at->diffForHumans() }}
                                        </span>
                                    </td>

                                    {{-- Aksi --}}
                                    <td class="px-4 py-4">
                                        <form action="{{ route('rag.destroy', $doc) }}" method="POST"
                                            onsubmit="return confirm('Yakin ingin menghapus dokumen ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="p-1.5 rounded-lg text-slate-400 hover:text-red-500 hover:bg-red-50
                                                       dark:hover:bg-red-500/10 transition-colors"
                                                title="Hapus">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if ($documents->hasPages())
                    <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-800">
                        {{ $documents->links() }}
                    </div>
                @endif
            @endif
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
            const fileSizeText = document.getElementById('fileSizeText');
            const uploadForm = document.getElementById('uploadForm');
            const submitBtn = document.getElementById('submitBtn');
            const submitLabel = document.getElementById('submitLabel');
            const submitIcon = document.getElementById('submitIcon');
            const loadingIcon = document.getElementById('loadingIcon');

            // Auto-dismiss flash message after 4 seconds
            const flash = document.getElementById('flashSuccess');
            if (flash) setTimeout(() => flash.remove(), 4000);

            // Prevent browser default drag-and-drop
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(evt => {
                dropZone.addEventListener(evt, e => {
                    e.preventDefault();
                    e.stopPropagation();
                });
                document.body.addEventListener(evt, e => {
                    e.preventDefault();
                    e.stopPropagation();
                });
            });

            // Visual feedback on drag over
            ['dragenter', 'dragover'].forEach(evt => {
                dropZone.addEventListener(evt, () => {
                    dropZone.classList.add('border-green-500', 'bg-green-50',
                        'dark:bg-green-500/10', 'scale-[1.01]');
                    dropZone.classList.remove('border-slate-300', 'dark:border-slate-700');
                });
            });

            ['dragleave', 'drop'].forEach(evt => {
                dropZone.addEventListener(evt, () => {
                    dropZone.classList.remove('border-green-500', 'bg-green-50',
                        'dark:bg-green-500/10', 'scale-[1.01]');
                    dropZone.classList.add('border-slate-300', 'dark:border-slate-700');
                });
            });

            // Handle drop
            dropZone.addEventListener('drop', function(e) {
                const files = e.dataTransfer.files;
                if (files.length > 0) {
                    fileInput.files = files;
                    updateUI(files[0]);
                }
            });

            // Handle manual file select
            fileInput.addEventListener('change', function() {
                if (this.files.length > 0) updateUI(this.files[0]);
            });

            function formatSize(bytes) {
                if (bytes < 1024) return bytes + ' B';
                if (bytes < 1048576) return (bytes / 1024).toFixed(1) + ' KB';
                return (bytes / 1048576).toFixed(2) + ' MB';
            }

            function updateUI(file) {
                emptyState.classList.add('hidden');
                filledState.classList.remove('hidden');
                fileNameText.textContent = file.name;
                fileSizeText.textContent = formatSize(file.size);
            }

            // Show loading state on submit
            uploadForm.addEventListener('submit', function() {
                submitBtn.disabled = true;
                submitIcon.classList.add('hidden');
                loadingIcon.classList.remove('hidden');
                submitLabel.textContent = 'Mengunggah...';
            });
        });
    </script>
@endpush
