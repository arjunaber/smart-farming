@extends('layouts.app')

@section('content')
    <div class="max-w-2xl mx-auto px-4 py-6">
        <div class="mb-8">
            <h1 class="text-3xl font-black text-slate-800 dark:text-white mb-2 uppercase tracking-tight">Mulai Siklus Tanam
                Baru</h1>
            <p class="text-sm font-bold text-slate-500 uppercase tracking-widest">Inisiasi Periode & Manajemen Komoditas
                Lahan</p>
        </div>

        <form method="POST" action="{{ route('siklus-tanam.store') }}"
            class="bg-white dark:bg-slate-900 rounded-[2.5rem] p-6 md:p-10 shadow-2xl border border-slate-100 dark:border-slate-800 space-y-8">
            @csrf

            {{-- Pilih Lahan --}}
            <div>
                <label class="block text-xs font-black text-slate-400 uppercase tracking-[0.2em] mb-3">Lahan
                    Pertanian</label>
                <div class="relative">
                    <select name="lahan_id" id="lahanSelect"
                        class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-2xl px-5 py-4 text-lg font-bold focus:ring-4 focus:ring-green-500/10 focus:border-green-500 outline-none appearance-none cursor-pointer transition-all"
                        required>
                        <option value="">Pilih Lahan Tujuan</option>
                        @foreach ($lahanList ?? [] as $lahan)
                            <option value="{{ $lahan->id }}" data-komoditas="{{ $lahan->komoditas_id }}"
                                {{ old('lahan_id', request('lahan')) == $lahan->id ? 'selected' : '' }}>
                                {{ $lahan->nama_lahan }}
                            </option>
                        @endforeach
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-5 text-slate-400">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Grid Komoditas & Tanggal Tanam --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-black text-slate-400 uppercase tracking-[0.2em] mb-3">Komoditas
                        (Otomatis)</label>
                    <div class="relative">
                        <select id="komoditasSelect"
                            class="w-full bg-slate-100 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700 rounded-2xl px-5 py-4 text-lg font-bold outline-none appearance-none pointer-events-none transition-all opacity-70"
                            disabled>
                            <option value="">Pilih Varietas</option>
                            @foreach ($komoditasList ?? [] as $komoditas)
                                <option value="{{ $komoditas->id }}">
                                    {{ $komoditas->nama_komoditas }}
                                </option>
                            @endforeach
                        </select>
                        <input type="hidden" name="komoditas_id" id="hiddenKomoditasId" value="{{ old('komoditas_id') }}">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-black text-slate-400 uppercase tracking-[0.2em] mb-3">Tanggal Mulai
                        Tanam</label>
                    <input type="date" name="tanggal_mulai" value="{{ old('tanggal_mulai', date('Y-m-d')) }}"
                        class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-2xl px-5 py-4 text-lg font-bold focus:ring-4 focus:ring-green-500/10 focus:border-green-500 outline-none transition-all"
                        required>
                </div>
            </div>

            {{-- Estimasi Panen --}}
            <div>
                <label class="block text-xs font-black text-slate-400 uppercase tracking-[0.2em] mb-3">Estimasi Tanggal
                    Panen (Opsional)</label>
                <input type="date" name="estimasi_panen" value="{{ old('estimasi_panen') }}"
                    class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-2xl px-5 py-4 text-lg font-bold focus:ring-4 focus:ring-green-500/10 focus:border-green-500 outline-none transition-all">
            </div>

            <div class="flex flex-col sm:flex-row gap-4 pt-4">
                <a href="{{ route('siklus-tanam.index') }}"
                    class="flex-1 text-center bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 font-black py-5 rounded-2xl transition-all uppercase tracking-widest text-xs">
                    Batal
                </a>
                <button type="submit"
                    class="flex-1 bg-gradient-to-r from-green-600 to-green-700 text-white font-black py-5 rounded-2xl shadow-xl shadow-green-600/20 active:scale-[0.98] transition-all uppercase tracking-widest text-xs">
                    Mulai Siklus
                </button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // LOGIC AUTO-SELECT KOMODITAS
            const lahanSelect = document.getElementById('lahanSelect');
            const komoditasSelect = document.getElementById('komoditasSelect');
            const hiddenKomoditasId = document.getElementById('hiddenKomoditasId');

            function updateKomoditas() {
                const selectedOption = lahanSelect.options[lahanSelect.selectedIndex];
                const komoditasId = selectedOption ? selectedOption.getAttribute('data-komoditas') : "";

                if (komoditasId) {
                    komoditasSelect.value = komoditasId;
                    hiddenKomoditasId.value = komoditasId;
                    komoditasSelect.classList.replace('opacity-70', 'opacity-100');
                } else {
                    komoditasSelect.value = "";
                    hiddenKomoditasId.value = "";
                    komoditasSelect.classList.replace('opacity-100', 'opacity-70');
                }
            }

            lahanSelect.addEventListener('change', updateKomoditas);
            if (lahanSelect.value) updateKomoditas();
        });
    </script>
@endsection
