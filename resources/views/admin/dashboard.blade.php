@extends('layouts.app')

@section('content')
<div class="space-y-8">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
        <div>
            <h1 class="text-3xl font-black text-slate-800 dark:text-white">Panel Admin</h1>
            <p class="text-slate-500">Kelola sistem SmarTani</p>
        </div>
        <div class="flex gap-4">
            <a href="{{ route('admin.users') }}" class="px-6 py-3 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 rounded-2xl font-bold transition-all">Kelola User</a>
            <a href="{{ route('admin.lahan') }}" class="px-6 py-3 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 rounded-2xl font-bold transition-all">Kelola Lahan</a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-3xl p-8 shadow-2xl">
            <div class="flex items-center gap-3 mb-4">
                <div class="p-3 bg-white/20 rounded-2xl">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-blue-100 text-sm uppercase tracking-wide font-bold">Total Petani</p>
                    <p class="text-3xl font-black">{{ $petaniCount }}</p>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-green-500 to-green-600 text-white rounded-3xl p-8 shadow-2xl">
            <div class="flex items-center gap-3 mb-4">
                <div class="p-3 bg-white/20 rounded-2xl">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2h10a2 2 0 012 2v2M5 11h14"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-green-100 text-sm uppercase tracking-wide font-bold">Total Lahan</p>
                    <p class="text-3xl font-black">{{ $lahanTotal }}</p>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-purple-500 to-purple-600 text-white rounded-3xl p-8 shadow-2xl">
            <div class="flex items-center gap-3 mb-4">
                <div class="p-3 bg-white/20 rounded-2xl">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-purple-100 text-sm uppercase tracking-wide font-bold">Total User</p>
                    <p class="text-3xl font-black">{{ $users->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-orange-500 to-orange-600 text-white rounded-3xl p-8 shadow-2xl">
            <div class="flex items-center gap-3 mb-4">
                <div class="p-3 bg-white/20 rounded-2xl">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-orange-100 text-sm uppercase tracking-wide font-bold">Aktivitas</p>
                    <p class="text-3xl font-black">24h</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white dark:bg-slate-900 rounded-3xl p-8 shadow-xl border border-slate-100 dark:border-slate-800">
            <p class="text-sm font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Total Devices</p>
            <p class="text-4xl font-black text-slate-800 dark:text-white mt-2">{{ $deviceCount }}</p>
        </div>

        <div class="bg-white dark:bg-slate-900 rounded-3xl p-8 shadow-xl border border-slate-100 dark:border-slate-800">
            <p class="text-sm font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Online</p>
            <p class="text-4xl font-black text-green-500 mt-2">{{ $onlineDeviceCount }}</p>
        </div>

        <div class="bg-white dark:bg-slate-900 rounded-3xl p-8 shadow-xl border border-slate-100 dark:border-slate-800">
            <p class="text-sm font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Offline</p>
            <p class="text-4xl font-black text-slate-400 mt-2">{{ max(0, $deviceCount - $onlineDeviceCount) }}</p>
        </div>

        <div class="bg-white dark:bg-slate-900 rounded-3xl p-8 shadow-xl border border-slate-100 dark:border-slate-800">
            <p class="text-sm font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Pending Approval</p>
            <p class="text-4xl font-black text-yellow-500 mt-2">{{ $pendingDeviceCount }}</p>
            @if($pendingDeviceCount > 0)
                <a href="{{ route('admin.iot-devices.pending') }}" class="inline-block mt-3 text-sm font-bold text-yellow-600 hover:text-yellow-700">Approve now →</a>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <div class="bg-white dark:bg-slate-900 rounded-3xl p-8 shadow-xl border border-slate-100 dark:border-slate-800">
            <h3 class="text-xl font-black text-slate-800 dark:text-white mb-6">Lahan Terbaru</h3>
            <div class="space-y-4">
                @forelse($recentLahans as $lahan)
                    <div class="flex items-center gap-4 p-4 bg-slate-50 dark:bg-slate-800 rounded-2xl">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-r from-green-500 to-green-600 flex items-center justify-center text-white font-bold text-sm">
                            {{ substr($lahan->nama_lahan, 0, 2) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-bold text-slate-800 dark:text-white truncate">{{ $lahan->nama_lahan }}</p>
                            <p class="text-sm text-slate-500">{{ $lahan->petani->user->name ?? 'N/A' }}</p>
                        </div>
                        <div class="text-right">
                            <p class="font-bold text-lg text-green-600 dark:text-green-400">{{ $lahan->online_count }}/{{ $lahan->devices_count }}</p>
                            <p class="text-xs text-slate-500">Online</p>
                        </div>
                    </div>
                @empty
                    <p class="text-center text-slate-500 py-8">Belum ada lahan</p>
                @endforelse
            </div>
        </div>

        <div class="bg-white dark:bg-slate-900 rounded-3xl p-8 shadow-xl border border-slate-100 dark:border-slate-800">
            <h3 class="text-xl font-black text-slate-800 dark:text-white mb-6">Device Pending</h3>
            <div class="space-y-4">
                @forelse($pendingDevices as $device)
                    <div class="flex items-center gap-4 p-4 bg-slate-50 dark:bg-slate-800 rounded-2xl">
                        <div class="w-10 h-10 rounded-full bg-yellow-100 dark:bg-yellow-900/30 flex items-center justify-center text-yellow-600 font-bold text-xs">
                            P
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-bold text-slate-800 dark:text-white truncate text-sm">{{ $device->device_name ?? 'Unnamed' }}</p>
                            <p class="text-xs text-slate-500 font-mono">{{ $device->device_uid }}</p>
                        </div>
                        <div>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-300">Pending</span>
                        </div>
                    </div>
                @empty
                    <p class="text-center text-slate-500 py-8">Tidak ada device pending</p>
                @endforelse
            </div>
            @if($pendingDeviceCount > 0)
                <div class="mt-4 text-right">
                    <a href="{{ route('admin.iot-devices.pending') }}" class="text-sm font-bold text-yellow-600 hover:text-yellow-700">Approve device →</a>
                </div>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <div class="bg-white dark:bg-slate-900 rounded-3xl p-8 shadow-xl border border-slate-100 dark:border-slate-800">
            <h3 class="text-xl font-black text-slate-800 dark:text-white mb-6">Petani Aktif</h3>
            <div class="space-y-4">
                @forelse($users->take(6) as $user)
                    <div class="flex items-center gap-4 p-4 bg-slate-50 dark:bg-slate-800 rounded-2xl">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-r from-green-500 to-green-600 flex items-center justify-center text-white font-bold text-sm">
                            {{ substr($user->name, 0, 2) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-bold text-slate-800 dark:text-white truncate">{{ $user->name }}</p>
                            <p class="text-sm text-slate-500">{{ $user->email }}</p>
                        </div>
                        <div class="text-right">
                            <p class="font-bold text-lg text-green-600 dark:text-green-400">{{ $user->lahan_count }}</p>
                            <p class="text-xs text-slate-500">Lahan</p>
                        </div>
                    </div>
                @empty
                    <p class="text-center text-slate-500 py-8">Belum ada petani</p>
                @endforelse
            </div>
        </div>

        <div class="bg-white dark:bg-slate-900 rounded-3xl p-8 shadow-xl border border-slate-100 dark:border-slate-800">
            <h3 class="text-xl font-black text-slate-800 dark:text-white mb-6">Statistik Sistem</h3>
            <div class="grid grid-cols-2 gap-4 text-center">
                <div>
                    <p class="text-3xl font-black text-slate-800 dark:text-white">{{ $petaniCount }}</p>
                    <p class="text-sm text-slate-500">Petani</p>
                </div>
                <div>
                    <p class="text-3xl font-black text-slate-800 dark:text-white">{{ $lahanTotal }}</p>
                    <p class="text-sm text-slate-500">Lahan</p>
                </div>
                <div>
                    <p class="text-3xl font-black text-slate-800 dark:text-white">{{ $deviceCount }}</p>
                    <p class="text-sm text-slate-500">Devices</p>
                </div>
                <div>
                    <p class="text-3xl font-black text-green-500">{{ $onlineDeviceCount }}</p>
                    <p class="text-sm text-slate-500">Online</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
