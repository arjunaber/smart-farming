@extends('layouts.app')

@section('content')
    {{-- Inisialisasi Alpine.js untuk mengatur loading selama 3 detik --}}
    <div x-data="loadingScreen" class="relative min-h-screen">
        <div :class="{ 'blur-2xl opacity-50 pointer-events-none': isLoading }"
            class="space-y-6 transition-all duration-700 ease-out">

            {{-- Header Section --}}
            <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-4">
                <div>
                    <h1 class="text-2xl font-black text-slate-800 dark:text-white uppercase tracking-tight">Tinjauan Lahan
                    </h1>
                    <p class="text-slate-500 dark:text-slate-400 text-sm mt-1 flex items-center gap-2">
                        <span class="inline-block w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                        Wilayah: <span class="text-slate-800 dark:text-slate-200 font-bold">{{ $area }}</span>
                    </p>
                </div>

                {{-- Tombol Ganti Lahan --}}
                <button type="button" onclick="document.getElementById('lahanModal').classList.remove('hidden')"
                    class="group bg-white dark:bg-slate-800 border-2 border-slate-100 dark:border-slate-700 p-1.5 pr-5 rounded-2xl flex items-center gap-3 shadow-sm hover:border-green-500 transition-all active:scale-95">
                    <div class="bg-green-500 text-white p-2 rounded-xl shadow-lg shadow-green-500/30">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16m-7 6h7"></path>
                        </svg>
                    </div>
                    <div class="text-left">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider leading-none">Lahan Terpilih
                        </p>
                        <p class="text-sm font-black text-slate-700 dark:text-white leading-tight">
                            {{ $lahan->nama_lahan ?? 'Pilih Lahan' }}
                        </p>
                    </div>
                </button>
            </div>

            {{-- Widget IoT Utama & Map --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                {{-- Card pH Tanah --}}
                <div
                    class="bg-gradient-to-br from-indigo-600 to-blue-700 rounded-[2.5rem] p-8 text-white shadow-xl relative overflow-hidden group">
                    <div class="relative z-10">
                        <p class="text-xs font-bold opacity-80 uppercase tracking-widest mb-2">pH Tanah (Real-time)</p>
                        <div class="flex items-baseline gap-3">
                            <h2 class="text-6xl font-black tracking-tighter">{{ $soil_ph }}</h2>
                            <span
                                class="bg-white/20 px-3 py-1 rounded-full text-[10px] font-bold backdrop-blur-md uppercase">
                                @if (is_numeric($soil_ph))
                                    {{ $soil_ph >= 5.5 && $soil_ph <= 7.5 ? 'Optimal' : 'Perlu Penyesuaian' }}
                                @else
                                    N/A
                                @endif
                            </span>
                        </div>
                        <div class="mt-6 flex items-center gap-2 text-xs opacity-70">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                            Update: {{ $last_update }}
                        </div>
                    </div>
                    <div
                        class="absolute -right-4 -bottom-4 text-9xl opacity-10 group-hover:scale-110 transition-transform duration-500">
                        🧪</div>
                </div>

                {{-- Card Kelembapan Tanah --}}
                <div
                    class="bg-gradient-to-br from-emerald-600 to-teal-700 rounded-[2.5rem] p-8 text-white shadow-xl relative overflow-hidden group">
                    <div class="relative z-10">
                        <p class="text-xs font-bold opacity-80 uppercase tracking-widest mb-2">Kelembapan Tanah</p>
                        <div class="flex items-baseline gap-3">
                            <h2 class="text-6xl font-black tracking-tighter">{{ $soil_moist }}</h2>
                            <span
                                class="bg-black/10 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider">%</span>
                        </div>
                        <div class="mt-6 flex items-center gap-2 text-xs opacity-70">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M13 10V3L4 14h7v7l9-11h-7z" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round"></path>
                            </svg>
                            Sensor ESP32 Aktif
                        </div>
                    </div>
                    <div
                        class="absolute -right-4 -bottom-4 text-9xl opacity-10 group-hover:scale-110 transition-transform duration-500">
                        💧</div>
                </div>

                {{-- Visualisasi Peta Polygon --}}
                <div onclick="openMapModal()"
                    class="bg-white dark:bg-slate-900 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 p-4 shadow-sm overflow-hidden cursor-pointer hover:border-blue-500 transition-all group/map">
                    <div class="flex justify-between items-center mb-3 px-2">
                        <h3
                            class="text-[10px] font-black text-slate-400 group-hover/map:text-blue-500 transition-colors uppercase tracking-widest">
                            Peta Area Lahan</h3>
                        <span
                            class="text-[10px] bg-blue-100 text-blue-600 dark:bg-blue-500/20 px-2 py-0.5 rounded-lg font-bold group-hover/map:bg-blue-500 group-hover/map:text-white transition-all">KLIK
                            UNTUK EDIT</span>
                    </div>
                    <div id="mapPolygon"
                        class="h-48 w-full rounded-[1.5rem] z-0 border border-slate-100 dark:border-slate-800 pointer-events-none">
                    </div>
                </div>
            </div>

            {{-- Row Info Komoditas & Rekomendasi --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                {{-- Status Komoditas --}}
                <div
                    class="bg-white dark:bg-slate-900 rounded-[2rem] border border-slate-100 dark:border-slate-800 p-6 flex items-center gap-5 shadow-sm">
                    <div class="bg-orange-100 dark:bg-orange-500/20 p-4 rounded-3xl text-3xl">🌾</div>
                    <div>
                        <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Komoditas Utama
                        </h3>
                        <p class="text-lg font-black text-slate-800 dark:text-white leading-none mb-2">
                            {{ $lahan->komoditas->nama_komoditas ?? 'N/A' }}
                        </p>
                        <span
                            class="text-[10px] bg-orange-100 text-orange-700 dark:bg-orange-500/30 dark:text-orange-300 px-2 py-1 rounded-lg font-black uppercase tracking-tighter">Monitoring
                            Aktif</span>
                    </div>
                </div>

                {{-- Rekomendasi Tindakan --}}
                <div
                    class="lg:col-span-2 bg-slate-900 dark:bg-green-950 rounded-[2rem] p-6 text-white flex items-center gap-6 shadow-lg relative overflow-hidden">
                    <div class="hidden sm:block bg-white/10 p-4 rounded-2xl backdrop-blur-md">
                        <svg class="w-8 h-8 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <div class="relative z-10">
                        <h3 class="text-sm font-bold text-green-400 uppercase tracking-widest mb-1 italic">Rekomendasi AGA
                            System</h3>
                        <p class="text-sm opacity-90 leading-relaxed font-medium">
                            Berdasarkan suhu udara <b>{{ $temp }}°C</b> dan pH <b>{{ $soil_ph }}</b>,
                            optimalkan irigasi untuk menjaga pertumbuhan komoditas
                            <b>{{ $lahan->komoditas->nama_komoditas ?? 'tanaman' }}</b> di wilayah
                            <b>{{ $area }}</b>.
                        </p>
                    </div>
                </div>
            </div>

            {{-- Weather & Area Widgets --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @php
                    $widgets = [
                        ['Suhu Udara', $temp . '°C', '🌡️'],
                        ['Kelembapan', $humidity . '%', '☁️'],
                        ['Kondisi', $condition, '🌤️'],
                        ['Luas Lahan', number_format($luas_lahan, 0, ',', '.') . ' m²', '📐'],
                    ];
                @endphp

                @foreach ($widgets as $item)
                    <div
                        class="bg-white dark:bg-slate-900 p-6 rounded-[2rem] border border-slate-100 dark:border-slate-800 shadow-sm hover:shadow-md transition-shadow">
                        <div class="flex justify-between items-start mb-3">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ $item[0] }}
                            </p>
                            <span class="text-lg">{{ $item[2] }}</span>
                        </div>
                        <p class="text-xl font-black text-slate-800 dark:text-white">{{ $item[1] }}</p>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- 2. INDIKATOR LOADING SCREEN (Menggunakan Lottie Animation, Otomatis Hilang setelah 3 detik) --}}
        <div x-show="isLoading" x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
            class="absolute inset-0 z-[9999] flex flex-col items-center justify-center min-h-[400px]">

            {{-- Card Loader dengan Lottie Player --}}
            <div
                class="flex flex-col items-center p-6 rounded-[2.5rem] bg-white/80 dark:bg-slate-900/80 shadow-xl border border-slate-100 dark:border-slate-800 backdrop-blur-md max-w-xs text-center">
                <div class="w-32 h-32 mb-2">
                    <dotlottie-player src="{{ asset('loading.lottie') }}" background="transparent" speed="1" loop
                        autoplay>
                    </dotlottie-player>
                </div>
                <p class="text-xs font-black text-slate-700 dark:text-white uppercase tracking-widest animate-pulse">
                    Sinkronisasi Data AGA...
                </p>
            </div>
        </div>
    </div>
@endsection

@push('modals')
    {{-- Modal Pilih Lahan --}}
    <div id="lahanModal" class="hidden fixed inset-0 w-screen h-screen transition-all duration-300" style="z-index: 99999;">
        <div class="absolute inset-0 bg-slate-950/60 backdrop-blur-sm"
            onclick="document.getElementById('lahanModal').classList.add('hidden')"></div>
        <div class="relative flex items-center justify-center min-h-screen p-4 pointer-events-none">
            <div
                class="bg-white dark:bg-slate-900 rounded-[3rem] w-full max-w-md shadow-2xl border border-slate-200 dark:border-slate-800 pointer-events-auto overflow-hidden animate-in fade-in zoom-in duration-200">
                <div class="p-8">
                    <div class="flex justify-center mb-6">
                        <div
                            class="w-20 h-20 bg-green-100 dark:bg-green-500/20 rounded-[2rem] flex items-center justify-center shadow-inner">
                            <span class="text-4xl">🪴</span>
                        </div>
                    </div>
                    <div class="text-center mb-8">
                        <h3 class="text-2xl font-black text-slate-800 dark:text-white">Pilih Lahan</h3>
                        <p class="text-slate-500 dark:text-slate-400 text-sm">Ganti perspektif monitoring Anda.</p>
                    </div>
                    <div class="space-y-3 max-h-[300px] overflow-y-auto pr-2 custom-scrollbar">
                        @forelse($userLahan as $l)
                            <a href="?lahan_id={{ $l->id }}"
                                class="flex items-center justify-between p-4 rounded-3xl border-2 transition-all group {{ optional($lahan)->id == $l->id ? 'border-green-500 bg-green-50 dark:bg-green-500/10' : 'border-slate-100 dark:border-slate-800 hover:border-green-200 dark:hover:border-green-900' }}">
                                <div class="flex items-center gap-4">
                                    <div
                                        class="w-10 h-10 rounded-2xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-lg">
                                        📍</div>
                                    <div class="text-left">
                                        <p
                                            class="font-bold text-slate-800 dark:text-white group-hover:text-green-600 transition-colors">
                                            {{ $l->nama_lahan }}</p>
                                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                                            {{ $l->komoditas->nama_komoditas ?? 'N/A' }}</p>
                                    </div>
                                </div>
                                @if (optional($lahan)->id == $l->id)
                                    <div class="bg-green-500 rounded-full p-1.5 text-white shadow-lg shadow-green-500/40">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="4"
                                                d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>
                                @endif
                            </a>
                        @empty
                            <div class="text-center py-6">
                                <p class="text-slate-400 italic text-sm">Belum ada data lahan.</p>
                            </div>
                        @endforelse
                    </div>
                    <button onclick="document.getElementById('lahanModal').classList.add('hidden')"
                        class="mt-8 w-full py-4 rounded-2xl bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 font-bold hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Gambar/Edit Polygon Lahan --}}
    <div id="mapModal" class="hidden fixed inset-0 w-screen h-screen transition-all duration-300"
        style="z-index: 99999;">
        <div class="absolute inset-0 bg-slate-950/60 backdrop-blur-sm" onclick="closeMapModal()"></div>
        <div class="relative flex items-center justify-center min-h-screen p-4 pointer-events-none">
            <div
                class="bg-white dark:bg-slate-900 rounded-[2.5rem] w-full max-w-2xl shadow-2xl border border-slate-200 dark:border-slate-800 pointer-events-auto overflow-hidden flex flex-col">

                {{-- HEADER MODAL (Rapi tanpa tombol IoT) --}}
                <div class="p-6 border-b border-slate-100 dark:border-slate-800 flex justify-between items-start">
                    <div>
                        <h3 class="text-lg font-black text-slate-800 dark:text-white">Atur Koordinat Lahan</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Klik minimal 3 titik di peta untuk
                            membentuk area lahan.</p>
                    </div>

                    <button type="button" onclick="clearDraftPolygon()"
                        class="text-xs font-bold text-red-500 hover:text-red-600 bg-red-50 dark:bg-red-500/10 px-3 py-1.5 rounded-xl transition-colors shrink-0">
                        Reset Titik
                    </button>
                </div>

                {{-- Container Peta di Dalam Modal --}}
                <div class="relative flex-1">
                    <div id="mapDraw" class="h-96 w-full z-0"></div>
                    <div
                        class="absolute bottom-4 left-4 z-[1000] bg-white/90 dark:bg-slate-900/90 backdrop-blur px-3 py-1.5 rounded-xl text-[10px] font-bold text-slate-700 dark:text-slate-300 border border-slate-100 dark:border-slate-800 shadow-sm">
                        <span id="pointCounter">0</span> Titik Terpilih
                    </div>
                </div>

                {{-- FOOTER MODAL (Tombol IoT Kiri Bawah, Aksi Kanan Bawah) --}}
                <div
                    class="p-6 bg-slate-50 dark:bg-slate-800/30 flex flex-col sm:flex-row gap-4 justify-between items-center border-t border-slate-100 dark:border-slate-800">

                    {{-- TOMBOL IOT DI KIRI BAWAH --}}
                    <a href="{{ url('/iot-device/create' . (isset($lahan) ? '?lahan_id=' . $lahan->id : '')) }}"
                        class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-4 py-3 bg-blue-500 hover:bg-blue-600 text-white text-xs font-bold rounded-xl shadow-md shadow-blue-500/20 transition-all uppercase tracking-wider">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z">
                            </path>
                        </svg>
                        + Tambah Alat IoT
                    </a>

                    {{-- TOMBOL BATAL & SIMPAN DI KANAN BAWAH --}}
                    <div class="flex gap-3 w-full sm:w-auto">
                        <button type="button" onclick="closeMapModal()"
                            class="flex-1 sm:flex-none px-5 py-3 rounded-xl bg-slate-200 dark:bg-slate-800 text-slate-600 dark:text-slate-300 font-bold text-sm hover:bg-slate-300 dark:hover:bg-slate-700 transition-colors">
                            Batal
                        </button>
                        <form id="savePolygonForm" action="/lahan/{{ $lahan->id ?? '' }}/update-polygon" method="POST"
                            class="flex-1 sm:flex-none">
                            @csrf
                            @method('PUT')
                            {{-- Input hidden untuk menampung json koordinat --}}
                            <input type="hidden" name="polygon_coordinates" id="polygonCoordinatesInput">
                            <button type="submit" id="btnSavePolygon" disabled
                                class="w-full px-5 py-3 rounded-xl bg-green-500 text-white font-bold text-sm hover:bg-green-600 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                                Simpan Area
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- Modal Peringatan IoT Belum Sinkron --}}
    @if (isset($needsSync) && $needsSync)
        <div x-data="{ open: true }" x-show="open" class="relative z-50" style="z-index: 100000;">
            <div x-show="open" class="fixed inset-0 bg-slate-900/75 backdrop-blur-sm transition-opacity"></div>
            <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
                <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                    <div x-show="open"
                        class="relative transform overflow-hidden rounded-2xl bg-white dark:bg-slate-900 text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-slate-100 dark:border-slate-800">
                        <div class="bg-white dark:bg-slate-900 px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div
                                    class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-amber-100 dark:bg-amber-500/20 sm:mx-0 sm:h-10 sm:w-10">
                                    <svg class="h-6 w-6 text-amber-600 dark:text-amber-500" fill="none"
                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                </div>
                                <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                                    <h3 class="text-lg font-bold leading-6 text-slate-900 dark:text-white">Perangkat IoT
                                        Belum Terdeteksi</h3>
                                    <div class="mt-2">bg-gradient-to-br from-indigo-600 to-blue-700 rounded-[2.5rem] p-8
                                        text-white shadow-xl relative overflow-hidden group
                                        <p class="text-sm text-slate-500 dark:text-slate-400">Harap hubungi <b>Admin</b>
                                            untuk sinkronisasi ESP32 ke lahan <b>{{ $lahan->nama_lahan }}</b> agar data
                                            sensor muncul.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-slate-50 dark:bg-slate-800/50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                            <button @click="open = false" type="button"
                                class="mt-3 inline-flex w-full justify-center rounded-xl bg-white dark:bg-slate-800 px-4 py-2 text-sm font-semibold text-slate-900 dark:text-white shadow-sm ring-1 ring-slate-300 dark:ring-slate-700 hover:bg-slate-50 sm:mt-0 sm:w-auto">Saya
                                Mengerti</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endpush

