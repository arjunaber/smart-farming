@extends('layouts.app')

@section('content')
    <div class="space-y-8 pb-10">

        {{-- ═══════════════════════════════════════════════════ --}}
        {{-- HEADER                                             --}}
        {{-- ═══════════════════════════════════════════════════ --}}
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <p class="text-xs font-bold uppercase tracking-[0.2em] text-slate-400 dark:text-slate-500 mb-1">SmarTani
                    Admin</p>
                <h1 class="text-4xl font-black text-slate-900 dark:text-white leading-tight">Dashboard</h1>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.users') }}"
                    class="px-5 py-2.5 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 rounded-xl font-bold text-sm border border-slate-200 dark:border-slate-700 transition-all shadow-sm">
                    Kelola User
                </a>
                <a href="{{ route('admin.lahan') }}"
                    class="px-5 py-2.5 bg-slate-900 dark:bg-white hover:bg-slate-700 dark:hover:bg-slate-100 text-white dark:text-slate-900 rounded-xl font-bold text-sm transition-all shadow-sm">
                    Kelola Lahan
                </a>
            </div>
        </div>

        {{-- ═══════════════════════════════════════════════════ --}}
        {{-- KPI STATS ROW                                      --}}
        {{-- ═══════════════════════════════════════════════════ --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">

            {{-- Petani --}}
            <div
                class="bg-white dark:bg-slate-900 rounded-2xl p-6 border border-slate-100 dark:border-slate-800 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-start justify-between mb-4">
                    <div class="p-2.5 bg-blue-50 dark:bg-blue-900/20 rounded-xl">
                        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor"
                            stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <span
                        class="text-xs font-bold text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/20 px-2 py-0.5 rounded-full">Petani</span>
                </div>
                <p class="text-3xl font-black text-slate-900 dark:text-white">{{ $petaniCount }}</p>
                <p class="text-xs text-slate-400 mt-1">Total terdaftar</p>
            </div>

            {{-- Lahan --}}
            <div
                class="bg-white dark:bg-slate-900 rounded-2xl p-6 border border-slate-100 dark:border-slate-800 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-start justify-between mb-4">
                    <div class="p-2.5 bg-emerald-50 dark:bg-emerald-900/20 rounded-xl">
                        <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor"
                            stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <span
                        class="text-xs font-bold text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-900/20 px-2 py-0.5 rounded-full">Lahan</span>
                </div>
                <p class="text-3xl font-black text-slate-900 dark:text-white">{{ $lahanTotal }}</p>
                <p class="text-xs text-slate-400 mt-1">Total lahan aktif</p>
            </div>

            {{-- Devices --}}
            <div
                class="bg-white dark:bg-slate-900 rounded-2xl p-6 border border-slate-100 dark:border-slate-800 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-start justify-between mb-4">
                    <div class="p-2.5 bg-violet-50 dark:bg-violet-900/20 rounded-xl">
                        <svg class="w-5 h-5 text-violet-600 dark:text-violet-400" fill="none" stroke="currentColor"
                            stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 3H5a2 2 0 00-2 2v4m6-6h10a2 2 0 012 2v4M9 3v18m0 0h10a2 2 0 002-2V9M9 21H5a2 2 0 01-2-2V9m0 0h18" />
                        </svg>
                    </div>
                    <span
                        class="text-xs font-bold text-violet-600 dark:text-violet-400 bg-violet-50 dark:bg-violet-900/20 px-2 py-0.5 rounded-full">IoT</span>
                </div>
                <p class="text-3xl font-black text-slate-900 dark:text-white">{{ $deviceCount }}</p>
                <p class="text-xs text-slate-400 mt-1">Total perangkat</p>
            </div>

            {{-- Pending --}}
            <div
                class="rounded-2xl p-6 border shadow-sm hover:shadow-md transition-shadow
            {{ $pendingDeviceCount > 0
                ? 'bg-amber-50 dark:bg-amber-900/10 border-amber-200 dark:border-amber-800'
                : 'bg-white dark:bg-slate-900 border-slate-100 dark:border-slate-800' }}">
                <div class="flex items-start justify-between mb-4">
                    <div
                        class="p-2.5 {{ $pendingDeviceCount > 0 ? 'bg-amber-100 dark:bg-amber-900/30' : 'bg-slate-100 dark:bg-slate-800' }} rounded-xl">
                        <svg class="w-5 h-5 {{ $pendingDeviceCount > 0 ? 'text-amber-600 dark:text-amber-400' : 'text-slate-400' }}"
                            fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    @if ($pendingDeviceCount > 0)
                        <span
                            class="text-xs font-bold text-amber-700 dark:text-amber-400 bg-amber-100 dark:bg-amber-900/30 px-2 py-0.5 rounded-full animate-pulse">Perlu
                            aksi</span>
                    @endif
                </div>
                <p
                    class="text-3xl font-black {{ $pendingDeviceCount > 0 ? 'text-amber-700 dark:text-amber-400' : 'text-slate-900 dark:text-white' }}">
                    {{ $pendingDeviceCount }}</p>
                <p
                    class="text-xs {{ $pendingDeviceCount > 0 ? 'text-amber-600/70 dark:text-amber-500' : 'text-slate-400' }} mt-1">
                    Pending approval
                </p>
            </div>
        </div>

        {{-- ═══════════════════════════════════════════════════ --}}
        {{-- DEVICE STATUS BAR                                  --}}
        {{-- ═══════════════════════════════════════════════════ --}}
        @if ($deviceCount > 0)
            <div class="bg-white dark:bg-slate-900 rounded-2xl p-6 border border-slate-100 dark:border-slate-800 shadow-sm">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-5">
                    <div>
                        <h3 class="font-black text-slate-900 dark:text-white">Status Perangkat IoT</h3>
                        <p class="text-xs text-slate-400 mt-0.5">Ringkasan kondisi semua device</p>
                    </div>
                    <a href="{{ route('admin.iot-devices.index') }}"
                        class="text-xs font-bold text-slate-500 hover:text-slate-800 dark:hover:text-white transition-colors">
                        Kelola semua →
                    </a>
                </div>
                <div class="flex gap-2 mb-4">
                    @php
                        $onlinePct = $deviceCount ? round(($onlineDeviceCount / $deviceCount) * 100) : 0;
                        $offlinePct = $deviceCount
                            ? round(
                                (max(0, $deviceCount - $onlineDeviceCount - $pendingDeviceCount) / $deviceCount) * 100,
                            )
                            : 0;
                        $pendingPct = $deviceCount ? round(($pendingDeviceCount / $deviceCount) * 100) : 0;
                    @endphp
                    @if ($onlinePct > 0)
                        <div class="h-2 rounded-full bg-emerald-500 transition-all" style="width: {{ $onlinePct }}%">
                        </div>
                    @endif
                    @if ($offlinePct > 0)
                        <div class="h-2 rounded-full bg-slate-300 dark:bg-slate-600 transition-all"
                            style="width: {{ $offlinePct }}%"></div>
                    @endif
                    @if ($pendingPct > 0)
                        <div class="h-2 rounded-full bg-amber-400 transition-all" style="width: {{ $pendingPct }}%">
                        </div>
                    @endif
                </div>
                <div class="flex flex-wrap gap-6 text-sm">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                        <span class="text-slate-500 dark:text-slate-400">Online</span>
                        <span class="font-black text-slate-900 dark:text-white">{{ $onlineDeviceCount }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-slate-300 dark:bg-slate-600"></span>
                        <span class="text-slate-500 dark:text-slate-400">Offline</span>
                        <span
                            class="font-black text-slate-900 dark:text-white">{{ max(0, $deviceCount - $onlineDeviceCount - $pendingDeviceCount) }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-amber-400"></span>
                        <span class="text-slate-500 dark:text-slate-400">Pending</span>
                        <span class="font-black text-slate-900 dark:text-white">{{ $pendingDeviceCount }}</span>
                    </div>
                </div>
            </div>
        @endif

        {{-- ═══════════════════════════════════════════════════ --}}
        {{-- MAIN CONTENT: Lahan + Devices                      --}}
        {{-- ═══════════════════════════════════════════════════ --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            {{-- Lahan Terbaru --}}
            <div
                class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
                    <div>
                        <h3 class="font-black text-slate-900 dark:text-white">Lahan Terbaru</h3>
                        <p class="text-xs text-slate-400 mt-0.5">5 lahan paling baru</p>
                    </div>
                    <a href="{{ route('admin.lahan') }}"
                        class="text-xs font-bold text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 transition-colors">Lihat
                        semua →</a>
                </div>
                <div class="divide-y divide-slate-50 dark:divide-slate-800">
                    @forelse($recentLahans as $lahan)
                        <div
                            class="flex items-center gap-4 px-6 py-4 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                            <div
                                class="w-9 h-9 rounded-xl bg-gradient-to-br from-emerald-400 to-emerald-600 flex items-center justify-center text-white font-black text-xs flex-shrink-0">
                                {{ strtoupper(substr($lahan->nama_lahan, 0, 2)) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-bold text-slate-800 dark:text-white text-sm truncate">
                                    {{ $lahan->nama_lahan }}</p>
                                <p class="text-xs text-slate-400 truncate">{{ $lahan->petani->user->name ?? 'N/A' }}</p>
                            </div>
                            <div class="text-right flex-shrink-0">
                                <p
                                    class="font-black text-sm {{ $lahan->online_count > 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-slate-400' }}">
                                    {{ $lahan->online_count }}<span
                                        class="font-normal text-slate-300 dark:text-slate-600">/{{ $lahan->devices_count }}</span>
                                </p>
                                <p class="text-[10px] text-slate-400 uppercase tracking-wide">Online</p>
                            </div>
                        </div>
                    @empty
                        <div class="px-6 py-12 text-center text-slate-400 text-sm">Belum ada lahan</div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- ═══════════════════════════════════════════════════ --}}
        {{-- BOTTOM: Petani Aktif + Statistik                   --}}
        {{-- ═══════════════════════════════════════════════════ --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Petani Aktif --}}
            <div
                class="lg:col-span-2 bg-white dark:bg-slate-900 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
                    <div>
                        <h3 class="font-black text-slate-900 dark:text-white">Petani Aktif</h3>
                        <p class="text-xs text-slate-400 mt-0.5">Berdasarkan jumlah lahan</p>
                    </div>
                    <a href="{{ route('admin.users') }}"
                        class="text-xs font-bold text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 transition-colors">Lihat
                        semua →</a>
                </div>
                <div class="divide-y divide-slate-50 dark:divide-slate-800">
                    @forelse($users->take(6) as $user)
                        <div
                            class="flex items-center gap-4 px-6 py-4 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                            <div
                                class="w-9 h-9 rounded-xl bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white font-black text-xs flex-shrink-0">
                                {{ strtoupper(substr($user->name, 0, 2)) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-bold text-slate-800 dark:text-white text-sm truncate">{{ $user->name }}
                                </p>
                                <p class="text-xs text-slate-400 truncate">{{ $user->email }}</p>
                            </div>
                            <div class="flex items-center gap-2 flex-shrink-0">
                                <span
                                    class="font-black text-sm text-slate-900 dark:text-white">{{ $user->lahan_count }}</span>
                                <span class="text-xs text-slate-400">lahan</span>
                            </div>
                        </div>
                    @empty
                        <div class="px-6 py-12 text-center text-slate-400 text-sm">Belum ada petani</div>
                    @endforelse
                </div>
            </div>

            {{-- Statistik Ringkas --}}
            <div class="bg-slate-900 dark:bg-slate-950 rounded-2xl border border-slate-800 shadow-sm p-6 text-white">
                <h3 class="font-black text-white mb-1">Statistik Sistem</h3>
                <p class="text-xs text-slate-500 mb-6">Ringkasan keseluruhan</p>

                <div class="space-y-4">
                    <div class="flex items-center justify-between py-3 border-b border-slate-800">
                        <span class="text-sm text-slate-400">Total Petani</span>
                        <span class="font-black text-xl text-white">{{ $petaniCount }}</span>
                    </div>
                    <div class="flex items-center justify-between py-3 border-b border-slate-800">
                        <span class="text-sm text-slate-400">Total Lahan</span>
                        <span class="font-black text-xl text-white">{{ $lahanTotal }}</span>
                    </div>
                    <div class="flex items-center justify-between py-3 border-b border-slate-800">
                        <span class="text-sm text-slate-400">Total Device</span>
                        <span class="font-black text-xl text-white">{{ $deviceCount }}</span>
                    </div>
                    <div class="flex items-center justify-between py-3 border-b border-slate-800">
                        <span class="text-sm text-slate-400">Device Online</span>
                        <span class="font-black text-xl text-emerald-400">{{ $onlineDeviceCount }}</span>
                    </div>
                    <div class="flex items-center justify-between py-3">
                        <span class="text-sm text-slate-400">Pending Approval</span>
                        <span
                            class="font-black text-xl {{ $pendingDeviceCount > 0 ? 'text-amber-400' : 'text-slate-500' }}">{{ $pendingDeviceCount }}</span>
                    </div>
                </div>

                @if ($pendingDeviceCount > 0)
                    <a href="{{ route('admin.iot-devices.index', ['status' => 'pending']) }}"
                        class="mt-6 w-full flex items-center justify-center gap-2 py-3 bg-amber-500 hover:bg-amber-400 text-white rounded-xl font-bold text-sm transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Approve {{ $pendingDeviceCount }} Device
                    </a>
                @else
                    <a href="{{ route('admin.iot-devices.index') }}"
                        class="mt-6 w-full flex items-center justify-center gap-2 py-3 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-xl font-bold text-sm transition-all">
                        Kelola Device →
                    </a>
                @endif
            </div>
        </div>

    </div>
@endsection
