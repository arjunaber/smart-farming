@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto space-y-8">
    <div>
        <h1 class="text-3xl font-black text-slate-900 dark:text-white">Profil Saya</h1>
        <p class="text-slate-500 dark:text-slate-400 text-sm mt-1">Informasi akun dan data diri Anda.</p>
    </div>

    <div class="bg-white dark:bg-slate-900 rounded-[2rem] border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden">
        <div class="p-8">
            <div class="flex items-center gap-6 mb-8">
                <div class="w-20 h-20 rounded-[2rem] bg-gradient-to-br from-green-400 to-emerald-600 flex items-center justify-center text-white text-3xl font-black shadow-lg shadow-green-500/20">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div>
                    <h2 class="text-2xl font-black text-slate-900 dark:text-white">{{ $user->name }}</h2>
                    <p class="text-slate-500 dark:text-slate-400">{{ $user->email }}</p>
                    <span class="inline-block mt-1 px-3 py-0.5 rounded-full text-xs font-bold {{ $user->role == 'super_admin' ? 'bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300' : 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300' }}">
                        {{ ucfirst($user->role) }}
                    </span>
                </div>
            </div>

            <div class="border-t border-slate-100 dark:border-slate-800 pt-6">
                <h3 class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-4">Data Akun</h3>
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <dt class="text-xs font-bold text-slate-400 uppercase tracking-wider">Nama Lengkap</dt>
                        <dd class="mt-1 text-sm font-bold text-slate-800 dark:text-white">{{ $user->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-bold text-slate-400 uppercase tracking-wider">Email</dt>
                        <dd class="mt-1 text-sm font-bold text-slate-800 dark:text-white">{{ $user->email }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-bold text-slate-400 uppercase tracking-wider">Role</dt>
                        <dd class="mt-1 text-sm font-bold text-slate-800 dark:text-white">{{ ucfirst($user->role) }}</dd>
                    </div>
                </dl>
            </div>

            @if ($user->petani)
            <div class="border-t border-slate-100 dark:border-slate-800 pt-6 mt-6">
                <h3 class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-4">Data Petani</h3>
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <dt class="text-xs font-bold text-slate-400 uppercase tracking-wider">Nama Lengkap</dt>
                        <dd class="mt-1 text-sm font-bold text-slate-800 dark:text-white">{{ $user->petani->nama_lengkap }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-bold text-slate-400 uppercase tracking-wider">NIK</dt>
                        <dd class="mt-1 text-sm font-bold text-slate-800 dark:text-white">{{ $user->petani->nik ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-bold text-slate-400 uppercase tracking-wider">No. HP</dt>
                        <dd class="mt-1 text-sm font-bold text-slate-800 dark:text-white">{{ $user->petani->no_hp ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-bold text-slate-400 uppercase tracking-wider">Kelompok Tani</dt>
                        <dd class="mt-1 text-sm font-bold text-slate-800 dark:text-white">{{ $user->petani->kelompok_tani ?? '-' }}</dd>
                    </div>
                    <div class="md:col-span-2">
                        <dt class="text-xs font-bold text-slate-400 uppercase tracking-wider">Alamat</dt>
                        <dd class="mt-1 text-sm font-bold text-slate-800 dark:text-white">{{ $user->petani->alamat ?? '-' }}</dd>
                    </div>
                </dl>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
