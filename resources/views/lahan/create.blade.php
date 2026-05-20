@extends('layouts.app')

@section('content')
    <div class="max-w-2xl mx-auto px-4 py-6">
        {{-- Header Section --}}
        <div class="mb-8">
            <h1 class="text-3xl font-black text-slate-800 dark:text-white mb-2 uppercase tracking-tight">Tambah Lahan Baru
            </h1>
            <p class="text-sm font-bold text-slate-500 uppercase tracking-widest">Registrasi Area Pertanian & Biofarmaka</p>
        </div>

        {{-- Form Card --}}
        {{-- MENGGUNAKAN STANDAR SUBMIT AGAR REDIRECT TERBACA OLEH LAYOUT --}}
        <form method="POST" action="{{ route('lahan.store') }}"
            class="bg-white dark:bg-slate-900 rounded-[2.5rem] p-6 md:p-10 shadow-2xl border border-slate-100 dark:border-slate-800 space-y-8">
            @csrf

            {{-- Nama Lahan --}}
            <div>
                <label class="block text-xs font-black text-slate-400 uppercase tracking-[0.2em] mb-3">Identitas
                    Lahan</label>
                <input type="text" name="nama_lahan" value="{{ old('nama_lahan') }}"
                    class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-2xl px-5 py-4 text-lg font-bold focus:ring-4 focus:ring-green-500/10 focus:border-green-500 outline-none transition-all placeholder:text-slate-300 @error('nama_lahan') border-red-500 @enderror"
                    placeholder="Contoh: Lahan A - Blok Padi" required>
                @error('nama_lahan')
                    <p class="text-red-500 text-xs mt-2 font-bold uppercase tracking-tight">{{ $message }}</p>
                @enderror
            </div>

            {{-- Grid Luas & Lokasi --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-black text-slate-400 uppercase tracking-[0.2em] mb-3">Luas
                        (Estimasi)</label>
                    <div class="relative">
                        <input type="number" name="luas" step="0.01" value="{{ old('luas', 0.25) }}"
                            class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-2xl px-5 py-4 text-lg font-bold focus:ring-4 focus:ring-green-500/10 focus:border-green-500 outline-none transition-all"
                            required>
                        <span class="absolute right-5 top-1/2 -translate-y-1/2 font-black text-slate-300">HA</span>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-black text-slate-400 uppercase tracking-[0.2em] mb-3">Lokasi Wilayah
                        (BMKG)</label>
                    <div class="relative">
                        <select name="lokasi"
                            class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-2xl px-5 py-4 text-lg font-bold focus:ring-4 focus:ring-green-500/10 focus:border-green-500 outline-none appearance-none cursor-pointer transition-all @error('lokasi') border-red-500 @enderror"
                            required>
                            <option value="">Pilih Wilayah</option>
                            @foreach ($lokasiList as $kode => $nama)
                                <option value="{{ $kode }}" {{ old('lokasi') == $kode ? 'selected' : '' }}>
                                    {{ $nama }}
                                </option>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-5 text-slate-400">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7">
                                </path>
                            </svg>
                        </div>
                    </div>
                    @error('lokasi')
                        <p class="text-red-500 text-xs mt-2 font-bold">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Komoditas & Status --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-black text-slate-400 uppercase tracking-[0.2em] mb-3">Komoditas
                        Utama</label>
                    <div class="relative">
                        <select name="komoditas_id"
                            class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-2xl px-5 py-4 text-lg font-bold focus:ring-4 focus:ring-green-500/10 focus:border-green-500 outline-none appearance-none cursor-pointer transition-all @error('komoditas_id') border-red-500 @enderror"
                            required>
                            <option value="">Pilih Komoditas</option>
                            @foreach ($komoditasList as $item)
                                <option value="{{ $item->id }}"
                                    {{ old('komoditas_id') == $item->id ? 'selected' : '' }}>
                                    {{ $item->nama_komoditas }}
                                </option>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-5 text-slate-400">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7">
                                </path>
                            </svg>
                        </div>
                    </div>
                    @error('komoditas_id')
                        <p class="text-red-500 text-xs mt-2 font-bold">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-black text-slate-400 uppercase tracking-[0.2em] mb-3">Status
                        Awal</label>
                    <div class="relative">
                        <select name="status"
                            class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-2xl px-5 py-4 text-lg font-bold focus:ring-4 focus:ring-green-500/10 focus:border-green-500 outline-none appearance-none cursor-pointer transition-all">
                            <option value="Aktif">🟢 Aktif / Produktif</option>
                            <option value="Persiapan">🔵 Persiapan Tanam</option>
                            <option value="Istirahat">🟡 Istirahat Lahan</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-5 text-slate-400">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7">
                                </path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Deskripsi --}}
            <div>
                <label class="block text-xs font-black text-slate-400 uppercase tracking-[0.2em] mb-3">Deskripsi
                    (Opsional)</label>
                <textarea name="deskripsi" rows="3"
                    class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-2xl px-5 py-4 text-lg font-medium italic focus:ring-4 focus:ring-green-500/10 focus:border-green-500 outline-none transition-all placeholder:text-slate-300"
                    placeholder="Tambahkan detail lahan...">{{ old('deskripsi') }}</textarea>
            </div>

            <input type="hidden" name="polygon_coordinates" value='[{"lat": -6.9744, "lng": 107.6311}]'>

            <div class="flex flex-col sm:flex-row gap-4 pt-4">
                <a href="{{ route('lahan.index') }}"
                    class="flex-1 text-center bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-400 font-black py-5 rounded-2xl transition-all uppercase tracking-widest text-xs">
                    Batal
                </a>
                <button type="submit"
                    class="flex-1 bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white font-black py-5 rounded-2xl shadow-xl shadow-green-600/20 active:scale-[0.98] transition-all uppercase tracking-widest text-xs">
                    Simpan Lahan
                </button>
            </div>
        </form>
    </div>
@endsection
