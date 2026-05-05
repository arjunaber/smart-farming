@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-green-50 to-blue-50 dark:from-slate-900 dark:to-slate-800 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div>
            <div class="mx-auto h-20 w-20 bg-green-600 rounded-2xl flex items-center justify-center">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v19M5 8h14M5 16h14"></path>
                </svg>
            </div>
            <h2 class="mt-6 text-center text-3xl font-black text-slate-800 dark:text-white">Daftar Akun Baru</h2>
        </div>
        <form class="mt-8 space-y-6" method="POST" action="{{ route('register') }}">
            @csrf
            @if ($errors->any())
                <div class="bg-red-100 border border-red-200 text-red-800 px-4 py-3 rounded-xl">
                    @foreach ($errors->all() as $error)
                        <p class="text-sm">{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <div class="space-y-4">
                <div>
                    <label for="name" class="block text-sm font-bold text-slate-500 uppercase tracking-wide mb-2">Nama Lengkap</label>
                    <input id="name" name="name" type="text" value="{{ old('name') }}" required autocomplete="name" class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-green-500 @error('name') border-red-500 @enderror bg-slate-50 dark:bg-slate-800">
                </div>
                <div>
                    <label for="email" class="block text-sm font-bold text-slate-500 uppercase tracking-wide mb-2">Email</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" required autocomplete="email" class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-green-500 @error('email') border-red-500 @enderror bg-slate-50 dark:bg-slate-800">
                </div>
                <div>
                    <label for="password" class="block text-sm font-bold text-slate-500 uppercase tracking-wide mb-2">Password</label>
                    <input id="password" name="password" type="password" required autocomplete="new-password" class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-green-500 @error('password') border-red-500 @enderror bg-slate-50 dark:bg-slate-800">
                </div>
                <div>
                    <label for="password_confirmation" class="block text-sm font-bold text-slate-500 uppercase tracking-wide mb-2">Konfirmasi Password</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password" class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-green-500 bg-slate-50 dark:bg-slate-800">
                </div>
                <div>
                    <label class="flex items-center gap-2">
                        <input type="radio" name="role" value="petani" checked class="w-4 h-4 text-green-600 bg-slate-50 border-slate-300 focus:ring-green-500">
                        <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Petani</span>
                    </label>
                </div>
            </div>

            <button type="submit" class="group relative w-full flex justify-center py-3.5 px-4 border border-transparent text-sm font-bold rounded-xl text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all shadow-lg shadow-green-200">
                Daftar Akun
            </button>

            <div class="text-center">
                <p class="text-sm text-slate-600 dark:text-slate-400">
                    Sudah punya akun? <a href="{{ route('login') }}" class="font-bold text-green-600 hover:text-green-700">Masuk sekarang</a>
                </p>
            </div>
        </form>
    </div>
</div>
@endsection

