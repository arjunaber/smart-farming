@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        {{-- Header --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
            <div>
                <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight">Portofolio Lahan</h1>
                <p class="text-slate-500 dark:text-slate-400 mt-1">Menampilkan {{ $lahan->count() }} lokasi di halaman ini.
                </p>
            </div>
            <a href="{{ route('lahan.create') }}"
                class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-bold rounded-2xl text-white bg-green-600 hover:bg-green-700 shadow-xl shadow-green-500/20 transition-all active:scale-95">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Inisialisasi Lahan
            </a>
        </div>

        {{-- Stats Overview --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
            <div
                class="bg-white dark:bg-slate-900 p-6 rounded-[2rem] border border-slate-100 dark:border-slate-800 shadow-sm">
                <p class="text-sm font-bold text-slate-400 uppercase tracking-widest">Total Area</p>
                <p class="text-2xl font-black text-slate-800 dark:text-white">{{ $lahan->sum('luas') }}
                    <span class="text-sm font-medium text-slate-500">Hektar</span>
                </p>
            </div>
            <div
                class="bg-white dark:bg-slate-900 p-6 rounded-[2rem] border border-slate-100 dark:border-slate-800 shadow-sm">
                <p class="text-sm font-bold text-slate-400 uppercase tracking-widest">Lahan Aktif</p>
                <p class="text-2xl font-black text-green-600">
                    {{ $lahan->where('status', 'Aktif')->count() }}
                    <span class="text-sm font-medium text-slate-500">Lokasi</span>
                </p>
            </div>
        </div>

        {{-- Lahan Grid --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-8">
            @forelse($lahan as $item)
                <div
                    class="group bg-white dark:bg-slate-900 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 shadow-sm hover:shadow-2xl hover:shadow-slate-200/50 dark:hover:shadow-none transition-all overflow-hidden relative">

                    <div class="p-8">
                        <div class="flex justify-between items-start mb-4">
                            <div class="bg-green-100 dark:bg-green-500/10 p-3 rounded-2xl">
                                {{-- Mengambil emoji dari kolom komoditas (JSON) --}}
                                <span class="text-2xl">{{ $item->komoditas[0]['emoji'] ?? '🌿' }}</span>
                            </div>
                            <div class="text-right">
                                <span class="text-xs font-black text-slate-400 uppercase tracking-tighter">Status
                                    Lahan</span>
                                <div class="mt-1">
                                    @if ($item->status == 'Aktif')
                                        <span
                                            class="px-3 py-1 bg-green-100 text-green-700 text-xs font-black rounded-full uppercase tracking-widest border border-green-200">Aktif</span>
                                    @elseif($item->status == 'Persiapan')
                                        <span
                                            class="px-3 py-1 bg-amber-100 text-amber-700 text-xs font-black rounded-full uppercase tracking-widest border border-amber-200">Persiapan</span>
                                    @else
                                        <span
                                            class="px-3 py-1 bg-slate-100 text-slate-600 text-xs font-black rounded-full uppercase tracking-widest border border-slate-200">{{ $item->status ?? 'N/A' }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <h3
                            class="text-xl font-black text-slate-800 dark:text-white mb-1 group-hover:text-green-600 transition-colors">
                            {{ $item->nama_lahan }}
                        </h3>
                        <p class="text-sm text-slate-500 mb-6 flex items-center italic">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            {{ $item->lokasi }}
                        </p>

                        <div class="space-y-3 mb-8">
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-slate-400">Komoditas Utama:</span>
                                <span class="font-bold text-slate-700 dark:text-slate-200">
                                    {{-- Mengambil nama dari array JSON komoditas --}}
                                    {{ $item->komoditas[0]['nama'] ?? 'Tidak ada data' }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-slate-400">Luas Area:</span>
                                <span class="font-bold text-slate-700 dark:text-slate-200">{{ $item->luas }} Ha</span>
                            </div>
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-slate-400">Aktivitas Logbook:</span>
                                <span class="font-bold text-green-600">{{ $item->logbook_entries_count ?? 0 }} Entri</span>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <a href="{{ route('lahan.show', $item) }}"
                                class="flex items-center justify-center py-3 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 rounded-xl font-bold hover:bg-slate-200 transition-all text-sm">
                                Detail
                            </a>
                            <button onclick="confirmDelete('{{ $item->id }}')"
                                class="flex items-center justify-center py-3 bg-red-50 text-red-600 rounded-xl font-bold hover:bg-red-100 transition-all text-sm">
                                Hapus
                            </button>
                            <form id="delete-form-{{ $item->id }}" action="{{ route('lahan.destroy', $item) }}"
                                method="POST" class="hidden">
                                @csrf @method('DELETE')
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div
                    class="col-span-full py-20 text-center bg-white dark:bg-slate-900 rounded-[3rem] border-2 border-dashed border-slate-200 dark:border-slate-800">
                    <div class="text-6xl mb-4">🚜</div>
                    <h3 class="text-2xl font-black text-slate-800 dark:text-white">Belum Ada Data Lahan</h3>
                    <p class="text-slate-500 max-w-sm mx-auto mt-2">Mulai pantau produktivitas pertanian Anda dengan
                        menambahkan area lahan pertama.</p>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        <div class="mt-10">
            {{ $lahan->links() }}
        </div>
    </div>

    <script>
        function confirmDelete(id) {
            if (confirm('Apakah Anda yakin ingin menghapus lahan ini? Semua data logbook terkait akan hilang.')) {
                document.getElementById('delete-form-' + id).submit();
            }
        }
    </script>
@endsection
