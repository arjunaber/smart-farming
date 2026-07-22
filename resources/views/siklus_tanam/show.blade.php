@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto space-y-8">
    <div class="flex items-center justify-between">
        <div>
            <a href="{{ route('siklus-tanam.index') }}" class="text-sm font-bold text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition-colors">&larr; Kembali</a>
            <h1 class="text-2xl font-black text-slate-900 dark:text-white mt-1">Detail Siklus Tanam</h1>
        </div>
        <span class="px-4 py-1.5 rounded-full text-xs font-bold {{ $siklusTanam->status == 'aktif' ? 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300' : 'bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400' }}">
            {{ ucfirst($siklusTanam->status) }}
        </span>
    </div>

    <div class="bg-white dark:bg-slate-900 rounded-[2rem] border border-slate-100 dark:border-slate-800 p-8 shadow-sm">
        <dl class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <dt class="text-xs font-bold text-slate-400 uppercase tracking-wider">Lahan</dt>
                <dd class="mt-1 text-sm font-bold text-slate-800 dark:text-white">{{ $siklusTanam->lahan->nama_lahan ?? '-' }}</dd>
            </div>
            <div>
                <dt class="text-xs font-bold text-slate-400 uppercase tracking-wider">Komoditas</dt>
                <dd class="mt-1 text-sm font-bold text-slate-800 dark:text-white">{{ $siklusTanam->komoditas->nama_komoditas ?? '-' }}</dd>
            </div>
            <div>
                <dt class="text-xs font-bold text-slate-400 uppercase tracking-wider">Tanggal Mulai</dt>
                <dd class="mt-1 text-sm font-bold text-slate-800 dark:text-white">{{ $siklusTanam->tanggal_mulai->format('d M Y') }}</dd>
            </div>
            <div>
                <dt class="text-xs font-bold text-slate-400 uppercase tracking-wider">Estimasi Panen</dt>
                <dd class="mt-1 text-sm font-bold text-slate-800 dark:text-white">{{ $siklusTanam->estimasi_panen ? $siklusTanam->estimasi_panen->format('d M Y') : '-' }}</dd>
            </div>
        </dl>
    </div>

    <div class="bg-white dark:bg-slate-900 rounded-[2rem] border border-slate-100 dark:border-slate-800 p-8 shadow-sm">
        <h3 class="text-lg font-black text-slate-900 dark:text-white mb-6">Logbook Aktivitas</h3>

        @if($siklusTanam->logbookEntries->count())
            <div class="space-y-4">
                @foreach($siklusTanam->logbookEntries as $entry)
                    <div class="flex items-start gap-4 p-4 rounded-2xl bg-slate-50 dark:bg-slate-800/50">
                        <div class="w-8 h-8 rounded-xl bg-green-100 dark:bg-green-900/30 flex items-center justify-center text-green-600 dark:text-green-400 flex-shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="font-bold text-sm text-slate-800 dark:text-white">{{ $entry->activity_type }}</span>
                                <span class="text-xs text-slate-400">{{ $entry->created_at->format('d M Y H:i') }}</span>
                            </div>
                            @if($entry->description)
                                <p class="text-sm text-slate-500 dark:text-slate-400">{{ $entry->description }}</p>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8">
                <p class="text-slate-400 text-sm">Belum ada aktivitas tercatat.</p>
            </div>
        @endif
    </div>
</div>
@endsection
