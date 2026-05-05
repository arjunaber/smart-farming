
@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white dark:bg-slate-900 rounded-3xl shadow-2xl p-8 border border-slate-100 dark:border-slate-800">
        <div class="flex items-center gap-4 mb-8">
            <a href="{{ route('logbook.index') }}" class="p-2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-300">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <h1 class="text-2xl font-black text-slate-800 dark:text-white">Tambah Logbook</h1>
        </div>

        <form method="POST" action="{{ route('logbook.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-bold text-slate-500 mb-2">Tipe Aktivitas</label>
                    <select name="tipe" class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl px-4 py-3 focus:ring-2 focus:ring-green-500 outline-none" required>
                        <option value="tanam">🌱 Penanaman</option>
                        <option value="pupuk">🧪 Pemupukan</option>
                        <option value="irigasi">💧 Irigasi</option>
                        <option value="panen">🌾 Panen</option>
                        <option value="hama">🐛 Hama/Penyakit</option>
                        <option value="lainnya">📝 Lainnya</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-500 mb-2">Tanggal</label>
                    <input type="date" name="tanggal" value="{{ old('tanggal', now()->format('Y-m-d')) }}" class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl px-4 py-3" required>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-500 mb-2">Detail Aktivitas</label>
                    <textarea name="detail" rows="4" placeholder="Contoh: Pemupukan urea 50kg/ha, kondisi tanah lembab..." class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl px-4 py-3 focus:ring-2 focus:ring-green-500 outline-none" required>{{ old('detail') }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-500 mb-2">Foto (Opsional)</label>
                    <input type="file" name="foto" accept="image/*" class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl px-4 py-3 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-green-50 file:text-green-700 hover:file:bg-green-100">
                </div>

                <button type="submit" class="w-full bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-bold py-4 px-8 rounded-2xl shadow-lg shadow-green-200 transition-all">
                    Simpan Logbook
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

