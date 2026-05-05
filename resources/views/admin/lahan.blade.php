
@extends('layouts.app')

@section('content')
<div class="space-y-8">
    <div class="flex justify-between items-center">
        <h1 class="text-3xl font-black text-slate-800 dark:text-white">Kelola Lahan</h1>
        <a href="{{ route('lahan.create') }}" class="px-8 py-3 bg-green-600 hover:bg-green-700 text-white font-bold rounded-2xl transition-all">
            Tambah Lahan
        </a>
    </div>

    <div class="bg-white dark:bg-slate-900 rounded-3xl p-8 shadow-xl border border-slate-100 dark:border-slate-800">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-slate-200 dark:border-slate-700">
                        <th class="text-left py-4 font-bold text-slate-800 dark:text-white">Lahan</th>
                        <th class="text-left py-4 font-bold text-slate-800 dark:text-white">Petani</th>
                        <th class="text-left py-4 font-bold text-slate-800 dark:text-white">Luas</th>
                        <th class="text-left py-4 font-bold text-slate-800 dark:text-white">Komoditas</th>
                        <th class="text-left py-4 font-bold text-slate-800 dark:text-white">Kesesuaian</th>
                        <th class="text-right py-4 font-bold text-slate-800 dark:text-white">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($lahan as $item)
                        <tr class="border-b border-slate-100 dark:border-slate-800 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                            <td class="py-4">
                                <div>
                                    <p class="font-bold text-slate-800 dark:text-white">{{ $item->nama_lahan }}</p>
                                    <p class="text-sm text-slate-500">{{ $item->lokasi }}</p>
                                </div>
                            </td>
                            <td class="py-4">
                                <p class="font-bold text-slate-800 dark:text-white">{{ $item->user->name }}</p>
                                <p class="text-sm text-slate-500">{{ $item->user->email }}</p>
                            </td>
                            <td class="py-4">
                                <span class="px-3 py-1 bg-green-100 text-green-800 text-sm font-bold rounded-full">{{ $item->luas }} Ha</span>
                            </td>
                            <td class="py-4">
                                <span class="px-3 py-1 bg-blue-100 text-blue-800 text-sm font-bold rounded-full">{{ $item->komoditas_utama }}</span>
                            </td>
                            <td class="py-4">
                                <div class="flex items-center gap-2">
                                    <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                                    <span class="font-bold text-green-600 dark:text-green-400">{{ $item->kesesuaian_score }}%</span>
                                </div>
                            </td>
                            <td class="py-4 text-right">
                                <div class="flex gap-2 justify-end">
                                    <a href="{{ route('lahan.show', $item) }}" class="p-2 text-slate-400 hover:text-slate-600">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </a>
                                    <a href="{{ route('lahan.edit', $item) }}" class="p-2 text-blue-500 hover:text-blue-600">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-12">
                                <p class="text-slate-500 dark:text-slate-400">Belum ada lahan</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

