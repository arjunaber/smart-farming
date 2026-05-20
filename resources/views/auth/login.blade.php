@extends('layouts.app')

@section('content')
    <div
        class="min-h-screen flex items-center justify-center bg-gradient-to-br from-green-50 to-blue-50 dark:from-slate-900 dark:to-slate-800 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <div>
                <div class="mx-auto h-20 w-20 bg-green-600 rounded-2xl flex items-center justify-center">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v19M5 8h14M5 16h14">
                        </path>
                    </svg>
                </div>
                <h2 class="mt-6 text-center text-3xl font-black text-slate-800 dark:text-white">Masuk ke AGA</h2>
            </div>
            <form class="mt-8 space-y-6" method="POST" action="{{ route('login') }}">
                @csrf
                <!-- Error messages -->
                @if ($errors->any())
                    <div class="bg-red-100 border border-red-200 text-red-800 px-4 py-3 rounded-xl">
                        @foreach ($errors->all() as $error)
                            <p class="text-sm">{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <div class="space-y-4">
                    <div>
                        <label for="email"
                            class="block text-sm font-bold text-slate-500 uppercase tracking-wide mb-2">Email</label>
                        <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus
                            autocomplete="email"
                            class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('email') border-red-500 @enderror bg-slate-50 dark:bg-slate-800">
                    </div>
                    <div>
                        <label for="password"
                            class="block text-sm font-bold text-slate-500 uppercase tracking-wide mb-2">Password</label>
                        <input id="password" name="password" type="password" required autocomplete="current-password"
                            class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('password') border-red-500 @enderror bg-slate-50 dark:bg-slate-800">
                    </div>
                    <div class="flex items-center">
                        <input id="remember" name="remember" type="checkbox"
                            class="h-4 w-4 text-green-600 focus:ring-green-500 border-slate-300 rounded">
                        <label for="remember" class="ml-2 block text-sm text-slate-600 dark:text-slate-400">Ingat
                            saya</label>
                    </div>
                </div>

                <button type="submit"
                    class="group relative w-full flex justify-center py-3.5 px-4 border border-transparent text-sm font-bold rounded-xl text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all shadow-lg shadow-green-200">
                    Masuk
                </button>
            </form>
        </div>
    </div>
@endsection
