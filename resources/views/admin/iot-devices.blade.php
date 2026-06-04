@extends('layouts.app')

@section('content')
<div class="space-y-8">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
        <div>
            <h1 class="text-3xl font-black text-slate-800 dark:text-white">IoT Devices</h1>
            <p class="text-slate-500">Kelola perangkat IoT</p>
        </div>
        <div class="flex gap-4">
            <a href="{{ route('admin.iot-devices.index') }}" class="px-6 py-3 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 rounded-2xl font-bold transition-all">All</a>
            <a href="{{ route('admin.iot-devices.pending') }}" class="px-6 py-3 bg-yellow-100 dark:bg-yellow-900/30 hover:bg-yellow-200 dark:hover:bg-yellow-800/30 text-yellow-700 dark:text-yellow-300 rounded-2xl font-bold transition-all">Pending</a>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-50 dark:bg-green-500/10 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-300 px-6 py-4 rounded-2xl font-medium">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-300 px-6 py-4 rounded-2xl font-medium">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white dark:bg-slate-900 rounded-3xl shadow-xl border border-slate-100 dark:border-slate-800 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-800/50 text-slate-500 dark:text-slate-400 text-xs uppercase tracking-wider font-bold">
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
                        <td class="px-6 py-4">
                            <code class="text-xs bg-slate-100 dark:bg-slate-800 px-2 py-1 rounded-lg font-mono">{{ $device->device_uid }}</code>
                        </td>
                        <td class="px-6 py-4 font-medium text-slate-800 dark:text-white">
                            {{ $device->device_name ?? '-' }}
                        </td>
                        <td class="px-6 py-4">
                            @if($device->lahan)
                                <span class="text-slate-700 dark:text-slate-300">{{ $device->lahan->nama_lahan }}</span>
                            @else
                                <span class="text-slate-400 italic">Unassigned</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($device->isPending())
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-300">Pending</span>
                            @elseif($device->isActive())
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300">Active</span>
                                @if($device->isOnline())
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400 ml-1">Online</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 ml-1">Offline</span>
                                @endif
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-300">Suspended</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-slate-500">
                            {{ $device->approved_at ? $device->approved_at->format('d M Y H:i') : '-' }}
                        </td>
                        <td class="px-6 py-4 text-slate-500">
                            {{ $device->last_seen ? $device->last_seen->diffForHumans() : 'Never' }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex gap-2">
                                @if($device->isPending() && isset($lahans))
                                    <form action="{{ route('admin.iot-devices.approve', $device) }}" method="POST" class="flex gap-2 items-center">
                                        @csrf
                                        <select name="lahan_id" required class="text-xs bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg px-2 py-1.5">
                                            <option value="">Select lahan...</option>
                                            @foreach($lahans as $lahan)
                                                <option value="{{ $lahan->id }}">{{ $lahan->nama_lahan }} ({{ $lahan->petani->user->name ?? 'N/A' }})</option>
                                            @endforeach
                                        </select>
                                        <button class="px-3 py-1.5 bg-green-500 hover:bg-green-600 text-white rounded-lg font-bold text-xs transition-all">Approve</button>
                                    </form>
                                @elseif($device->isPending() && !isset($lahans))
                                    <span class="text-xs text-slate-400 italic">Create a lahan first</span>
                                @endif
                                @if($device->isActive())
                                    <form action="{{ route('admin.iot-devices.suspend', $device) }}" method="POST">
                                        @csrf
                                        <button class="px-3 py-1.5 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg font-bold text-xs transition-all" onclick="return confirm('Suspend this device?')">Suspend</button>
                                    </form>
                                @endif
                                <form action="{{ route('admin.iot-devices.reset', $device) }}" method="POST">
                                    @csrf
                                    <button class="px-3 py-1.5 bg-red-500 hover:bg-red-600 text-white rounded-lg font-bold text-xs transition-all" onclick="return confirm('Reset device to pending? This will invalidate any existing token.')">Reset</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-slate-500">No devices found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($devices->hasPages())
            <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-800">
                {{ $devices->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
