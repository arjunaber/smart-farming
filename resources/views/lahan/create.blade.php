@extends('layouts.app')

@section('content')
<div class="space-y-8">
    <div>
        <h1 class="text-3xl font-black text-slate-800 dark:text-white mb-2">Tambah Lahan Baru</h1>
        <p class="text-slate-500">Kelola lahan sawah atau biofarmaka Anda</p>
    </div>

<div class="max-w-2xl mx-auto">
    <form method="POST" action="{{ route('lahan.store') }}" class="bg-white dark:bg-slate-900 rounded-3xl p-8 shadow-2xl border border-slate-100 dark:border-slate-800 space-y-6">
        @csrf
        <div>
            <label class="block text-sm font-bold text-slate-500 uppercase tracking-wide mb-2">Nama Lahan</label>
            <input type="text" name="nama_lahan" value="{{ old('nama_lahan') }}" class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl px-4 py-3 text-lg focus:ring-2 focus:ring-green-500 focus:border-transparent" placeholder="Lahan A - Blok Padi" required>
            @error('nama_lahan') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-bold text-slate-500 uppercase tracking-wide mb-2">Luas (hektar)</label>
                <input type="number" name="luas" step="0.01" value="{{ old('luas', 0.25) }}" class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl px-4 py-3 text-lg focus:ring-2 focus:ring-green-500 focus:border-transparent" required>
                @error('luas') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-500 uppercase tracking-wide mb-2">Lokasi</label>
                <input type="text" name="lokasi" value="{{ old('lokasi') }}" class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl px-4 py-3 text-lg focus:ring-2 focus:ring-green-500 focus:border-transparent" placeholder="Soreang, Kab. Bandung" required>
                @error('lokasi') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div>
            <label class="block text-sm font-bold text-slate-500 uppercase tracking-wide mb-3">Komoditas Utama</label>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <select name="komoditas_utama" class="bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl px-4 py-3 text-lg focus:ring-2 focus:ring-green-500" required>
                    <option value="">Pilih Komoditas</option>
                    @foreach($komoditasList as $id => $nama)
                        <option value="{{ $nama }}" {{ old('komoditas_utama') == $nama ? 'selected' : '' }}>{{ $nama }}</option>
                    @endforeach
                </select>
                <input type="number" name="kesesuaian_score" value="{{ old('kesesuaian_score', 85) }}" min="0" max="100" class="bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl px-4 py-3 text-lg focus:ring-2 focus:ring-green-500" placeholder="Kesesuaian %">
            </div>
            @error('komoditas_utama') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-bold text-slate-500 uppercase tracking-wide mb-2">Deskripsi (Opsional)</label>
            <textarea name="deskripsi" rows="3" class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl px-4 py-3 text-lg focus:ring-2 focus:ring-green-500">{{ old('deskripsi') }}</textarea>
        </div>

        <div class="flex gap-4">
            <a href="{{ route('lahan.index') }}" class="flex-1 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-bold py-3.5 rounded-2xl transition-all text-center">Batal</a>
            <button type="submit" class="flex-1 bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white font-black py-3.5 rounded-2xl shadow-xl shadow-green-200 transition-all">Tambah Lahan</button>
        </div>
    </form>
</div>
@endsection

