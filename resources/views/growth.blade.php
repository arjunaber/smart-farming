@extends('layouts.app')

@section('content')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- Script untuk menampilkan error validasi Laravel via SweetAlert --}}
    @if ($errors->any())
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Gagal Menyimpan',
                text: @json(implode(', ', $errors->all())),
                confirmButtonColor: '#16a34a',
            });
        </script>
    @endif

    <div class="max-w-6xl mx-auto space-y-6" x-data="tanamanManager()">
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-2xl font-black text-slate-800 dark:text-white">Manajemen Tanaman</h1>
                <p class="text-slate-500 text-sm">Kelola data lahan dan prediksi panen secara real-time.</p>
            </div>
            <button @click="openAddModal()"
                class="bg-green-600 hover:bg-green-700 text-white px-5 py-2.5 rounded-2xl font-bold transition-all flex items-center gap-2 shadow-lg shadow-green-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M12 6v6m0 0v6m0-6h6m-6 0H6" stroke-width="2" stroke-linecap="round" />
                </svg>
                Tambah Data
            </button>
        </div>

        <div
            class="bg-white dark:bg-slate-900 rounded-[2rem] border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden">
            <div class="p-6 overflow-x-auto">
                <table id="tableTanaman" class="w-full text-sm text-left text-slate-500">
                    <thead class="text-xs text-slate-400 uppercase bg-slate-50 dark:bg-slate-800">
                        <tr>
                            <th class="px-6 py-4">Nama Tanaman</th>
                            <th class="px-6 py-4">Tgl Tanam</th>
                            <th class="px-6 py-4">Prediksi Panen</th>
                            <th class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @forelse ($tanaman as $t)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                                <td class="px-6 py-4 font-bold text-slate-700 dark:text-white">{{ $t->nama_tanaman }}</td>
                                <td class="px-6 py-4">{{ \Carbon\Carbon::parse($t->tanggal_tanam)->format('d M Y') }}</td>
                                <td class="px-6 py-4 font-bold text-green-600">
                                    Tercepat: {{ $t->panen_tercepat ?? '-' }}
                                    <div class="text-xs text-slate-400">
                                        Maksimal: {{ $t->panen_terlama ?? '-' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 flex items-center justify-center gap-2">
                                    <button @click="openPredictModal({{ json_encode($t) }})"
                                        class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-all">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" stroke-width="2" />
                                            <path
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                                                stroke-width="2" />
                                        </svg>
                                    </button>
                                    <button @click="openEditModal({{ json_encode($t) }})"
                                        class="p-2 text-amber-600 hover:bg-amber-50 rounded-lg transition-all">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"
                                                stroke-width="2" />
                                        </svg>
                                    </button>
                                    <button @click="confirmDelete('{{ route('tanaman.destroy', $t->id) }}')"
                                        class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-all">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                                                stroke-width="2" />
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="p-10 text-center text-slate-400">Data kosong.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Form Hapus (Global) --}}
        <form id="deleteForm" method="POST" class="hidden">@csrf @method('DELETE')</form>

        {{-- Modal Form (Add/Edit) --}}
        <div x-show="showFormModal"
            class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" x-transition
            x-cloak>
            <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] p-8 w-full max-w-md relative"
                @click.away="!isSaving && (showFormModal = false)">

                <h3 class="text-xl font-black mb-6" x-text="isEdit ? 'Update Data' : 'Tambah Data'"></h3>

                <form @submit.prevent="submitForm" id="mainForm" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" name="_method" :value="isEdit ? 'PUT' : 'POST'">

                    <div>
                        <label class="block font-black uppercase mb-2 text-[10px] text-slate-400">Nama Tanaman</label>
                        <input type="text" name="nama_tanaman" x-model.debounce.300ms="formData.nama_tanaman"
                            :readonly="isSaving || isEdit"
                            class="w-full bg-slate-50 border rounded-xl py-3 px-4 text-black focus:ring-2 focus:ring-green-500 outline-none transition disabled:opacity-50"
                            required placeholder="Misal: Padi, Jagung, Cabai...">
                    </div>

                    <div>
                        <label class="block font-black uppercase mb-2 text-[10px] text-slate-400">Tanggal Tanam</label>
                        <input type="date" name="tanggal_tanam" x-model="formData.tanggal_tanam" :readonly="isSaving"
                            class="w-full bg-slate-50 border rounded-xl py-3 px-4 text-black focus:ring-2 focus:ring-green-500 outline-none transition"
                            required>
                    </div>

                    <input type="hidden" name="durasi_min" :value="formData.durasi_min">
                    <input type="hidden" name="durasi_max" :value="formData.durasi_max">

                    <div class="flex gap-3 pt-4">
                        <button type="button" @click="showFormModal = false" :disabled="isSaving"
                            class="flex-1 p-3 bg-slate-100 text-black rounded-xl hover:bg-slate-200 transition disabled:opacity-50 font-bold">
                            Batal
                        </button>
                        <button type="submit" :disabled="isSaving"
                            class="flex-1 p-3 bg-green-600 text-white rounded-xl hover:bg-green-700 transition disabled:bg-slate-400 font-bold">
                            <span x-show="!isSaving" x-text="isEdit ? 'Update' : 'Simpan'"></span>
                            <span x-show="isSaving">Memproses...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Modal Predict (Countdown) --}}
        <div x-show="showPredictModal"
            class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" x-transition
            x-cloak>
            <div class="bg-white dark:bg-slate-900 rounded-[3rem] p-8 w-full max-w-2xl relative"
                @click.away="closePredict()">
                <h2 class="text-2xl font-black text-center" x-text="selectedTanaman.nama_tanaman"></h2>
                <div class="grid grid-cols-4 gap-3 my-8">
                    <template x-for="(val, unit) in countdown" :key="unit">
                        <div class="bg-slate-900 text-white rounded-2xl p-4 text-center">
                            <p class="text-3xl font-black" x-text="val"></p>
                            <p class="text-[10px] uppercase text-slate-400" x-text="unit"></p>
                        </div>
                    </template>
                </div>
                <div class="relative w-full h-4 bg-slate-100 rounded-full overflow-hidden">
                    <div class="h-full bg-green-500 transition-all duration-1000"
                        :style="'width: ' + (selectedTanaman.progress || 0) + '%'"></div>
                </div>
                <p class="mt-4 text-center text-sm font-bold text-slate-500">
                    Progres: <span x-text="selectedTanaman.progress || 0"></span>% menuju panen
                </p>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('tanamanManager', () => ({
                showFormModal: false,
                showPredictModal: false,
                isEdit: false,
                isSaving: false,
                formData: {
                    id: null,
                    nama_tanaman: '',
                    tanggal_tanam: '',
                    durasi_min: 0,
                    durasi_max: 0
                },
                selectedTanaman: {},
                countdown: {
                    days: 0,
                    hours: 0,
                    mins: 0,
                    secs: 0
                },
                timerInterval: null,

                openAddModal() {
                    this.isEdit = false;
                    this.formData = {
                        id: null,
                        nama_tanaman: '',
                        tanggal_tanam: '',
                        durasi_min: 0,
                        durasi_max: 0
                    };
                    this.showFormModal = true;
                },

                openEditModal(data) {
                    this.isEdit = true;
                    this.formData = {
                        ...data
                    };
                    this.showFormModal = true;
                },

                async submitForm() {
                    this.isSaving = true;
                    const form = document.getElementById('mainForm');

                    // Ambil langsung dari input (ANTI BUG ALPINE)
                    const namaInput = document.querySelector('[name="nama_tanaman"]').value.trim();

                    // VALIDASI WAJIB
                    if (!namaInput) {
                        this.isSaving = false;
                        Swal.fire({
                            icon: 'warning',
                            title: 'Nama Tanaman Kosong',
                            text: 'Silakan isi nama tanaman terlebih dahulu.',
                            confirmButtonColor: '#f59e0b',
                        });
                        return;
                    }

                    // Sync ke Alpine
                    this.formData.nama_tanaman = namaInput;

                    // 1. Tentukan Route
                    if (this.isEdit) {
                        form.action = `/tanaman/${this.formData.id}`;
                    } else {
                        form.action = "{{ route('tanaman.store') }}";

                        try {
                            const params = new URLSearchParams();
                            params.append('prompt',
                                `Apakah "${namaInput}" adalah tanaman pertanian valid?\nJika YA:\n- Berikan durasi panen dalam bentuk RANGE hari (contoh: 70-90)\n- Jangan beri penjelasan\n\nJika TIDAK:\n- Balas "INVALID"`
                            );

                            const response = await fetch(
                                'https://70sj7zdm-8000.asse.devtunnels.ms/recommend', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/x-www-form-urlencoded'
                                    },
                                    body: params
                                });

                            if (response.ok) {
                                const result = await response.json();
                                const aiResponse = result.recommendation.toUpperCase().trim();

                                // ❗ VALIDASI INVALID
                                if (aiResponse.includes("INVALID")) {
                                    this.isSaving = false;
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Nama Tanaman Tidak Valid',
                                        text: `"${namaInput}" tidak dikenali sebagai tanaman pertanian.`,
                                        confirmButtonColor: '#ef4444',
                                    });
                                    return;
                                }

                                // ✅ AMBIL ANGKA (SUPPORT RANGE)
                                const numbers = aiResponse.match(/\d+/g);

                                if (numbers && numbers.length > 1) {
                                    this.formData.durasi_min = parseInt(numbers[0]);
                                    this.formData.durasi_max = parseInt(numbers[1]);
                                } else if (numbers && numbers.length === 1) {
                                    const val = parseInt(numbers[0]);
                                    this.formData.durasi_min = val;
                                    this.formData.durasi_max = val;
                                } else {
                                    this.formData.durasi_min = 90;
                                    this.formData.durasi_max = 90;
                                }
                            } else {
                                this.formData.durasi_min = 90;
                                this.formData.durasi_max = 90;
                            }

                        } catch (error) {
                            console.error("AI Error:", error);
                            this.formData.durasi_min = 90;
                            this.formData.durasi_max = 90;
                        }
                    }

                    // Submit form
                    this.$nextTick(() => {
                        form.submit();
                    });
                },

                confirmDelete(url) {
                    Swal.fire({
                        title: 'Hapus data ini?',
                        text: "Data yang dihapus tidak bisa dikembalikan!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#ef4444',
                        cancelButtonColor: '#94a3b8',
                        confirmButtonText: 'Ya, Hapus!'
                    }).then((res) => {
                        if (res.isConfirmed) {
                            const f = document.getElementById('deleteForm');
                            f.action = url;
                            f.submit();
                        }
                    });
                },

                openPredictModal(data) {
                    this.selectedTanaman = data;
                    this.showPredictModal = true;
                    if (data.panen_iso) {
                        this.startCountdown(data.panen_iso);
                    }
                },

                closePredict() {
                    if (this.timerInterval) clearInterval(this.timerInterval);
                    this.showPredictModal = false;
                },

                startCountdown(target) {
                    const targetTime = new Date(target).getTime();
                    const update = () => {
                        const now = new Date().getTime();
                        const diff = targetTime - now;

                        if (diff <= 0) {
                            this.countdown = {
                                days: 0,
                                hours: 0,
                                mins: 0,
                                secs: 0
                            };
                            clearInterval(this.timerInterval);
                            return;
                        }

                        this.countdown.days = Math.floor(diff / 86400000);
                        this.countdown.hours = Math.floor((diff % 86400000) / 3600000);
                        this.countdown.mins = Math.floor((diff % 3600000) / 60000);
                        this.countdown.secs = Math.floor((diff % 60000) / 1000);
                    };
                    update();
                    if (this.timerInterval) clearInterval(this.timerInterval);
                    this.timerInterval = setInterval(update, 1000);
                }
            }));
        });
    </script>
@endsection
