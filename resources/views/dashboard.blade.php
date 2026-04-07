@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        {{-- Header Section --}}
        <div class="flex justify-between items-end">
            <div>
                <h1 class="text-2xl font-black text-slate-800 dark:text-white">Tinjauan Kebun</h1>
                <p class="text-slate-500 dark:text-slate-400 text-sm mt-1">
                    Pemantauan lahan Blok A-1 terintegrasi IoT & BMKG
                </p>
            </div>
            <div class="flex gap-2">
                <button onclick="document.getElementById('locationModal').classList.remove('hidden')"
                    class="text-xs font-bold bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-400 px-3 py-1.5 rounded-full border border-slate-200 dark:border-slate-700 hover:bg-slate-50 transition-all shadow-sm">
                    📍 Ubah Lokasi: <span id="header-area-btn" class="text-orange-500">{{ $area ?? 'Bekasi' }}</span>
                </button>
                <span
                    class="text-xs font-bold bg-green-100 text-green-700 dark:bg-green-500/20 dark:text-green-400 px-3 py-1.5 rounded-full flex items-center gap-2 shadow-sm border border-green-200 dark:border-green-800">
                    <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span> Sistem Online
                </span>
            </div>
        </div>

        {{-- LLM Recommendation Card --}}
        <div
            class="bg-gradient-to-r from-amber-500 to-orange-500 rounded-[2.5rem] p-8 md:p-10 shadow-lg shadow-orange-500/20 text-white relative overflow-hidden">
            <svg class="absolute right-0 top-0 w-64 h-64 text-white/10 -mt-10 -mr-10" fill="currentColor"
                viewBox="0 0 24 24">
                <path d="M13 10V3L4 14h7v7l9-11h-7z"></path>
            </svg>
            <div class="relative z-10 flex flex-col md:flex-row gap-6 items-center justify-between">
                <div class="flex items-center gap-6">
                    <div class="bg-white/20 p-5 rounded-3xl backdrop-blur-sm shadow-inner flex-shrink-0">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold mb-2">Rekomendasi Tindakan Irigasi Manual</h3>
                        <p class="text-orange-50 leading-relaxed text-sm max-w-3xl">
                            Berdasarkan <strong>Sensor IoT</strong>, kelembapan tanah Anda saat ini tergolong kering (42%).
                            Mempertimbangkan prediksi <strong>BMKG</strong> di area <strong
                                id="card-area">{{ $area ?? 'Lokasi' }}</strong>
                            bahwa hari ini suhu mencapai <span id="card-temp">{{ $temp ?? '--' }}</span>°C dengan kondisi
                            <span id="card-cuaca">{{ $cuaca ?? '--' }}</span>,
                            LLM menyarankan Anda untuk <strong>segera melakukan penyiraman</strong>.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Metrics Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            {{-- IoT Kelembapan Tanah --}}
            <div
                class="bg-white dark:bg-slate-900 rounded-[2rem] border border-slate-100 dark:border-slate-800 p-6 shadow-sm flex flex-col relative overflow-hidden">
                <div
                    class="absolute top-0 right-0 bg-blue-500 text-white text-[10px] font-bold px-3 py-1 rounded-bl-xl z-10">
                    DATA IOT</div>
                <div class="flex justify-between items-start mb-4 mt-2">
                    <div class="p-3 bg-blue-50 dark:bg-blue-500/10 rounded-2xl">
                        <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 14C19 17.866 15.866 21 12 21C8.13401 21 5 17.866 5 14C5 10.134 12 3 12 3C12 3 19 10.134 19 14Z">
                            </path>
                        </svg>
                    </div>
                    <span
                        class="text-xs font-bold text-red-500 bg-red-50 dark:bg-red-500/10 px-2.5 py-1 rounded-lg border border-red-100 dark:border-red-900/50">Kering</span>
                </div>
                <div class="mt-auto">
                    <h3 class="text-slate-400 dark:text-slate-500 text-xs font-bold tracking-widest uppercase mb-1">
                        Kelembapan Tanah</h3>
                    <p class="text-3xl font-black text-slate-800 dark:text-white">
                        42<span class="text-lg text-slate-400 font-semibold">%</span>
                    </p>
                </div>
            </div>

            {{-- IoT pH Tanah --}}
            <div
                class="bg-white dark:bg-slate-900 rounded-[2rem] border border-slate-100 dark:border-slate-800 p-6 shadow-sm flex flex-col relative overflow-hidden">
                <div
                    class="absolute top-0 right-0 bg-blue-500 text-white text-[10px] font-bold px-3 py-1 rounded-bl-xl z-10">
                    DATA IOT</div>
                <div class="flex justify-between items-start mb-4 mt-2">
                    <div class="p-3 bg-purple-50 dark:bg-purple-500/10 rounded-2xl">
                        <svg class="w-6 h-6 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z">
                            </path>
                        </svg>
                    </div>
                    <span
                        class="text-xs font-bold text-slate-400 bg-slate-100 dark:bg-slate-800 px-2.5 py-1 rounded-lg">Normal</span>
                </div>
                <div class="mt-auto">
                    <h3 class="text-slate-400 dark:text-slate-500 text-xs font-bold tracking-widest uppercase mb-1">pH Tanah
                    </h3>
                    <p class="text-3xl font-black text-slate-800 dark:text-white">6.2</p>
                </div>
            </div>

            {{-- BMKG Suhu Udara --}}
            <div
                class="bg-white dark:bg-slate-900 rounded-[2rem] border border-slate-100 dark:border-slate-800 p-6 shadow-sm flex flex-col relative overflow-hidden">
                <div
                    class="absolute top-0 right-0 bg-orange-500 text-white text-[10px] font-bold px-3 py-1 rounded-bl-xl z-10">
                    API BMKG</div>
                <div class="flex justify-between items-start mb-4 mt-2">
                    <div class="p-3 bg-orange-50 dark:bg-orange-500/10 rounded-2xl">
                        <svg class="w-6 h-6 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z">
                            </path>
                        </svg>
                    </div>
                </div>
                <div class="mt-auto">
                    <h3 class="text-slate-400 dark:text-slate-500 text-xs font-bold tracking-widest uppercase mb-1">Suhu
                        <span id="label-area">{{ $area ?? 'Area' }}</span>
                    </h3>
                    <p class="text-3xl font-black text-slate-800 dark:text-white">
                        <span id="display-temp">{{ $temp ?? '--' }}</span><span
                            class="text-lg text-slate-400 font-semibold">°C</span>
                    </p>
                </div>
            </div>

            {{-- BMKG Kelembapan Udara --}}
            <div
                class="bg-white dark:bg-slate-900 rounded-[2rem] border border-slate-100 dark:border-slate-800 p-6 shadow-sm flex flex-col relative overflow-hidden">
                <div
                    class="absolute top-0 right-0 bg-teal-500 text-white text-[10px] font-bold px-3 py-1 rounded-bl-xl z-10">
                    API BMKG</div>
                <div class="flex justify-between items-start mb-4 mt-2">
                    <div class="p-3 bg-teal-50 dark:bg-teal-500/10 rounded-2xl">
                        <svg class="w-6 h-6 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z">
                            </path>
                        </svg>
                    </div>
                </div>
                <div class="mt-auto">
                    <h3 class="text-slate-400 dark:text-slate-500 text-xs font-bold tracking-widest uppercase mb-1">Lembap
                        Udara / Cuaca</h3>
                    <p class="text-2xl font-black text-slate-800 dark:text-white leading-tight">
                        <span id="display-humidity">{{ $humidity_api ?? '--' }}</span>%
                        <span id="display-cuaca"
                            class="text-sm text-slate-400 block font-bold uppercase tracking-tighter">{{ $cuaca ?? '--' }}</span>
                    </p>
                </div>
            </div>
        </div>

        {{-- BMKG Data Attribution (Kewajiban Penggunaan API) --}}
        <div class="flex justify-end mt-2">
            <p class="text-xs text-slate-400 dark:text-slate-500">
                * Sumber data cuaca: <a href="https://data.bmkg.go.id" target="_blank"
                    class="text-orange-500 hover:text-orange-600 transition-colors font-semibold">BMKG (Badan Meteorologi,
                    Klimatologi, dan Geofisika)</a>
            </p>
        </div>
    </div>

    {{-- Location Modal --}}
    <div id="locationModal"
        class="{{ $showModal ? '' : 'hidden' }} fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm transition-all">
        <div
            class="bg-white dark:bg-slate-900 w-full max-w-md rounded-[2.5rem] p-8 shadow-2xl border border-slate-100 dark:border-slate-800 relative">

            {{-- Tombol Tutup --}}
            <button onclick="document.getElementById('locationModal').classList.add('hidden')"
                class="absolute top-6 right-6 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg>
            </button>

            <div class="text-center mb-6">
                <div
                    class="bg-orange-100 dark:bg-orange-500/20 w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-orange-500 animate-bounce" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                    </svg>
                </div>
                <h2 class="text-xl font-black text-slate-800 dark:text-white">Sesuaikan Lokasi Lahan</h2>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-2">Data BMKG akan disesuaikan otomatis berdasarkan
                    wilayah kebun Anda.</p>
            </div>

            <form id="locationForm" class="space-y-4" onsubmit="event.preventDefault(); updateLocation();">
                <div class="relative">
                    <select id="selectLocation" name="location" required
                        class="block w-full appearance-none bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-200 py-4 px-5 pr-10 rounded-2xl focus:outline-none focus:ring-2 focus:ring-orange-500 transition-all font-bold">
                        @foreach ($locations as $id => $name)
                            <option value="{{ $id }}" {{ $selectedLocation == $id ? 'selected' : '' }}>
                                📍 {{ $name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" id="btnConfirm"
                    class="w-full bg-orange-500 text-white font-bold py-4 rounded-2xl shadow-lg shadow-orange-500/30 hover:bg-orange-600 transition-all">
                    Konfirmasi Lokasi
                </button>
            </form>
        </div>
    </div>

    <script>
        async function updateLocation() {
            const locationId = document.getElementById('selectLocation').value;
            const btn = document.getElementById('btnConfirm');

            // Status Loading
            btn.disabled = true;
            btn.innerHTML =
                `<svg class="animate-spin h-5 w-5 mx-auto text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>`;

            try {
                const response = await fetch(`/dashboard?location=${locationId}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });

                if (!response.ok) {
                    throw new Error(`Server Error: ${response.status}`);
                }

                const data = await response.json();

                // 1. Update Metrics (Angka Utama)
                if (data.temp !== undefined) document.getElementById('display-temp').innerText = data.temp;
                if (data.humidity !== undefined) document.getElementById('display-humidity').innerText = data.humidity;
                if (data.condition) document.getElementById('display-cuaca').innerText = data.condition;

                // 2. Update Label Area (Header & Tombol)
                if (data.area) {
                    document.getElementById('header-area-btn').innerText = data.area;
                    if (document.getElementById('label-area')) {
                        document.getElementById('label-area').innerText = data.area;
                    }
                }

                // 3. Update Teks di dalam Card Rekomendasi LLM (Agar Sinkron)
                if (document.getElementById('card-area')) document.getElementById('card-area').innerText = data.area;
                if (document.getElementById('card-temp')) document.getElementById('card-temp').innerText = data.temp;
                if (document.getElementById('card-cuaca')) document.getElementById('card-cuaca').innerText = data
                    .condition;

                // Tutup Modal
                document.getElementById('locationModal').classList.add('hidden');

            } catch (error) {
                console.error("Detail Error:", error);
                alert("Gagal memperbarui data cuaca. Periksa koneksi atau log server.");
            } finally {
                btn.disabled = false;
                btn.innerText = "Konfirmasi Lokasi";
            }
        }
    </script>
@endsection
