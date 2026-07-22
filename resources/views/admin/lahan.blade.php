
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
                        <th class="text-left py-4 font-bold text-slate-800 dark:text-white">Perangkat IoT</th>
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
                                <p class="font-bold text-slate-800 dark:text-white">{{ $item->petani->user->name ?? 'N/A' }}</p>
                                <p class="text-sm text-slate-500">{{ $item->petani->user->email ?? '' }}</p>
                            </td>
                            <td class="py-4">
                                <span class="px-3 py-1 bg-green-100 text-green-800 text-sm font-bold rounded-full">{{ $item->luas }} Ha</span>
                            </td>
                            <td class="py-4">
                                <span class="px-3 py-1 bg-blue-100 text-blue-800 text-sm font-bold rounded-full">{{ $item->komoditas->nama_komoditas ?? '-' }}</span>
                            </td>
                            <td class="py-4">
                                @php $deviceCount = $item->devices()->count(); @endphp
                                <span class="px-3 py-1 {{ $deviceCount > 0 ? 'bg-violet-100 text-violet-800' : 'bg-slate-100 text-slate-500' }} text-sm font-bold rounded-full">
                                    {{ $deviceCount }} device
                                </span>
                            </td>
                            <td class="py-4 text-right">
                                <div class="flex gap-2 justify-end">
                                    <a href="{{ route('lahan.show', $item) }}" class="p-2 text-slate-400 hover:text-slate-600" title="Detail">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </a>
                                    <a href="{{ route('lahan.edit', $item) }}" class="p-2 text-blue-500 hover:text-blue-600" title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>
                                    <button type="button" onclick="openIoTModal({{ $item->id }})" class="p-2 text-violet-500 hover:text-violet-600" title="Atur IoT">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3H5a2 2 0 00-2 2v4m6-6h10a2 2 0 012 2v4M9 3v18m0 0h10a2 2 0 002-2V9M9 21H5a2 2 0 01-2-2V9m0 0h18"></path>
                                        </svg>
                                    </button>
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

{{-- Modal IoT Assignment --}}
<div id="iotModal" class="hidden fixed inset-0 w-screen h-screen transition-all duration-300" style="z-index: 99999;">
    <div class="absolute inset-0 bg-slate-950/60 backdrop-blur-sm" onclick="closeIoTModal()"></div>
    <div class="relative flex items-center justify-center min-h-screen p-4 pointer-events-none">
        <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] w-full max-w-2xl shadow-2xl border border-slate-200 dark:border-slate-800 pointer-events-auto overflow-hidden">
            <div class="p-6 border-b border-slate-100 dark:border-slate-800">
                <h3 class="text-lg font-black text-slate-800 dark:text-white">Atur Perangkat IoT</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Pilih perangkat IoT yang tersedia untuk dipasang di lahan ini.</p>
            </div>
            <div class="p-6 max-h-96 overflow-y-auto">
                <div id="iotDeviceList" class="space-y-3">
                    <div class="text-center py-8 text-slate-400">Memuat data...</div>
                </div>
            </div>
            <div class="p-6 bg-slate-50 dark:bg-slate-800/30 flex justify-end gap-3 border-t border-slate-100 dark:border-slate-800">
                <button type="button" onclick="closeIoTModal()" class="px-5 py-3 rounded-xl bg-slate-200 dark:bg-slate-800 text-slate-600 dark:text-slate-300 font-bold text-sm hover:bg-slate-300 dark:hover:bg-slate-700 transition-colors">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const deviceData = @json(\App\Models\Device::with('lahan')->get());
    const lahanData = @json($lahan);

    window.openIoTModal = function(lahanId) {
        document.getElementById('iotModal').classList.remove('hidden');
        const lahan = lahanData.find(l => l.id === lahanId);
        const assignedIds = deviceData.filter(d => d.lahan_id === lahanId).map(d => d.id);
        const available = deviceData.filter(d => !d.lahan_id || d.lahan_id === lahanId);

        const list = document.getElementById('iotDeviceList');
        if (!available.length) {
            list.innerHTML = '<div class="text-center py-8 text-slate-400">Tidak ada perangkat IoT tersedia.</div>';
            return;
        }

        list.innerHTML = available.map(d => {
            const isAssigned = assignedIds.includes(d.id);
            return `
                <div class="flex items-center justify-between p-4 rounded-2xl border ${isAssigned ? 'border-green-200 dark:border-green-800 bg-green-50 dark:bg-green-900/10' : 'border-slate-100 dark:border-slate-800'}">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-violet-100 dark:bg-violet-900/30 flex items-center justify-center text-violet-600 dark:text-violet-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3H5a2 2 0 00-2 2v4m6-6h10a2 2 0 012 2v4M9 3v18m0 0h10a2 2 0 002-2V9M9 21H5a2 2 0 01-2-2V9m0 0h18"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="font-bold text-sm text-slate-800 dark:text-white">${d.device_name || d.device_uid}</p>
                            <p class="text-xs text-slate-400">${d.device_uid} &middot; ${d.lahan_id === lahanId ? 'Terpasang di lahan ini' : (d.lahan ? 'Terpasang di ' + d.lahan.nama_lahan : 'Belum terpasang')}</p>
                        </div>
                    </div>
                    <form action="/admin/iot-devices/${d.id}/approve" method="POST" class="flex items-center gap-2">
                        @csrf
                        ${!isAssigned ? `
                            <input type="hidden" name="lahan_id" value="${lahanId}">
                            <button type="submit" class="px-3 py-1.5 bg-green-500 hover:bg-green-600 text-white rounded-lg font-bold text-xs transition-all">
                                Pasang
                            </button>
                        ` : `
                            <span class="text-xs font-bold text-green-600 dark:text-green-400">Terpasang</span>
                            <a href="/admin/iot-devices?status=active" class="px-3 py-1.5 bg-slate-200 dark:bg-slate-700 text-slate-600 dark:text-slate-300 rounded-lg font-bold text-xs transition-all">
                                Kelola
                            </a>
                        `}
                    </form>
                </div>
            `;
        }).join('');
    };

    window.closeIoTModal = function() {
        document.getElementById('iotModal').classList.add('hidden');
    };
</script>
@endpush
