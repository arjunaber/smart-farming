@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-end">
        <div>
            <h1 class="text-2xl font-black text-slate-800 dark:text-white">Manajemen Lahan</h1>
            <p class="text-slate-500 dark:text-slate-400">Kelola lahan sawah dan biofarmaka Anda</p>
        </div>
        <a href="{{ route('lahan.create') }}" class="bg-green-600 text-white px-6 py-2.5 rounded-xl font-bold hover:bg-green-700 transition-all shadow-lg">
            + Tambah Lahan Baru
        </a>
    </div>

    @if (session('success'))
        <div class="bg-green-100 border border-green-200 text-green-800 px-6 py-3 rounded-2xl">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white dark:bg-slate-900 rounded-[2rem] border border-slate-100 dark:border-slate-800 p-8 shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-slate-200 dark:border-slate-700">
                        <th class="text-left py-4 font-bold text-slate-700 dark:text-slate-300">Nama Lahan</th>
                        <th class="text-left py-4 font-bold text-slate-700 dark:text-slate-300">Luas (Ha)</th>
                        <th class="text-left py-4 font-bold text-slate-700 dark:text-slate-300">Lokasi</th>
                        <th class="text-left py-4 font-bold text-slate-700 dark:text-slate-300">Komoditas</th>
                        <th class="text-left py-4 font-bold text-slate-700 dark:text-slate-300">Kesesuaian</th>
                        <th class="text-right py-4 font-bold text-slate-700 dark:text-slate-300">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($lahan as $item)
                        <tr class="border-b border-slate-100 dark:border-slate-800 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                            <td class="py-4 font-bold text-slate-800 dark:text-white">{{ $item->nama_lahan }}</td>
                            <td class="py-4 text-slate-600 dark:text-slate-400">{{ number_format($item->luas, 2) }}</td>
                            <td class="py-4 text-slate-600 dark:text-slate-400">{{ $item->lokasi }}</td>
                            <td class="py-4">
                                <span class="font-bold text-green-600">{{ $item->komoditas_utama }}</span>
                            </td>
                            <td class="py-4">
                                <span class="px-3 py-1 rounded-full text-xs font-bold {{ $item->kesesuaian_score >= 80 ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ $item->kesesuaian_score }}%
                                </span>
                            </td>
                            <td class="py-4">
                                <div class="flex gap-2">
                                    <a href="{{ route('lahan.show', $item) }}" class="text-blue-600 hover:text-blue-700 text-sm font-bold">Lihat</a>
                                    <a href="{{ route('lahan.edit', $item) }}" class="text-orange-600 hover:text-orange-700 text-sm font-bold">Edit</a>
                                    <form action="{{ route('lahan.destroy', $item) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-700 text-sm font-bold" onclick="return confirm('Hapus lahan?')">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-slate-500 dark:text-slate-400">
                                <svg class="w-16 h-16 mx-auto mb-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                </svg>
                                <h3 class="text-lg font-bold mb-2">Belum ada lahan</h3>
                                <p>Tambahkan lahan pertama Anda untuk mulai monitoring.</p>
                                <a href="{{ route('lahan.create') }}" class="mt-4 inline-block bg-green-600 text-white px-6 py-2.5 rounded-xl font-bold hover:bg-green-700">
                                    + Tambah Lahan
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

