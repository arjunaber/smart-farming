@extends('layouts.app')

{{-- 1. Tambahkan CSS Leaflet, Leaflet.Editable, dan Control Geocoder untuk pencarian jalan --}}
@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
    <style>
        .leaflet-container {
            z-index: 10 !important;
        }

        .leaflet-vertex-icon {
            background: #ffffff !important;
            border: 2px solid #22c55e !important;
            border-radius: 50% !important;
        }

        /* PERBAIKAN TOTAL UNTUK BOX PENCARIAN TRANSPARAN & TUMPANG TINDIH */
        .leaflet-control-geocoder {
            background-color: #ffffff !important;
            border-radius: 12px !important;
            border: 2px solid #22c55e !important;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15) !important;
            z-index: 1000 !important;
            overflow: hidden;
            max-width: 300px !important;
        }

        /* Memastikan form input pencarian terlihat jelas */
        .leaflet-control-geocoder-form {
            display: block !important;
            background-color: #ffffff !important;
            padding: 4px 8px !important;
        }

        .leaflet-control-geocoder-form input {
            color: #1e293b !important;
            background-color: #ffffff !important;
            font-size: 13px !important;
            font-weight: 700 !important;
            border: none !important;
            outline: none !important;
            width: 220px !important;
        }

        .leaflet-control-geocoder-throbber {
            background-color: #ffffff !important;
        }

        .leaflet-control-geocoder-results {
            background-color: #ffffff !important;
            color: #1e293b !important;
            border-top: 1px solid #e2e8f0 !important;
            font-size: 12px !important;
            font-weight: 600 !important;
            max-height: 200px;
            overflow-y: auto;
        }

        .leaflet-control-geocoder-results li {
            padding: 6px 10px !important;
            background-color: #ffffff !important;
            border-bottom: 1px solid #f1f5f9 !important;
        }

        .leaflet-control-geocoder-results li:hover {
            background-color: #f0fdf4 !important;
            cursor: pointer;
        }
    </style>
@endpush

