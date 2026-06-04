@extends('layouts.app')

@section('content')
    <div class="space-y-8">

        {{-- Header --}}
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h1 class="text-3xl font-black text-slate-800 dark:text-white">IoT Devices</h1>
                <p class="text-slate-500 dark:text-slate-400 text-sm mt-1">Kelola dan pantau seluruh perangkat IoT</p>
            </div>
        </div>

        {{-- Flash Messages --}}
        @if (session('success'))
            <div
                class="bg-green-50 dark:bg-green-500/10 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-300 px-6 py-4 rounded-2xl font-medium text-sm">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div
                class="bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-300 px-6 py-4 rounded-2xl font-medium text-sm">
                {{ session('error') }}
            </div>
        @endif

        {{-- Card --}}
        <div
            class="bg-white dark:bg-slate-900 rounded-3xl shadow-xl border border-slate-100 dark:border-slate-800 overflow-hidden">

            {{-- Filter Tabs + Search --}}
            <div
                class="px-6 pt-5 pb-0 flex flex-col sm:flex-row sm:items-center gap-4 border-b border-slate-100 dark:border-slate-800">

                {{-- Tabs --}}
                <nav class="flex gap-1 flex-wrap" id="filter-tabs">
                    @php
                        $tabs = [
                            'all' => ['label' => 'All', 'color' => 'slate'],
                            'pending' => ['label' => 'Pending', 'color' => 'yellow'],
                            'active' => ['label' => 'Active', 'color' => 'green'],
                            'suspended' => ['label' => 'Suspended', 'color' => 'red'],
                        ];
                        $active = request('status', 'all');
                    @endphp

                    @foreach ($tabs as $key => $tab)
                        @php
                            $isActive = $active === $key;
                            $colorMap = [
                                'slate' => $isActive
                                    ? 'bg-slate-800 dark:bg-slate-200 text-white dark:text-slate-900'
                                    : 'text-slate-500 hover:text-slate-700 dark:hover:text-slate-300',
                                'yellow' => $isActive
                                    ? 'bg-yellow-500 text-white'
                                    : 'text-yellow-600 dark:text-yellow-400 hover:text-yellow-700',
                                'green' => $isActive
                                    ? 'bg-green-500 text-white'
                                    : 'text-green-600 dark:text-green-400 hover:text-green-700',
                                'red' => $isActive
                                    ? 'bg-red-500 text-white'
                                    : 'text-red-600 dark:text-red-400 hover:text-red-700',
                            ];
                            $countMap = [
                                'all' => $counts['all'] ?? 0,
                                'pending' => $counts['pending'] ?? 0,
                                'active' => $counts['active'] ?? 0,
                                'suspended' => $counts['suspended'] ?? 0,
                            ];
                        @endphp
                        <a href="{{ request()->fullUrlWithQuery(['status' => $key, 'page' => 1]) }}"
                            class="inline-flex items-center gap-2 px-4 py-2.5 mb-0 rounded-t-xl text-sm font-bold transition-all {{ $colorMap[$tab['color']] }} {{ $isActive ? 'shadow-sm' : '' }}">
                            {{ $tab['label'] }}
                            <span
                                class="text-[11px] font-bold px-1.5 py-0.5 rounded-full {{ $isActive ? 'bg-white/25 text-white' : 'bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400' }}">
                                {{ $countMap[$key] }}
                            </span>
                        </a>
                    @endforeach
                </nav>

                {{-- Search --}}
                <div class="ml-auto mb-2">
                    <form method="GET" action="{{ request()->url() }}">
                        <input type="hidden" name="status" value="{{ $active }}">
                        <div class="relative">
                            <span class="absolute inset-y-0 left-3 flex items-center text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <circle cx="11" cy="11" r="8" />
                                    <path d="M21 21l-4.35-4.35" />
                                </svg>
                            </span>
                            <input type="text" name="q" value="{{ request('q') }}"
                                placeholder="Cari UID atau nama..."
                                class="pl-9 pr-4 py-2 text-sm rounded-xl bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300 dark:focus:ring-slate-600 w-56 transition-all">
                        </div>
                    </form>
                </div>
            </div>

            {{-- Table --}}
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr
                            class="bg-slate-50 dark:bg-slate-800/50 text-slate-500 dark:text-slate-400 text-xs uppercase tracking-wider font-bold">
                            <th class="px-6 py-4 text-left">Device UID</th>
                            <th class="px-6 py-4 text-left">Name</th>
                            <th class="px-6 py-4 text-left">Lahan</th>
                            <th class="px-6 py-4 text-left">Status</th>
                            <th class="px-6 py-4 text-left">Approved At</th>
                            <th class="px-6 py-4 text-left">Last Seen</th>
                            <th class="px-6 py-4 text-left">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @forelse($devices as $device)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/30 transition-colors">

                                {{-- UID --}}
                                <td class="px-6 py-4">
                                    <code
                                        class="text-xs bg-slate-100 dark:bg-slate-800 px-2 py-1 rounded-lg font-mono">{{ $device->device_uid }}</code>
                                </td>

                                {{-- Name --}}
                                <td class="px-6 py-4 font-medium text-slate-800 dark:text-white">
                                    {{ $device->device_name ?? '-' }}
                                </td>

                                {{-- Lahan --}}
                                <td class="px-6 py-4">
                                    @if ($device->lahan)
                                        <span
                                            class="text-slate-700 dark:text-slate-300">{{ $device->lahan->nama_lahan }}</span>
                                    @else
                                        <span class="text-slate-400 italic text-xs">Unassigned</span>
                                    @endif
                                </td>

                                {{-- Status --}}
                                <td class="px-6 py-4">
                                    @if ($device->isPending())
                                        <span
                                            class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-300">
                                            <span
                                                class="w-1.5 h-1.5 rounded-full bg-yellow-500 mr-1.5 animate-pulse"></span>Pending
                                        </span>
                                    @elseif($device->isActive())
                                        <div class="flex items-center gap-1.5 flex-wrap">
                                            <span
                                                class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300">
                                                <span class="w-1.5 h-1.5 rounded-full bg-green-500 mr-1.5"></span>Active
                                            </span>
                                            @if ($device->isOnline())
                                                <span
                                                    class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400">
                                                    <span
                                                        class="w-1 h-1 rounded-full bg-emerald-500 mr-1 animate-pulse"></span>Online
                                                </span>
                                            @else
                                                <span
                                                    class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400">
                                                    <span class="w-1 h-1 rounded-full bg-slate-400 mr-1"></span>Offline
                                                </span>
                                            @endif
                                        </div>
                                    @else
                                        <span
                                            class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-300">
                                            <span class="w-1.5 h-1.5 rounded-full bg-red-500 mr-1.5"></span>Suspended
                                        </span>
                                    @endif
                                </td>

                                {{-- Approved At --}}
                                <td class="px-6 py-4 text-slate-500 dark:text-slate-400 text-xs">
                                    {{ $device->approved_at ? $device->approved_at->format('d M Y H:i') : '-' }}
                                </td>

                                {{-- Last Seen --}}
                                <td class="px-6 py-4 text-slate-500 dark:text-slate-400 text-xs">
                                    {{ $device->last_seen ? $device->last_seen->diffForHumans() : 'Never' }}
                                </td>

                                {{-- Actions --}}
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2 flex-wrap">

                                        {{-- Approve (pending only) --}}
                                        @if ($device->isPending())
                                            @if (isset($lahans) && $lahans->count())
                                                <form action="{{ route('admin.iot-devices.approve', $device) }}"
                                                    method="POST" class="flex gap-2 items-center">
                                                    @csrf
                                                    <select name="lahan_id" required
                                                        class="text-xs bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg px-2 py-1.5 focus:outline-none focus:ring-2 focus:ring-green-300">
                                                        <option value="">Pilih lahan...</option>
                                                        @foreach ($lahans as $lahan)
                                                            <option value="{{ $lahan->id }}">{{ $lahan->nama_lahan }}
                                                                ({{ $lahan->petani->user->name ?? 'N/A' }})</option>
                                                        @endforeach
                                                    </select>
                                                    <button type="submit"
                                                        class="px-3 py-1.5 bg-green-500 hover:bg-green-600 text-white rounded-lg font-bold text-xs transition-all whitespace-nowrap">
                                                        ✓ Approve
                                                    </button>
                                                </form>
                                            @else
                                                <span class="text-xs text-slate-400 italic">Buat lahan terlebih
                                                    dahulu</span>
                                            @endif
                                        @endif

                                        {{-- Suspend (active only) --}}
                                        @if ($device->isActive())
                                            <form action="{{ route('admin.iot-devices.suspend', $device) }}"
                                                method="POST">
                                                @csrf
                                                <button type="submit" onclick="return confirm('Suspend perangkat ini?')"
                                                    class="px-3 py-1.5 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg font-bold text-xs transition-all whitespace-nowrap">
                                                    Suspend
                                                </button>
                                            </form>
                                        @endif

                                        {{-- Reset --}}
                                        <form action="{{ route('admin.iot-devices.reset', $device) }}" method="POST">
                                            @csrf
                                            <button type="submit"
                                                onclick="return confirm('Reset perangkat ke pending? Token yang ada akan dinonaktifkan.')"
                                                class="px-3 py-1.5 bg-red-500 hover:bg-red-600 text-white rounded-lg font-bold text-xs transition-all whitespace-nowrap">
                                                Reset
                                            </button>
                                        </form>

                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center gap-3 text-slate-400">
                                        <svg class="w-12 h-12 opacity-30" fill="none" stroke="currentColor"
                                            stroke-width="1.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M9 3H5a2 2 0 00-2 2v4m6-6h10a2 2 0 012 2v4M9 3v18m0 0h10a2 2 0 002-2V9M9 21H5a2 2 0 01-2-2V9m0 0h18" />
                                        </svg>
                                        <span class="text-sm font-medium">Tidak ada perangkat ditemukan</span>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if ($devices->hasPages())
                <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-800">
                    {{ $devices->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