@push('scripts')
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('loadingScreen', () => ({
                isLoading: true,
                init() {
                    // Timeout akan berjalan aman di sini
                    setTimeout(() => {
                        this.isLoading = false;
                    }, 2000);
                }
            }))
        })
    </script>
    <script>
        let mainMap, drawMap;
        let draftCoordinates = [];
        let draftMarkers = [];
        let draftPolygonLayer = null;

        let rawCoords = @json($lahan->polygon_coordinates ?? []);

        if (typeof rawCoords === 'string' && rawCoords.trim() !== '') {
            try {
                rawCoords = JSON.parse(rawCoords);
            } catch (e) {
                rawCoords = [];
            }
        }
        const initialCoords = Array.isArray(rawCoords) ? rawCoords : [];

        // ─── Inisialisasi Peta Kecil ───────────────────────────────────────────────
        document.addEventListener('DOMContentLoaded', function() {
            const mapContainer = document.getElementById('mapPolygon');

            if (initialCoords && initialCoords.length > 0) {
                mainMap = L.map('mapPolygon', {
                    zoomControl: false,
                    attributionControl: false,
                    dragging: false,
                    scrollWheelZoom: false,
                });

                L.tileLayer('https://{s}.google.com/vt/lyrs=s,h&x={x}&y={y}&z={z}', {
                    maxZoom: 20,
                    subdomains: ['mt0', 'mt1', 'mt2', 'mt3'],
                }).addTo(mainMap);

                const polygon = L.polygon(initialCoords, {
                    color: '#22c55e',
                    fillColor: '#22c55e',
                    fillOpacity: 0.35,
                    weight: 3,
                }).addTo(mainMap);

                mainMap.fitBounds(polygon.getBounds());
            } else {
                mapContainer.classList.add('flex', 'items-center', 'justify-center',
                    'bg-slate-50', 'dark:bg-slate-800');
                mapContainer.innerHTML = `
                <div class="text-center">
                    <span class="block text-2xl mb-1">🗺️</span>
                    <span class="text-[10px] text-slate-400 font-bold uppercase">
                        Koordinat Belum Diatur<br>
                        <span class="text-blue-500 text-[9px]">Klik untuk atur</span>
                    </span>
                </div>`;
            }
        });

        // ─── Helpers Internal ──────────────────────────────────────────────────────
        function clearDraftLayersOnly() {
            if (!drawMap) return;
            draftMarkers.forEach(marker => drawMap.removeLayer(marker));
            draftMarkers = [];
            if (draftPolygonLayer) {
                drawMap.removeLayer(draftPolygonLayer);
                draftPolygonLayer = null;
            }
        }

        function refreshDrawLayers() {
            clearDraftLayersOnly();

            if (!Array.isArray(draftCoordinates)) {
                draftCoordinates = [];
            }

            draftCoordinates.forEach(coord => {
                const marker = L.circleMarker(coord, {
                    radius: 6,
                    color: '#ef4444',
                    fillColor: '#ef4444',
                    fillOpacity: 1,
                    zIndexOffset: 1000,
                }).addTo(drawMap);
                draftMarkers.push(marker);
            });

            if (draftCoordinates.length >= 2) {
                draftPolygonLayer = L.polygon(draftCoordinates, {
                    color: '#ef4444',
                    fillColor: '#ef4444',
                    fillOpacity: 0.2,
                    weight: 2,
                }).addTo(drawMap);
            }

            updateFormValidation();
        }

        function updateFormValidation() {
            const total = Array.isArray(draftCoordinates) ? draftCoordinates.length : 0;
            document.getElementById('pointCounter').innerText = total;

            const btnSave = document.getElementById('btnSavePolygon');
            const hiddenInput = document.getElementById('polygonCoordinatesInput');

            if (total >= 3) {
                btnSave.disabled = false;
                hiddenInput.value = JSON.stringify(draftCoordinates);
            } else {
                btnSave.disabled = true;
                hiddenInput.value = '';
            }
        }

        // ─── Fungsi Global ─────────────────────────────────────────────────────────
        window.openMapModal = function() {
            document.getElementById('mapModal').classList.remove('hidden');

            draftCoordinates = JSON.parse(JSON.stringify(initialCoords));
            if (!Array.isArray(draftCoordinates)) {
                draftCoordinates = [];
            }

            if (!drawMap) {
                drawMap = L.map('mapDraw', {
                    attributionControl: false
                });

                L.tileLayer('https://{s}.google.com/vt/lyrs=s,h&x={x}&y={y}&z={z}', {
                    maxZoom: 20,
                    subdomains: ['mt0', 'mt1', 'mt2', 'mt3'],
                }).addTo(drawMap);

                drawMap.on('click', function(e) {
                    if (!Array.isArray(draftCoordinates)) draftCoordinates = [];
                    draftCoordinates.push([e.latlng.lat, e.latlng.lng]);
                    refreshDrawLayers();
                });
            }

            setTimeout(() => {
                drawMap.invalidateSize();

                if (draftCoordinates && draftCoordinates.length > 0) {
                    refreshDrawLayers();
                    drawMap.fitBounds(L.polygon(draftCoordinates).getBounds());
                } else {
                    refreshDrawLayers();
                    drawMap.setView([-2.5489, 118.0149], 5); // Default view
                }
            }, 200);
        };

        window.closeMapModal = function() {
            document.getElementById('mapModal').classList.add('hidden');
            clearDraftLayersOnly();
        };

        window.clearDraftPolygon = function() {
            draftCoordinates = [];
            clearDraftLayersOnly();
            updateFormValidation();
        };
    </script>
@endpush

<style>
    .custom-scrollbar::-webkit-scrollbar {
        width: 4px;
    }

    .custom-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #e2e8f0;
        border-radius: 10px;
    }

    .dark .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #334155;
    }

    /* Leaflet Popup Styling */
    .leaflet-popup-content-wrapper {
        border-radius: 1.25rem;
        padding: 4px;
        box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1);
    }

    .leaflet-popup-tip {
        background: white;
    }

    .leaflet-container {
        font-family: inherit;
    }
</style>
