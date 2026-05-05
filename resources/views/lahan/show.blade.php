
@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto space-y-8">
    <div class="bg-white dark:bg-slate-900 rounded-3xl shadow-2xl p-8 border border-slate-100 dark:border-slate-800">
        <div class="flex items-center gap-4 mb-8">
            <a href="{{ route('lahan.index') }}" class="p-3 bg-slate-50 dark:bg-slate-800 rounded-2xl hover:bg-slate-100 dark:hover:bg-slate-700 transition-all">
                <svg class="w-6 h-6 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <div>
                <h1 class="text-3xl font-black text-slate-800 dark:text-white">{{ $lahan->nama_lahan }}</h1>
                <p class="text-slate-500">{{ $lahan->lokasi }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <div>
                <h3 class="text-xl font-bold text-slate-800 dark:text-white mb-6">Informasi Lahan</h3>
                <div class="space-y-4">
                    <div>
                        <span class="text-sm font-bold text-slate-500">Luas</span>
                        <p class="text-2xl font-black text-slate-800 dark:text-white">{{ $lahan->luas }} Ha</p>
                    </div>
                    <div>
                        <span class="text-sm font-bold text-slate-500">Kesesuaian</span>
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                            <span class="text-2xl font-black text-green-600 dark:text-green-400">{{ $lahan->kesesuaian_score }}%</span>
                        </div>
                    </div>
                    <div>
                        <span class="text-sm font-bold text-slate-500">Komoditas Utama</span>
                        <p class="text-xl font-bold text-slate-800 dark:text-white">{{ $lahan->komoditas_utama }}</p>
                    </div>
                    <p class="text-slate-600 dark:text-slate-400 mt-4">{{ $lahan->deskripsi }}</p>
                </div>
            </div>

            <div>
                <h3 class="text-xl font-bold text-slate-800 dark:text-white mb-6">Komoditas Saat Ini</h3>
                <div class="space-y-4">
                    @forelse($lahan->komoditas as $komoditas)
                        <div class="flex items-center justify-between p-4 bg-gradient-to-r from-green-50 to-green-100 dark:from-green-900/30 dark:to-green-800/30 rounded-2xl">
                            <div>
                                <p class="font-bold text-slate-800 dark:text-white">{{ $komoditas['nama'] }}</p>
                                <p class="text-sm text-slate-500">Fase: {{ $komoditas['fase'] }} (Hari {{ $komoditas['hari'] ?? 0 }})</p>
                            </div>
                            <span class="text-2xl">🌾</span>
                        </div>
                    @empty
                        <p class="text-center py-12 text-slate-500 dark:text-slate-400 rounded-2xl bg-slate-50 dark:bg-slate-800">Belum ada komoditas</p>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="pt-8 border-t border-slate-200 dark:border-slate-700">
            <a href="{{ route('lahan.edit', $lahan) }}" class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-2xl transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Edit Lahan
            </a>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-900 rounded-3xl shadow-2xl p-8 border border-slate-100 dark:border-slate-800">
        <h3 class="text-xl font-bold text-slate-800 dark:text-white mb-6">Riwayat Logbook</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($lahan->logbookEntries()->latest()->take(6)->get() as $entry)
                <div class="p-6 bg-slate-50 dark:bg-slate-800 rounded-2xl border-r-4 {{ $entry->tipe == 'hama' ? 'border-red-500' : 'border-green-500' }}">
                    <div class="flex items-start gap-3 mb-3">
                        <span class="text-2xl">{{ $entry->tipe_icon }}</span>
                        <div>
                            <p class="font-bold text-slate-800 dark:text-white text-sm uppercase tracking-wide">{{ ucfirst($entry->tipe) }}</p>
                            <p class="text-xs text-slate-500">{{ $entry->tanggal->format('d M Y') }}</p>
                        </div>
                    </div>
                    <p class="text-sm text-slate-700 dark:text-slate-300">{{ Str::limit($entry->detail, 100) }}</p>
                    @if($entry->foto)
                        <img src="{{ asset('storage/' . $entry->foto) }}" alt="Foto" class="mt-3 w-full h-32 object-cover rounded-lg">
                    @endif
                </div>
            @empty
                <div class="col-span-full text-center py-16">
                    <svg class="w-16 h-16 text-slate-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-slate-500 dark:text-slate-400 text-lg">Belum ada logbook</p>
                    <a href="{{ route('logbook.create') }}" class="mt-4 inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded-2xl">
                        Tambah Logbook Pertama
                    </a>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection

