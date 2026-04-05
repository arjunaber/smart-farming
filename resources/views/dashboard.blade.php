@extends('layouts.app')

@section('content')
    <div
        class="bg-white dark:bg-slate-900 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 p-10 shadow-sm transition-colors">
        <div class="flex items-center gap-3 mb-10">
            <div class="w-2.5 h-8 bg-green-500 rounded-full shadow-sm"></div>
            <h2 class="text-xl font-extrabold text-slate-800 dark:text-white">Soil Sensor AGA</h2>
        </div>

        <span class="text-3xl font-black text-slate-700 dark:text-slate-200">23.0°C</span>
    </div>
@endsection
