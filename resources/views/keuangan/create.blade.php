@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white dark:bg-slate-900 rounded-3xl shadow-2xl p-8 border border-slate-100 dark:border-slate-800">
        <div class="flex items-center gap-4 mb-8">
            <a href="{{ route('keuangan.index') ?? '/keuangan' }}" class="p-2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 bg-slate-50 dark:bg-slate-800 rounded-xl transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-black text-slate-800 dark:text-white">Catat Pengeluaran</h1>
                <p class="text-sm text-slate-500">Masukkan detail pengeluaran lahan</p>
            </div>
        </div>

        <form method="POST" action="{{ route('keuangan.store') ?? '#' }}">
            @csrf
            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-bold text-slate-500 mb-2">Pilih Lahan <span class="text-red-500">*</span></label>
                    <select name="lahan_id" class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl px-4 py-3 focus:ring-2 focus:ring-green-500 outline-none text-slate-700 dark:text-slate-200 cursor-pointer" required>
                        <option value="" disabled selected>-- Pilih Lahan Sawah --</option>
                        <option value="1">Blok A-1 (Ciherang)</option>
                        <option value="2">Blok B-2 (Inpari)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-500 mb-2">Kategori <span class="text-red-500">*</span></label>
                    <select name="kategori" class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl px-4 py-3 focus:ring-2 focus:ring-green-500 outline-none text-slate-700 dark:text-slate-200 cursor-pointer" required>
                        <option value="Pupuk">Pupuk</option>
                        <option value="Obat Hama">Obat Hama / Pestisida</option>
                        <option value="Bibit">Bibit</option>
                        <option value="Tenaga Kerja">Tenaga Kerja / Tukang</option>
                        <option value="Lainnya">Operasional Lainnya</option>
                    </select>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-slate-500 mb-2">Nominal (Rp) <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <span class="absolute left-4 top-3.5 text-slate-400 font-bold">Rp</span>
                            <input type="number" name="nominal" class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl pl-12 pr-4 py-3 focus:ring-2 focus:ring-green-500 outline-none text-slate-700 dark:text-slate-200" placeholder="500000" required>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-500 mb-2">Tanggal</label>
                        <input type="date" name="tanggal" value="{{ old('tanggal', now()->format('Y-m-d')) }}" class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl px-4 py-3 focus:ring-2 focus:ring-green-500 outline-none text-slate-700 dark:text-slate-200" required>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-500 mb-2">Detail Pembelian</label>
                    <textarea name="keterangan" rows="3" placeholder="Contoh: Pembelian Urea 2 Karung di Toko Tani Jaya" class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl px-4 py-3 focus:ring-2 focus:ring-green-500 outline-none text-slate-700 dark:text-slate-200 resize-none"></textarea>
                </div>
            </div>

            <div class="mt-8 flex justify-end gap-3">
                <a href="{{ route('keuangan.index') ?? '/keuangan' }}" class="px-6 py-3 rounded-xl font-bold text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">Batal</a>
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-8 py-3 rounded-xl font-bold transition-all shadow-md flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Simpan Data
                </button>
            </div>
        </form>
    </div>
</div>
@endsection