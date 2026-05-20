@extends('layouts.app')

@section('content')
    <div class="max-w-2xl mx-auto">
        <div class="bg-white dark:bg-slate-900 rounded-3xl shadow-2xl p-8 border border-slate-100 dark:border-slate-800">
            <div class="flex items-center gap-4 mb-8">
                <a href="{{ route('logbook.index') }}"
                    class="p-2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </a>
                <h1 class="text-2xl font-black text-slate-800 dark:text-white">Tambah Logbook</h1>
            </div>

            <form method="POST" action="{{ route('logbook.store') }}">
                @csrf
                <div class="space-y-6">
                    {{-- PILIH SIKLUS TANAM --}}
                    <div>
                        <label class="block text-sm font-bold text-slate-500 mb-2">Siklus Tanam (Lahan)</label>
                        <select name="siklus_tanam_id"
                            class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl px-4 py-3 outline-none focus:ring-2 focus:ring-green-500"
                            required>
                            <option value="">-- Pilih Siklus Aktif --</option>
                            @foreach ($siklusList as $s)
                                <option value="{{ $s->id }}">
                                    {{ $s->lahan->nama_lahan }} - {{ $s->komoditas->nama_komoditas }} (Mulai:
                                    {{ \Carbon\Carbon::parse($s->tanggal_tanam)->format('d M Y') }})
                                </option>
                            @endforeach
                        </select>
                        @error('siklus_tanam_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- JENIS KEGIATAN --}}
                        <div>
                            <label class="block text-sm font-bold text-slate-500 mb-2">Jenis Kegiatan</label>
                            <select name="jenis_kegiatan"
                                class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl px-4 py-3 outline-none focus:ring-2 focus:ring-green-500"
                                required>
                                <option value="Pembenihan">🌱 Pembenihan</option>
                                <option value="Pemupukan">🧪 Pemupukan</option>
                                <option value="Pengairan">💧 Pengairan</option>
                                <option value="Penyemprotan">💨 Penyemprotan (Pestisida)</option>
                                <option value="Panen">🌾 Panen</option>
                                <option value="Lainnya">📝 Lainnya</option>
                            </select>
                        </div>

                        {{-- TANGGAL --}}
                        <div>
                            <label class="block text-sm font-bold text-slate-500 mb-2">Tanggal Aktivitas</label>
                            <input type="date" name="activity_date"
                                value="{{ old('activity_date', now()->format('Y-m-d')) }}"
                                class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl px-4 py-3"
                                required>
                        </div>
                    </div>

                    {{-- JUDUL / TITLE --}}
                    <div>
                        <label class="block text-sm font-bold text-slate-500 mb-2">Judul Ringkas</label>
                        <input type="text" name="title" value="{{ old('title') }}"
                            placeholder="Contoh: Pemupukan Dasar Urea"
                            class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl px-4 py-3 outline-none focus:ring-2 focus:ring-green-500"
                            required>
                    </div>

                    {{-- DESKRIPSI --}}
                    <div>
                        <label class="block text-sm font-bold text-slate-500 mb-2">Keterangan Detail</label>
                        <textarea name="description" rows="3" placeholder="Jelaskan detail aktivitas..."
                            class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl px-4 py-3 outline-none focus:ring-2 focus:ring-green-500">{{ old('description') }}</textarea>
                    </div>

                    {{-- KUANTITAS & SATUAN --}}
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-slate-500 mb-2">Kuantitas (Opsional)</label>
                            <input type="number" step="0.01" name="kuantitas" value="{{ old('kuantitas') }}"
                                placeholder="0.00"
                                class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl px-4 py-3 outline-none focus:ring-2 focus:ring-green-500">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-500 mb-2">Satuan</label>
                            <input type="text" name="satuan" value="{{ old('satuan') }}" placeholder="Kg, Liter, dll"
                                class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl px-4 py-3 outline-none focus:ring-2 focus:ring-green-500">
                        </div>
                    </div>

                    <button type="submit"
                        class="w-full bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-bold py-4 px-8 rounded-2xl shadow-lg shadow-green-200 transition-all">
                        Simpan Logbook
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
