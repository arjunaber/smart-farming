@extends('layouts.app')

@section('content')
    <div class="max-w-2xl mx-auto px-4 py-6">
        <div
            class="bg-white dark:bg-slate-900 rounded-[2.5rem] shadow-2xl p-6 md:p-10 border border-slate-100 dark:border-slate-800">

            {{-- Header --}}
            <div class="flex items-center gap-4 mb-10">
                <a href="{{ route('lahan.show', $lahan) }}"
                    class="p-3 bg-slate-50 dark:bg-slate-800 rounded-2xl text-slate-400 hover:text-green-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </a>
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1">Manajemen Lahan</p>
                    <h1 class="text-2xl font-black text-slate-800 dark:text-white uppercase">Edit Data Lahan</h1>
                </div>
            </div>

            <form method="POST" action="{{ route('lahan.update', $lahan) }}" class="space-y-8">
                @csrf
                @method('PUT')

                <div class="space-y-6">
                    {{-- Nama Lahan --}}
                    <div class="group">
                        <label
                            class="block text-xs font-black text-slate-500 mb-2 uppercase tracking-widest group-focus-within:text-green-600 transition-colors">Nama
                            Identitas Lahan</label>
                        <input type="text" name="nama_lahan" value="{{ old('nama_lahan', $lahan->nama_lahan) }}"
                            class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-2xl px-5 py-4 focus:ring-4 focus:ring-green-500/10 focus:border-green-500 outline-none transition-all font-bold"
                            placeholder="Contoh: Blok Sawah Utara" required>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Lokasi --}}
                        <div>
                            <label class="block text-xs font-black text-slate-400 uppercase tracking-[0.2em] mb-3">Lokasi
                                Wilayah (BMKG)</label>
                            <select name="lokasi"
                                class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-2xl px-5 py-4 text-lg font-bold focus:ring-4 focus:ring-green-500/10 focus:border-green-500 outline-none appearance-none cursor-pointer transition-all"
                                required>
                                <option value="">Pilih Wilayah Monitoring</option>
                                @foreach ($lokasiList as $id => $nama)
                                    <option value="{{ $id }}"
                                        {{ old('lokasi', $lahan->lokasi ?? '') == $id ? 'selected' : '' }}>
                                        {{ $nama }}
                                    </option>
                                @endforeach
                            </select>
                            @error('lokasi')
                                <p class="text-red-500 text-xs mt-2 font-bold">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Luas --}}
                        <div>
                            <label class="block text-xs font-black text-slate-500 mb-2 uppercase tracking-widest">Luas
                                (Hektar)</label>
                            <div class="relative">
                                <input type="number" step="0.01" name="luas" value="{{ old('luas', $lahan->luas) }}"
                                    class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-2xl px-5 py-4 focus:ring-4 focus:ring-green-500/10 focus:border-green-500 outline-none transition-all font-bold"
                                    required>
                                <span class="absolute right-5 top-1/2 -translate-y-1/2 font-black text-slate-400">HA</span>
                            </div>
                        </div>
                    </div>

                    {{-- Status Lahan --}}
                    <div>
                        <label class="block text-xs font-black text-slate-500 mb-2 uppercase tracking-widest">Status
                            Operasional</label>
                        <select name="status"
                            class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-2xl px-5 py-4 focus:ring-4 focus:ring-green-500/10 focus:border-green-500 outline-none transition-all font-bold appearance-none">
                            <option value="Aktif" {{ old('status', $lahan->status) == 'Aktif' ? 'selected' : '' }}>🟢 Aktif
                                / Produktif</option>
                            <option value="Istirahat" {{ old('status', $lahan->status) == 'Istirahat' ? 'selected' : '' }}>
                                🟡 Istirahat Lahan</option>
                            <option value="Persiapan" {{ old('status', $lahan->status) == 'Persiapan' ? 'selected' : '' }}>
                                🔵 Persiapan Tanam</option>
                        </select>
                    </div>

                    {{-- Deskripsi --}}
                    <div>
                        <label class="block text-xs font-black text-slate-500 mb-2 uppercase tracking-widest">Catatan
                            Tambahan</label>
                        <textarea name="deskripsi" rows="3"
                            class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-2xl px-5 py-4 focus:ring-4 focus:ring-green-500/10 focus:border-green-500 outline-none transition-all font-medium italic"
                            placeholder="Tambahkan catatan mengenai kondisi tanah atau irigasi...">{{ old('deskripsi', $lahan->deskripsi) }}</textarea>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="flex flex-col sm:flex-row gap-4 pt-4">
                    <button type="submit"
                        class="flex-1 bg-green-600 hover:bg-green-700 text-white font-black py-5 rounded-2xl transition-all shadow-xl shadow-green-600/20 active:scale-[0.98] uppercase tracking-widest text-sm">
                        Simpan Perubahan
                    </button>
                    <a href="{{ route('lahan.show', $lahan) }}"
                        class="flex-1 text-center bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 font-black py-5 rounded-2xl transition-all uppercase tracking-widest text-sm">
                        Batal
                    </a>
                </div>
            </form>
        </div>

        {{-- Footer Info --}}
        <p class="mt-8 text-center text-[10px] font-black text-slate-400 uppercase tracking-[0.3em]">
            AGA Smart Farming System • {{ date('Y') }}
        </p>
    </div>
@endsection
