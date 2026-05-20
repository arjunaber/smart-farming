@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-4">
            <div>
                <h1 class="text-2xl font-black text-slate-800 dark:text-white">Logbook Siklus Tanam</h1>
                <p class="text-slate-500 dark:text-slate-400">Riwayat aktivitas lahan Anda</p>
            </div>
            <div class="flex gap-3">
                {{-- Dropdown Filter Lahan --}}
                <form action="{{ route('logbook.index') }}" method="GET" id="filterForm">
                    <select name="lahan_id" onchange="document.getElementById('filterForm').submit()"
                        class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-700 dark:text-slate-300 px-4 py-2.5 rounded-xl font-bold focus:ring-2 focus:ring-blue-500 outline-none">
                        <option value="">Semua Lahan</option>
                        @foreach ($lahan as $l)
                            <option value="{{ $l->id }}" {{ request('lahan_id') == $l->id ? 'selected' : '' }}>
                                {{ $l->nama_lahan }}
                            </option>
                        @endforeach
                    </select>
                </form>

                <a href="{{ route('logbook.create') }}"
                    class="bg-blue-600 text-white px-6 py-2.5 rounded-xl font-bold hover:bg-blue-700 transition-all shadow-lg">
                    + Catat
                </a>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-900 rounded-[2rem] border border-slate-100 dark:border-slate-800 p-8 shadow-sm">
            <div class="divide-y divide-slate-200 dark:divide-slate-700">
                @forelse($entries as $entry)
                    <div class="py-6 flex gap-4 items-start">
                        {{-- Icon Logic --}}
                        <div
                            class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-green-500 to-green-600 rounded-2xl flex items-center justify-center text-white text-xl shadow-lg">
                            @if (str_contains(strtolower($entry->jenis_kegiatan), 'pupuk'))
                                🧪
                            @elseif(str_contains(strtolower($entry->jenis_kegiatan), 'air'))
                                💧
                            @elseif(str_contains(strtolower($entry->jenis_kegiatan), 'panen'))
                                🌾
                            @else
                                📝
                            @endif
                        </div>

                        <div class="flex-1">
                            <div class="flex flex-wrap items-center gap-2 mb-1">
                                <span class="text-xs font-black text-green-600 uppercase tracking-wider">
                                    {{ $entry->jenis_kegiatan }}
                                </span>
                                <span class="text-xs font-bold text-slate-400">|</span>
                                <span class="text-xs font-bold text-slate-500">
                                    {{ \Carbon\Carbon::parse($entry->activity_date)->format('d M Y') }}
                                </span>
                                <span
                                    class="px-2 py-0.5 text-[10px] font-black bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 rounded-md uppercase">
                                    {{ $entry->siklusTanam->lahan->nama_lahan }}
                                </span>
                            </div>

                            <h4 class="font-bold text-slate-800 dark:text-white">{{ $entry->title }}</h4>
                            <p class="text-slate-600 dark:text-slate-400 text-sm leading-relaxed mt-1">
                                {{ $entry->description }}</p>

                            @if ($entry->kuantitas)
                                <div
                                    class="mt-2 inline-flex items-center px-3 py-1 rounded-lg bg-blue-50 dark:bg-blue-500/10 text-blue-700 dark:text-blue-400 text-xs font-bold">
                                    {{ $entry->kuantitas }} {{ $entry->satuan }}
                                </div>
                            @endif
                        </div>

                        {{-- Action --}}
                        <form action="{{ route('logbook.destroy', $entry) }}" method="POST" class="flex-shrink-0">
                            @csrf @method('DELETE')
                            <button type="submit" class="p-2 text-slate-300 hover:text-red-500 transition-colors"
                                onclick="return confirm('Hapus?')">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                    </path>
                                </svg>
                            </button>
                        </form>
                    </div>
                @empty
                    {{-- Empty State --}}
                    <div class="text-center py-20">
                        <h3 class="text-lg font-bold text-slate-700 dark:text-slate-200">Tidak ada aktivitas ditemukan</h3>
                        <p class="text-slate-500 dark:text-slate-400 text-sm">Coba pilih lahan lain atau buat catatan baru.
                        </p>
                    </div>
                @endforelse
            </div>

            {{-- PAGINATION --}}
            <div class="mt-8">
                {{ $entries->links() }}
            </div>
        </div>
    </div>
@endsection