@section('content')
    <div class="max-w-3xl mx-auto px-4 py-6">
        <div
            class="bg-white dark:bg-slate-900 rounded-[2.5rem] shadow-2xl p-6 md:p-10 border border-slate-100 dark:border-slate-800">

            {{-- Header --}}
            <div class="flex items-center gap-4 mb-10">
                <a href="{{ route('lahan.show', $lahan) }}"
                    class="p-3 bg-slate-50 dark:bg-slate-800 rounded-2xl text-slate-400 hover:text-green-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </a>
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1">Manajemen Lahan</p>
                    <h1 class="text-2xl font-black text-slate-800 dark:text-white uppercase">Edit Data Lahan</h1>
                </div>
            </div>

            <form method="POST" action="{{ route('lahan.update', $lahan) }}" class="space-y-8">
                @csrf
                @method('PUT')

                {{-- Input Hidden untuk Menyimpan Koordinat ke Database --}}
                <input type="hidden" name="polygon_coordinates" id="polygon_coordinates"
                    value="{{ old('polygon_coordinates', is_array($lahan->polygon_coordinates) ? json_encode($lahan->polygon_coordinates) : $lahan->polygon_coordinates) }}">

                <div class="space-y-6">
                    {{-- Nama Lahan --}}
                    <div class="group">
                        <label
                            class="block text-xs font-black text-slate-500 mb-2 uppercase tracking-widest group-focus-within:text-green-600 transition-colors">
                            Nama Identitas Lahan
                        </label>
                        <input type="text" name="nama_lahan" value="{{ old('nama_lahan', $lahan->nama_lahan) }}"
                            class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-2xl px-5 py-4 focus:ring-4 focus:ring-green-500/10 focus:border-green-500 outline-none transition-all font-bold"
                            placeholder="Contoh: Blok Sawah Utara" required>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Lokasi Wilayah --}}
                        <div>
                            <label class="block text-xs font-black text-slate-400 uppercase tracking-[0.2em] mb-3">
                                Lokasi Wilayah (BMKG)
                            </label>
                            <select name="lokasi" id="lokasi_select"
                                class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-2xl px-5 py-4 text-sm font-bold focus:ring-4 focus:ring-green-500/10 focus:border-green-500 outline-none appearance-none cursor-pointer transition-all"
                                required>
                                <option value="">Pilih Wilayah Monitoring</option>
                                @foreach ($lokasiList as $id => $nama)
                                    <option value="{{ $id }}"
                                        {{ (string) old('lokasi', $lahan->lokasi ?? '') === (string) $id || old('lokasi', $lahan->lokasi ?? '') === $nama ? 'selected' : '' }}>
                                        {{ $nama }}
                                    </option>
                                @endforeach
                            </select>
                            @error('lokasi')
                                <p class="text-red-500 text-xs mt-2 font-bold">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Luas Hektar --}}
                        <div>
                            <label class="block text-xs font-black text-slate-500 mb-2 uppercase tracking-widest">
                                Luas (Hektar)
                            </label>
                            <div>
                                <input type="number" step="0.01" name="luas" id="luas_input"
                                    value="{{ old('luas', $lahan->luas) }}"
                                    class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-2xl px-5 py-4 text-sm font-bold focus:border-green-500 outline-none">
                            </div>
                            @error('luas')
                                <p class="text-red-500 text-xs mt-2 font-bold">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Komoditas Utama --}}
                    <div>
                        <label class="block text-xs font-black text-slate-500 mb-2 uppercase tracking-widest">
                            Komoditas Utama
                        </label>
                        <select name="komoditas_id"
                            class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-2xl px-5 py-4 focus:ring-4 focus:ring-green-500/10 focus:border-green-500 outline-none transition-all font-bold appearance-none"
                            required>
                            <option value="">Pilih Komoditas</option>
                            @foreach ($komoditasList as $komoditas)
                                <option value="{{ $komoditas->id }}"
                                    {{ old('komoditas_id', $lahan->komoditas_id) == $komoditas->id ? 'selected' : '' }}>
                                    {{ $komoditas->nama_komoditas }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Status Lahan --}}
                    <div>
                        <label class="block text-xs font-black text-slate-500 mb-2 uppercase tracking-widest">
                            Status Operasional
                        </label>
                        <select name="status"
                            class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-2xl px-5 py-4 focus:ring-4 focus:ring-green-500/10 focus:border-green-500 outline-none transition-all font-bold appearance-none">
                            <option value="Aktif" {{ old('status', $lahan->status) == 'Aktif' ? 'selected' : '' }}>🟢
                                Aktif / Produktif</option>
                            <option value="Istirahat" {{ old('status', $lahan->status) == 'Istirahat' ? 'selected' : '' }}>
                                💡 Istirahat Lahan</option>
                            <option value="Persiapan" {{ old('status', $lahan->status) == 'Persiapan' ? 'selected' : '' }}>
                                🔵 Persiapan Tanam</option>
                        </select>
                    </div>

                    {{-- Peta Gambar Wilayah Poligon --}}
                    <div>
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-2">
                            <label class="block text-xs font-black text-slate-500 uppercase tracking-widest">
                                Pemetaan Batas Geospasial Lahan
                            </label>
                            <span id="mapStatusHint"
                                class="text-[10px] font-bold text-amber-500 bg-amber-500/10 px-2 py-0.5 rounded-md">
                                Klik pada peta untuk mulai membuat area lahan
                            </span>
                        </div>

                        {{-- Container Peta --}}
                        <div id="mapFormEditor"
                            class="w-full h-80 rounded-2xl overflow-hidden shadow-inner bg-slate-50 border border-slate-200 dark:border-slate-700 relative">
                        </div>

                        <div class="flex items-center justify-between mt-2">
                            <p class="text-[10px] font-medium text-slate-400">
                                * Gunakan ikon 🔍 di peta untuk mencari nama jalan. Geser titik untuk merubah area lahan.
                            </p>
                            <button type="button" id="btnClearMap"
                                class="text-[10px] font-black text-red-500 uppercase hover:underline">
                                🗑️ Reset Poligon
                            </button>
                        </div>
                    </div>

                    {{-- Deskripsi --}}
                    <div>
                        <label class="block text-xs font-black text-slate-500 mb-2 uppercase tracking-widest">
                            Catatan Tambahan
                        </label>
                        <textarea name="deskripsi" rows="3"
                            class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-2xl px-5 py-4 focus:ring-4 focus:ring-green-500/10 focus:border-green-500 outline-none transition-all font-medium italic"
                            placeholder="Tambahkan catatan mengenai kondisi tanah atau irigasi...">{{ old('deskripsi', $lahan->deskripsi) }}</textarea>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="flex flex-col sm:flex-row gap-4 pt-4">
                    <button type="submit"
                        class="flex-1 bg-green-600 hover:bg-green-700 text-white font-black py-5 rounded-2xl transition-all shadow-xl shadow-green-600/20 active:scale-[0.98] uppercase tracking-widest text-sm">
                        Simpan Perubahan
                    </button>
                    <a href="{{ route('lahan.show', $lahan) }}"
                        class="flex-1 text-center bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 font-black py-5 rounded-2xl transition-all uppercase tracking-widest text-sm">
                        Batal
                    </a>
                </div>
            </form>
        </div>

        {{-- Footer Info --}}
        <p class="mt-8 text-center text-[10px] font-black text-slate-400 uppercase tracking-[0.3em]">
            AGA Smart Farming System • {{ date('Y') }}
        </p>
    </div>
