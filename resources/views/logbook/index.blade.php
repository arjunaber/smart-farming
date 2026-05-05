@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-end">
        <div>
            <h1 class="text-2xl font-black text-slate-800 dark:text-white">Logbook Siklus Tanam</h1>
            <p class="text-slate-500 dark:text-slate-400">Riwayat aktivitas lahan Anda</p>
        </div>
        <a href="{{ route('logbook.create') }}" class="bg-blue-600 text-white px-6 py-2.5 rounded-xl font-bold hover:bg-blue-700 transition-all shadow-lg">
            + Catat Aktivitas
        </a>
    </div>

    @if (session('success'))
        <div class="bg-green-100 border border-green-200 text-green-800 px-6 py-3 rounded-2xl">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white dark:bg-slate-900 rounded-[2rem] border border-slate-100 dark:border-slate-800 p-8 shadow-sm">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-1 gap-6 mb-6">
            @foreach($lahan as $l)
                <div class="p-6 border border-slate-200 dark:border-slate-700 rounded-2xl bg-slate-50 dark:bg-slate-800/50">
                    <h3 class="font-bold text-lg mb-2">{{ $l->nama_lahan }}</h3>
                    <div class="space-y-2 text-sm">
                        <span class="text-slate-600 dark:text-slate-400">Luas: {{ $l->luas }} Ha</span>
                        <span class="text-slate-600 dark:text-slate-400">Komoditas: {{ $l->komoditas_utama }}</span>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="divide-y divide-slate-200 dark:divide-slate-700">
            @forelse($entries as $entry)
                <div class="py-6 flex gap-4 items-start">
                    <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-2xl flex items-center justify-center text-white font-bold text-lg mt-1">
                        {{ $entry->tipe_icon }}
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="text-xs font-bold text-slate-500 uppercase tracking-wide">{{ ucfirst($entry->tipe) }}</span>
                            <span class="px-2 py-0.5 text-xs font-bold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 rounded-full">
                                {{ $entry->tanggal->format('d M Y') }}
                            </span>
                        </div>
                        <p class="text-slate-800 dark:text-white leading-relaxed">{{ $entry->detail }}</p>
                        @if($entry->foto)
                            <img src="{{ asset('storage/' . $entry->foto) }}" alt="Foto" class="mt-2 w-24 h-24 object-cover rounded-xl">
                        @endif
                    </div>
                    <form action="{{ route('logbook.destroy', $entry) }}" method="POST" class="flex-shrink-0">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-500 hover:text-red-700 font-bold text-sm" onclick="return confirm('Hapus catatan ini?')">
                            Hapus
                        </button>
                    </form>
                </div>
            @empty
                <div class="text-center py-12">
                    <svg class="w-16 h-16 mx-auto mb-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <h3 class="text-lg font-bold text-slate-700 dark:text-slate-200 mb-1">Belum ada catatan</h3>
                    <p class="text-slate-500 dark:text-slate-400">Mulai catat aktivitas lahan pertama Anda.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection

