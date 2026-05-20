@extends('layouts.app')

{{-- Tambahkan aset Leaflet di Header --}}
@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        /* Mengatasi konflik z-index Leaflet dengan dropdown/navbar Tailwind */
        .leaflet-container {
            z-index: 10 !important;
        }
    </style>
@endpush

@section('content')
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-6 text-slate-800 dark:text-slate-200">
        <div class="space-y-6">

            {{-- Header Section --}}
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div class="flex items-center gap-4">
                    <a href="{{ route('lahan.index') }}"
                        class="p-3 bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 hover:bg-slate-50 transition">
                        <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </a>
                    <div>
                        <div
                            class="flex items-center gap-2 text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">
                            <span>Lahan</span>
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M9 5l7 7-7 7" stroke-width="3"></path>
                            </svg>
                            <span>Detail Monitoring</span>
                        </div>
                        <h1 class="text-2xl md:text-3xl font-black text-slate-800 dark:text-white uppercase tracking-tight">
                            {{ $lahan->nama_lahan }}
                        </h1>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <a href="{{ route('lahan.edit', $lahan) }}"
                        class="px-4 py-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg font-bold text-sm hover:bg-slate-50 transition">Edit
                        Semua Data</a>
                    <a href="{{ route('logbook.create') }}"
                        class="px-4 py-2 bg-green-600 text-white rounded-lg font-bold text-sm hover:bg-green-700 shadow-lg shadow-green-600/20 transition">+
                        Logbook</a>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- Kiri: Info Utama & Peta Lahan --}}
                <div class="lg:col-span-2 space-y-6">

                    {{-- Peta Batas Wilayah Lahan (Polygon dengan Fitur Inline Edit) --}}
                    <div
                        class="bg-white dark:bg-slate-900 rounded-2xl p-6 border border-slate-100 dark:border-slate-800 shadow-sm relative">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-4">
                            <div class="flex items-center gap-2">
                                <span class="text-lg"></span>
                                <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest">Peta Batas Wilayah
                                    Lahan</h3>
                            </div>

                            {{-- Control Panel untuk Edit Poligon --}}
                            <div id="polygonControls" class="flex items-center gap-2 hidden">
                                <button id="btnEditPolygon" type="button"
                                    class="px-3 py-1 bg-amber-500 hover:bg-amber-600 text-white text-xs font-bold rounded transition flex items-center gap-1">
                                    Edit Batas
                                </button>
                                <div id="editingActions" class="flex items-center gap-1 hidden">
                                    <button id="btnSavePolygon" type="button"
                                        class="px-3 py-1 bg-green-600 hover:bg-green-700 text-white text-xs font-bold rounded transition">
                                        Simpan
                                    </button>
                                    <button id="btnCancelPolygon" type="button"
                                        class="px-3 py-1 bg-slate-500 hover:bg-slate-600 text-white text-xs font-bold rounded transition">
                                        Batal
                                    </button>
                                </div>
                            </div>
                        </div>

                        {{-- Container Peta --}}
                        <div id="mapDetailPolygon"
                            class="w-full h-80 rounded-xl overflow-hidden shadow-inner bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700">
                        </div>

                        {{-- Form Tersembunyi untuk Update via Method PUT (Opsional jika ingin submit standar, di script kita gunakan Fetch API) --}}
                        <form id="formUpdatePolygon" action="{{ route('lahan.update', $lahan) }}" method="POST"
                            class="hidden">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="polygon_coordinates" id="inputPolygonCoords">
                        </form>
                    </div>

                    {{-- Parameter Card --}}
                    <div
                        class="bg-white dark:bg-slate-900 rounded-2xl p-6 border border-slate-100 dark:border-slate-800 shadow-sm">
                        <div class="flex items-center gap-2 mb-6">
                            <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                            <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest">Parameter Utama Lahan
                            </h3>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                            <div>
                                <label class="text-[10px] font-black text-slate-400 uppercase">Luas Area</label>
                                <p class="text-3xl font-black mt-1">{{ number_format($lahan->luas ?? 0, 2) }} <span
                                        class="text-sm font-medium text-slate-400">Ha</span></p>
                            </div>
                            <div>
                                <label class="text-[10px] font-black text-slate-400 uppercase">Status Operasional</label>
                                <div class="mt-2">
                                    <span
                                        class="px-3 py-1 bg-green-100 dark:bg-green-500/10 text-green-600 dark:text-green-400 text-xs font-black rounded-full border border-green-200 dark:border-green-500/20 uppercase">
                                        {{ $lahan->status ?? 'AKTIF' }}
                                    </span>
                                </div>
                            </div>
                            <div>
                                <label class="text-[10px] font-black text-slate-400 uppercase">Lokasi Presisi</label>
                                <p class="text-sm font-bold mt-1 text-slate-600 dark:text-slate-300">{{ $lahan->lokasi }}
                                </p>
                            </div>
                        </div>

                        <div class="mt-8 pt-6 border-t border-slate-50 dark:border-slate-800">
                            <label class="text-[10px] font-black text-slate-400 uppercase block mb-2">Catatan /
                                Deskripsi</label>
                            <div
                                class="bg-slate-50 dark:bg-slate-800/50 p-4 rounded-xl text-sm italic text-slate-500 dark:text-slate-400">
                                "{{ $lahan->deskripsi ?? 'Tidak ada deskripsi.' }}"
                            </div>
                        </div>
                    </div>

                    {{-- Komoditas Card --}}
                    <div
                        class="bg-white dark:bg-slate-900 rounded-2xl p-6 border border-slate-100 dark:border-slate-800 shadow-sm">
                        <h3 class="text-sm font-black mb-5 uppercase tracking-tight">Komoditas Teranam (Siklus Aktif)</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @php
                                $siklusAktifList = $lahan->siklusTanam->where('status', 'aktif');
                            @endphp

                            @forelse($siklusAktifList as $siklus)
                                <div
                                    class="flex items-center gap-4 p-4 bg-slate-50 dark:bg-slate-800/40 rounded-2xl border border-transparent hover:border-green-500/30 transition-all">
                                    <div class="text-3xl">🌱</div>
                                    <div>
                                        <p class="font-black text-slate-800 dark:text-white">
                                            {{ $siklus->komoditas->nama_komoditas ?? 'Komoditas Tidak Diketahui' }}
                                        </p>
                                        <p class="text-[10px] font-bold text-green-600 uppercase tracking-widest">
                                            Mulai: {{ \Carbon\Carbon::parse($siklus->tanggal_mulai)->format('d M Y') }}
                                        </p>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center col-span-full py-4 space-y-2">
                                    <p class="text-sm text-slate-400">Belum ada siklus penanaman yang aktif di lahan ini.
                                    </p>
                                    <a href="{{ route('siklus-tanam.create') }}"
                                        class="text-xs text-green-600 font-bold underline hover:text-green-700">Mulai Siklus
                                        Tanam Baru</a>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- Kanan: Logbook & IoT --}}
                <div class="space-y-6">
                    {{-- Timeline Logbook --}}
                    <div
                        class="bg-white dark:bg-slate-900 rounded-2xl p-6 border border-slate-100 dark:border-slate-800 shadow-sm">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-sm font-black uppercase">Riwayat Logbook</h3>
                            <span
                                class="text-[10px] bg-slate-100 dark:bg-slate-800 px-2 py-1 rounded font-bold text-slate-400">TERBARU</span>
                        </div>

                        <div class="space-y-6 relative border-l-2 border-slate-100 dark:border-slate-800 ml-2">
                            @php
                                $allLogbooks = $lahan->siklusTanam->flatMap->logbookEntries->sortByDesc(
                                    'activity_date',
                                );
                            @endphp

                            @forelse($allLogbooks as $entry)
                                <div class="relative pl-6">
                                    <div
                                        class="absolute -left-[9px] top-1 w-4 h-4 bg-white dark:bg-slate-900 border-2 {{ $entry->jenis_kegiatan == 'hama' ? 'border-red-500' : 'border-green-500' }} rounded-full">
                                    </div>

                                    <div class="flex flex-col">
                                        <span class="text-[10px] font-black text-slate-400 uppercase mb-1">
                                            {{ \Carbon\Carbon::parse($entry->activity_date)->format('d M Y') }}
                                        </span>
                                        <h4 class="text-sm font-black text-slate-800 dark:text-white leading-tight">
                                            {{ $entry->title }} ({{ ucfirst($entry->jenis_kegiatan) }})
                                        </h4>
                                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 italic leading-relaxed">
                                            "{: $entry->description :}"
                                        </p>
                                        @if ($entry->kuantitas)
                                            <span class="text-[10px] text-slate-400 font-bold mt-1">Kuantitas:
                                                {{ $entry->kuantitas }} {{ $entry->satuan }}</span>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <p class="text-xs text-slate-500 text-center py-2 pl-4">Belum ada aktivitas tercatat pada
                                    seluruh siklus.</p>
                            @endforelse
                        </div>
                    </div>

                    {{-- AGA IoT Widget --}}
                    <div class="bg-slate-900 rounded-[2.5rem] p-8 shadow-2xl relative overflow-hidden group">
                        <div class="absolute -top-12 -right-12 w-32 h-32 bg-green-500/20 blur-3xl rounded-full"></div>
                        <div class="relative z-10">
                            <p class="text-[10px] font-black text-green-500 uppercase tracking-[0.3em] mb-6">AGA Smart
                                Farming</p>

                            @php
                                $device = \App\Models\IotDevice::where('lahan_id', $lahan->id)->first();
                                $latestMetric = $device
                                    ? \App\Models\EnvironmentalMetric::where('iot_device_id', $device->id)
                                        ->latest('recorded_at')
                                        ->first()
                                    : null;
                            @endphp

                            <div class="space-y-6">
                                <div>
                                    <div class="flex justify-between mb-2">
                                        <span class="text-xs text-slate-400 font-bold">Kelembapan Tanah</span>
                                        <span class="text-lg font-mono font-black text-white">
                                            {{ $latestMetric ? $latestMetric->soil_moisture . '%' : 'N/A' }}
                                        </span>
                                    </div>
                                    <div class="w-full bg-slate-800 h-2 rounded-full">
                                        <div class="bg-green-500 h-full rounded-full"
                                            style="width: {{ $latestMetric ? $latestMetric->soil_moisture : 0 }}%"></div>
                                    </div>
                                </div>

                                <div>
                                    <div class="flex justify-between mb-2">
                                        <span class="text-xs text-slate-400 font-bold">Suhu Lingkungan</span>
                                        <span class="text-lg font-mono font-black text-white">
                                            {{ $latestMetric ? $latestMetric->temperature . '°C' : 'N/A' }}
                                        </span>
                                    </div>
                                    <div class="w-full bg-slate-800 h-2 rounded-full">
                                        <div class="bg-orange-500 h-full rounded-full"
                                            style="width: {{ $latestMetric ? min($latestMetric->temperature * 2, 100) : 0 }}%">
                                        </div>
                                    </div>
                                </div>

                                <div class="pt-6 flex items-center justify-center border-t border-slate-800/50">
                                    @if ($device)
                                        <span class="flex h-2 w-2 rounded-full bg-green-500 mr-3 animate-ping"></span>
                                        <p class="text-[9px] text-slate-400 font-black tracking-widest uppercase">
                                            Alat: {{ $device->device_name }} (Online)
                                        </p>
                                    @else
                                        <span class="flex h-2 w-2 rounded-full bg-red-500 mr-3"></span>
                                        <p class="text-[9px] text-slate-500 font-black tracking-widest uppercase">Perangkat
                                            Tidak Terdeteksi</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