@endsection

@push('scripts')
    {{-- Import Leaflet Core, Editable, Geocoder pencarian jalan, & Turf.js --}}
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-editable@1.2.0/src/Leaflet.Editable.js"></script>
    <script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@turf/turf@6/turf.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const inputCoords = document.getElementById('polygon_coordinates');
            const mapStatusHint = document.getElementById('mapStatusHint');
            const btnClear = document.getElementById('btnClearMap');
            const lokasiSelect = document.getElementById('lokasi_select');
            const inputLuas = document.getElementById('luas_input');

            // --- DATABASE KOORDINAT WILAYAH BMKG ---
            const daftarPusatWilayah = {
                '32.73.12.1001': [-6.9126, 107.6166],
                '32.73.05.1001': [-6.9174, 107.6624],
                '32.73.20.1001': [-6.9142, 107.6766],
                '32.73.01.1001': [-6.9181, 107.5794],
                '32.73.08.1001': [-6.9324, 107.6014],
                '32.73.04.1001': [-6.9427, 107.5746],
                '32.73.22.1001': [-6.9463, 107.6258],
                '32.73.03.1001': [-6.9248, 107.5592],
                '32.73.13.1001': [-6.9079, 107.6186],
                '32.73.11.1001': [-6.9298, 107.6366],
                '32.73.09.1001': [-6.9348, 107.5898],
                '32.73.10.1001': [-6.9538, 107.5962],
                '32.73.23.1001': [-6.9460, 107.6499],
                '32.73.15.1001': [-6.8994, 107.6318],
                '32.73.14.1001': [-6.9095, 107.6402],
                '32.73.25.1001': [-6.9344, 107.7127],
                '32.73.02.1001': [-6.9038, 107.5962],
                '32.73.18.1001': [-6.8722, 107.6044],
                '32.73.29.1001': [-6.9284, 107.6898],
                '32.73.16.1001': [-6.8872, 107.6166],
                '32.73.26.1001': [-6.9495, 107.6922],
                '32.73.19.1001': [-6.9248, 107.6499],
                '32.73.17.1001': [-6.9312, 107.6186],
                '32.73.30.1001': [-6.9056, 107.6688],
                '32.73.28.1001': [-6.9366, 107.7276],
                '32.73.24.1001': [-6.9582, 107.6666],
                '32.73.07.1001': [-6.9372, 107.6099],
                '32.73.06.1001': [-6.8942, 107.5962],
                '32.73.21.1001': [-6.8748, 107.5878],
                '32.73.27.1001': [-6.9168, 107.7012],
                '32.04.13.2001': [-6.9744, 107.6305],
                '32.04.14.1001': [-6.9944, 107.5305],
                '32.04.12.1001': [-7.0084, 107.6202],
                '32.04.34.2001': [-7.0256, 107.5186],
                '32.04.16.2001': [-7.0544, 107.5866],
                '32.04.28.2001': [-6.9388, 107.7466],
                '32.04.29.1001': [-6.8788, 107.6602],
                '32.04.09.2001': [-7.1722, 107.5688],
                '32.04.30.2001': [-6.8924, 107.6988],
                '32.04.27.2001': [-6.9682, 107.7724],
                '32.04.32.2001': [-7.0468, 107.7522],
                '32.04.11.2001': [-7.0244, 107.6888],
                '32.17.03.2001': [-6.8222, 107.6186],
                '32.17.01.2001': [-6.8524, 107.4922],
                '32.17.02.2001': [-6.8388, 107.4724],
                '32.17.13.2001': [-6.8166, 107.5833],
                '32.75.01.1001': [-6.2344, 106.9924],
                '32.16.01.2001': [-6.3648, 107.1724],
                '32.71.01.1001': [-6.6044, 106.8124],
                '32.01.01.2001': [-6.4824, 106.8488],
                '32.77.01.1001': [-6.8724, 107.5422],
                '32.72.01.1001': [-6.9202, 106.9312],
                '32.05.01.2001': [-7.1944, 107.8922],
                '32.78.01.1001': [-7.3312, 108.2244],
                '32.74.01.1001': [-6.7144, 108.5566]
            };

            let defaultLat = -6.9147;
            let defaultLng = 107.6098;
            let zoomLevel = 11;
            let initialCoords = [];

            // Parsing koordinat lama jika ada
            if (inputCoords && inputCoords.value && inputCoords.value !== 'null' && inputCoords.value !== '[]') {
                try {
                    let parsed = JSON.parse(inputCoords.value);
                    if (Array.isArray(parsed)) {
                        if (parsed.length === 1 && Array.isArray(parsed[0]) && Array.isArray(parsed[0][0])) {
                            parsed = parsed[0];
                        }
                        initialCoords = parsed.filter(point => Array.isArray(point) && point.length >= 2)
                            .map(point => [parseFloat(point[0]), parseFloat(point[1])]);
                    }
                } catch (e) {
                    console.error("Gagal parsing koordinat:", e);
                }
            }

            // Atur posisi awal kamera map berdasarkan wilayah atau poligon lama
            if (initialCoords.length === 0 && lokasiSelect && lokasiSelect.value !== "") {
                const kodeWilayah = lokasiSelect.value.trim();
                if (daftarPusatWilayah[kodeWilayah]) {
                    defaultLat = daftarPusatWilayah[kodeWilayah][0];
                    defaultLng = daftarPusatWilayah[kodeWilayah][1];
                    zoomLevel = 15;
                }
            } else if (initialCoords.length > 0) {
                defaultLat = initialCoords[0][0];
                defaultLng = initialCoords[0][1];
                zoomLevel = 16;
            }

            // Inisialisasi Map beserta handler Leaflet.Editable
            const map = L.map('mapFormEditor', {
                scrollWheelZoom: false,
                editable: true // Cukup gunakan editable: true, Leaflet akan otomatis membuat map.editTools
            }).setView([defaultLat, defaultLng], zoomLevel);

            L.tileLayer('https://{s}.google.com/vt/lyrs=s,h&x={x}&y={y}&z={z}', {
                maxZoom: 20,
                subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
            }).addTo(map);

            // Tambahkan control geocoder pencarian alamat agar tombol 🔍 berfungsi
            L.Control.geocoder({
                defaultMarkGeocode: false
            }).on('markgeocode', function(e) {
                map.setView(e.geocode.center, 17);
            }).addTo(map);

            let currentPolygon = null;

            // FUNGSI UTAMA: Update value input hidden & kalkulasi luas hektar
            function updateFormValue(polygon) {
                if (!polygon) {
                    if (inputCoords) inputCoords.value = '';
                    if (inputLuas) inputLuas.value = '';
                    if (mapStatusHint) {
                        mapStatusHint.innerText = "Batas kosong, buat baru dengan klik di peta";
                        mapStatusHint.className =
                            "text-[10px] font-bold text-amber-500 bg-amber-500/10 px-2 py-0.5 rounded-md";
                    }
                    return;
                }

                // Ambil titik koordinat
                const latLngs = polygon.getLatLngs()[0];
                if (!latLngs || latLngs.length === 0) return;

                const formattedArray = latLngs.map(point => [point.lat, point.lng]);

                // Simpan ke input hidden untuk dikirim via form
                if (inputCoords) inputCoords.value = JSON.stringify(formattedArray);

                if (mapStatusHint) {
                    mapStatusHint.innerText = `Terpetakan (${formattedArray.length} Titik Sudut)`;
                    mapStatusHint.className =
                        "text-[10px] font-bold text-green-500 bg-green-500/10 px-2 py-0.5 rounded-md";
                }

                // Kalkulasi Luas via Turf.js jika titik sudut sudah minimal 3 (Segitiga)
                if (formattedArray.length >= 3) {
                    const turfCoords = formattedArray.map(p => [p[1], p[0]]); // Turf butuh format [lng, lat]
                    turfCoords.push([formattedArray[0][1], formattedArray[0][
                        0
                    ]]); // Tutup poligon untuk validasi geometri

                    try {
                        const turfPolygon = turf.polygon([turfCoords]);
                        const areaSquareMeters = turf.area(turfPolygon);
                        const areaHectares = (areaSquareMeters / 10000).toFixed(2);

                        if (inputLuas) inputLuas.value = areaHectares;
                    } catch (err) {
                        console.warn("Kalkulasi Turf gagal:", err);
                    }
                }
            }

            function attachPolygonEvents(polygon) {
                // Dengarkan berbagai aktivitas drawing dan geser titik
                polygon.on(
                    'editable:vertex:drag editable:vertex:dragend editable:vertex:deleted editable:vertex:new editable:drawing:commit',
                    function() {
                        updateFormValue(polygon);
                    }
                );
            }

            // --- ALUR RENDER AWAL (SMOOTH RENDER) ---
            if (initialCoords.length > 0) {
                // Jika sudah ada data, gunakan pendekatan L.polygon() standar Leaflet, BUKAN map.editTools.startPolygon()
                currentPolygon = L.polygon(initialCoords, {
                    color: '#22c55e',
                    fillColor: '#22c55e',
                    fillOpacity: 0.3,
                    weight: 3
                }).addTo(map);

                // Langsung aktifkan mode edit
                currentPolygon.enableEdit();
                attachPolygonEvents(currentPolygon);
                updateFormValue(currentPolygon);

                // Sesuaikan zoom dan letak kamera secara mulus
                map.whenReady(function() {
                    setTimeout(() => {
                        map.fitBounds(currentPolygon.getBounds(), {
                            padding: [40, 40],
                            maxZoom: 18
                        });
                    }, 200);
                });
            } else {
                // Jika masih kosong, langsung nyalakan mode menggambar.
                // User tinggal klik di peta (tidak perlu map.on('click'))
                currentPolygon = map.editTools.startPolygon(null, {
                    color: '#22c55e',
                    fillColor: '#22c55e',
                    fillOpacity: 0.3,
                    weight: 3
                });
                attachPolygonEvents(currentPolygon);
            }

            // Fungsi pindah kamera berdasarkan dropdown wilayah (Fix Bug Variable)
            function triggerFokusLokasiWilayah() {
                const kodeTerpilih = lokasiSelect.value.trim();
                if (daftarPusatWilayah[kodeTerpilih]) {
                    map.setView(daftarPusatWilayah[kodeTerpilih], 15);
                }
            }

            if (lokasiSelect) {
                lokasiSelect.addEventListener('change', function() {
                    // Pindah lokasi HANYA bila area belum digambar atau titik masih kosong
                    if (!currentPolygon || (currentPolygon.getLatLngs()[0] && currentPolygon.getLatLngs()[0]
                            .length === 0)) {
                        triggerFokusLokasiWilayah();
                    }
                });
            }

            // TOMBOL RESET / HAPUS MAP
            if (btnClear) {
                btnClear.addEventListener('click', function() {
                    if (currentPolygon) {
                        map.editTools
                            .stopDrawing(); // Berhentikan aktivitas menggambar yang sedang berjalan
                        currentPolygon.disableEdit();
                        map.removeLayer(currentPolygon);
                        currentPolygon = null;
                    }

                    updateFormValue(null);
                    triggerFokusLokasiWilayah();

                    // Nyalakan kembali mode menggambar dari awal
                    currentPolygon = map.editTools.startPolygon(null, {
                        color: '#22c55e',
                        fillColor: '#22c55e',
                        fillOpacity: 0.3,
                        weight: 3
                    });
                    attachPolygonEvents(currentPolygon);
                });
            }
        });
    </script>
@endpush