{{-- Tambahkan skrip Leaflet di Footer --}}
@push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // 1. Parsing Koordinat dari backend secara aman
            let rawCoordinates = @json($lahan->polygon_coordinates ?? []);

            if (typeof rawCoordinates === 'string' && rawCoordinates.trim() !== '') {
                try {
                    rawCoordinates = JSON.parse(rawCoordinates);
                } catch (e) {
                    rawCoordinates = [];
                }
            }
            let polygonCoords = Array.isArray(rawCoordinates) ? rawCoordinates : [];

            const mapContainer = document.getElementById('mapDetailPolygon');
            const controlsContainer = document.getElementById('polygonControls');
            const btnEdit = document.getElementById('btnEditPolygon');
            const editingActions = document.getElementById('editingActions');
            const btnSave = document.getElementById('btnSavePolygon');
            const btnCancel = document.getElementById('btnCancelPolygon');

            let detailMap, polygonObject;
            let isEditing = false;
            let originalCoords = JSON.parse(JSON.stringify(polygonCoords)); // Deep copy untuk fallback cancel

            // 2. Jika koordinat ada, inisialisasi peta dan aktifkan tombol edit kontrol
            if (polygonCoords.length > 0) {
                controlsContainer.classList.remove('hidden');

                detailMap = L.map('mapDetailPolygon', {
                    scrollWheelZoom: false
                });

                // Menggunakan layer Hybrid Satelit Google
                L.tileLayer('https://{s}.google.com/vt/lyrs=s,h&x={x}&y={y}&z={z}', {
                    maxZoom: 20,
                    subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
                }).addTo(detailMap);

                // Render Poligon batas wilayah lahan
                polygonObject = L.polygon(polygonCoords, {
                    color: '#22c55e', // Hijau emerald
                    fillColor: '#22c55e',
                    fillOpacity: 0.3,
                    weight: 3
                }).addTo(detailMap);

                // Tambahkan marker pop up nama lahan di pusat koordinat
                polygonObject.bindPopup(
                    `<strong>{{ $lahan->nama_lahan }}</strong><br>Luas: {{ number_format($lahan->luas ?? 0, 2) }} Ha`
                ).openPopup();

                // Set otomatis fokus kamera zoom peta mengikuti area koordinat poligon
                detailMap.fitBounds(polygonObject.getBounds());

                // ==========================================
                // FITUR INTERAKTIF EDIT TITIK POLYGON
                // ==========================================

                // Klik Tombol "Edit Batas"
                btnEdit.addEventListener('click', function() {
                    isEditing = true;
                    polygonObject.closePopup();
                    polygonObject.unbindPopup(); // Lepas popup agar tidak mengganggu drag titik

                    // Aktifkan mode edit bawaan Leaflet pada objek poligon
                    if (polygonObject.editing) {
                        polygonObject.editing.enable();
                    } else {
                        // Jalur alternatif jika versi Leaflet memerlukan handler manual
                        // (Umumnya Leaflet murni butuh sub-plugin Path.Transform / Leaflet.Draw untuk editing, 
                        // namun jika Anda menggunakan library bungkusan tertentu, method ini langsung bekerja)
                        alert(
                            "Gunakan halaman Edit Semua Data jika sistem Leaflet Anda memerlukan plugin eksternal."
                            );
                        return;
                    }

                    // Ubah visibilitas UI tombol kontrol
                    btnEdit.classList.add('hidden');
                    editingActions.classList.remove('hidden');
                });

                // Klik Tombol "Batal"
                btnCancel.addEventListener('click', function() {
                    isEditing = false;
                    if (polygonObject.editing) {
                        polygonObject.editing.disable();
                    }

                    // Kembalikan koordinat ke bentuk semula
                    polygonObject.setLatLngs(originalCoords);
                    polygonObject.bindPopup(
                        `<strong>{{ $lahan->nama_lahan }}</strong><br>Luas: {{ number_format($lahan->luas ?? 0, 2) }} Ha`
                    );

                    editingActions.classList.add('hidden');
                    btnEdit.classList.remove('hidden');
                });

                // Klik Tombol "Simpan" (Kirim AJAX/Fetch ke Laravel Backend)
                btnSave.addEventListener('click', function() {
                    btnSave.disabled = true;
                    btnSave.innerText = 'Menyimpan...';

                    // Dapatkan array koordinat baru setelah digeser oleh user
                    const currentLatLngs = polygonObject.getLatLngs()[0];
                    const updatedCoordinates = currentLatLngs.map(latlng => [latlng.lat, latlng.lng]);

                    // Kirim data ke backend menggunakan Fetch API bawaan browser
                    fetch("{{ route('lahan.update', $lahan) }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: JSON.stringify({
                                _method: 'PUT', // Spoofing method PUT Laravel
                                polygon_coordinates: JSON.stringify(updatedCoordinates)
                            })
                        })
                        .then(response => response.json().then(data => ({
                            status: response.status,
                            body: data
                        })))
                        .then(res => {
                            if (res.status === 200 || res.body.success) {
                                // Update data backup sukses
                                originalCoords = JSON.stringify(updatedCoordinates);
                                alert('Batas koordinat lahan berhasil diperbarui!');
                                window.location
                                    .reload(); // Reload untuk kalkulasi ulang parameter luas dll di server
                            } else {
                                alert('Gagal menyimpan koordinat: ' + (res.body.message ||
                                    'Terjadi kesalahan internal.'));
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('Terjadi kesalahan jaringan saat mencoba menyimpan.');
                        })
                        .finally(() => {
                            btnSave.disabled = false;
                            btnSave.innerText = 'Simpan';
                            isEditing = false;
                            if (polygonObject.editing) polygonObject.editing.disable();
                            editingActions.classList.add('hidden');
                            btnEdit.classList.remove('hidden');
                        });
                });

            } else {
                // Tampilan fallback jika koordinat poligon kosong
                mapContainer.classList.add('flex', 'flex-col', 'items-center', 'justify-center', 'text-center',
                    'p-6');
                mapContainer.innerHTML = `
                    <div class="space-y-2">
                        <span class="text-4xl block">🗺️</span>
                        <h4 class="text-sm font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Koordinat Geospasial Belum Diatur</h4>
                        <p class="text-xs text-slate-400 max-w-xs mx-auto">Silakan gunakan halaman edit penuh untuk membuat bentuk poligon area awal di atas peta.</p>
                        <div class="pt-2">
                            <a href="{{ route('lahan.edit', $lahan) }}" class="inline-block px-3 py-1.5 bg-blue-500 text-white font-bold text-xs rounded-lg hover:bg-blue-600 transition">Atur Koordinat Awal</a>
                        </div>
                    </div>
                `;
            }
        });
    </script>
@endpush
