@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    <div class="mb-8">
        <h1 class="text-2xl font-black text-slate-800 dark:text-white">Prediksi Pertumbuhan</h1>
        <p class="text-slate-500 dark:text-slate-400 text-sm mt-1">Pantau siklus tanaman dan perkiraan tanggal panen.</p>
    </div>

<<<<<<< Updated upstream
    <!-- Timeline Banner -->
    <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 p-8 shadow-sm mb-8">
        <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
            <div>
                <h2 class="text-lg font-bold text-slate-800 dark:text-white">Siklus Tanaman Padi</h2>
                <p class="text-slate-500 dark:text-slate-400 text-sm">Hari 45 dari 120 (Fase Vegetatif)</p>
            </div>
            <div class="text-right">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Perkiraan Panen</p>
                <p class="text-xl font-black text-green-600 dark:text-green-400">15 Agu 2026</p>
            </div>
        </div>

        <!-- Custom Progress Bar -->
        <div class="relative w-full h-4 bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden mb-2">
            <div class="absolute top-0 left-0 h-full bg-gradient-to-r from-green-400 to-green-600 rounded-full" style="width: 37%"></div>
        </div>
        <div class="flex justify-between text-xs font-bold text-slate-400">
            <span>Penanaman</span>
            <span class="text-green-600 dark:text-green-400">Vegetatif</span>
            <span>Reproduktif</span>
            <span>Pematangan</span>
=======
    <div class="max-w-6xl mx-auto space-y-6" x-data="tanamanManager()">
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-2xl font-black text-slate-800 dark:text-white">Manajemen Tanaman & Siklus</h1>
                <p class="text-slate-500 text-sm">Kelola data lahan, prediksi panen, dan Logbook aktivitas.</p>
            </div>
            <button @click="openAddModal()" class="bg-green-600 hover:bg-green-700 text-white px-5 py-2.5 rounded-2xl font-bold transition-all flex items-center gap-2">
                + Tambah Siklus
            </button>
        </div>

        <div class="bg-white dark:bg-slate-900 rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden">
            <div class="p-6 overflow-x-auto">
                <table class="w-full text-sm text-left text-slate-500">
                    <thead class="text-xs text-slate-400 uppercase bg-slate-50 dark:bg-slate-800">
                        <tr>
                            <th class="px-6 py-4">Nama Tanaman</th>
                            <th class="px-6 py-4">Tgl Tanam</th>
                            <th class="px-6 py-4 text-center">Aksi & Logbook</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($tanaman as $t)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-4 font-bold text-slate-700">{{ $t->nama_tanaman }}</td>
                                <td class="px-6 py-4">{{ \Carbon\Carbon::parse($t->tanggal_tanam)->format('d M Y') }}</td>
                                <td class="px-6 py-4 flex items-center justify-center gap-2">
                                    {{-- Tombol Logbook Baru --}}
                                    <button @click="openLogbookModal('{{ $t->nama_tanaman }}')" class="px-3 py-1.5 bg-blue-100 text-blue-700 hover:bg-blue-200 font-bold rounded-lg transition-all flex items-center gap-1 text-xs">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                        Logbook
                                    </button>
                                    {{-- Tombol Edit & Delete asli --}}
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="p-10 text-center text-slate-400">Data kosong.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Modal Form (Add/Edit) dengan MINIMASI INPUT (Revisi Bu Mifta) --}}
        <div x-show="showFormModal" class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" x-cloak>
            <div class="bg-white rounded-[2.5rem] p-8 w-full max-w-md relative" @click.away="showFormModal = false">
                <h3 class="text-xl font-black mb-6" x-text="isEdit ? 'Update Data' : 'Tambah Siklus Tanam'"></h3>
                <form @submit.prevent="submitForm" id="mainForm" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block font-black uppercase mb-2 text-[10px] text-slate-400">Pilih Komoditas Tanaman</label>
                        {{-- Select Input untuk meminimalkan pengetikan manual --}}
                        <select name="nama_tanaman" x-model="formData.nama_tanaman" class="w-full bg-slate-50 border rounded-xl py-3 px-4 text-black focus:ring-2 focus:ring-green-500 outline-none" required>
                            <option value="" disabled>-- Pilih Tanaman Biofarmaka / Pangan --</option>
                            <option value="Padi">Padi (IR64 / Ciherang)</option>
                            <option value="Jahe Merah">Jahe Merah</option>
                            <option value="Kunyit">Kunyit</option>
                            <option value="Temulawak">Temulawak</option>
                        </select>
                    </div>

                    <div>
                        <label class="block font-black uppercase mb-2 text-[10px] text-slate-400">Tanggal Mulai Tanam</label>
                        <input type="date" name="tanggal_tanam" x-model="formData.tanggal_tanam" class="w-full bg-slate-50 border rounded-xl py-3 px-4 text-black outline-none" required>
                    </div>

                    <div class="flex gap-3 pt-4">
                        <button type="button" @click="showFormModal = false" class="flex-1 p-3 bg-slate-100 text-black rounded-xl font-bold">Batal</button>
                        <button type="submit" class="flex-1 p-3 bg-green-600 text-white rounded-xl font-bold">Simpan</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Modal Logbook (Siklus Tanam) --}}
        <div x-show="showLogbookModal" class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" x-cloak>
            <div class="bg-white rounded-[2.5rem] p-8 w-full max-w-2xl relative" @click.away="showLogbookModal = false">
                <h2 class="text-xl font-black mb-1">Riwayat Siklus Tanam</h2>
                <p class="text-sm text-green-600 font-bold mb-6" x-text="logbookTitle"></p>
                
                <div class="space-y-4 max-h-80 overflow-y-auto pr-2">
                    <div class="border-l-2 border-green-500 pl-4 pb-4">
                        <p class="text-xs font-bold text-slate-400">12 Maret 2026</p>
                        <p class="text-sm font-bold text-slate-800">Penanaman Bibit</p>
                    </div>
                    <div class="border-l-2 border-green-500 pl-4 pb-4">
                        <p class="text-xs font-bold text-slate-400">26 Maret 2026</p>
                        <p class="text-sm font-bold text-slate-800">Pemupukan Pertama (Urea)</p>
                    </div>
                    <div class="border-l-2 border-red-500 pl-4 pb-4">
                        <p class="text-xs font-bold text-slate-400">5 April 2026</p>
                        <p class="text-sm font-bold text-slate-800">Deteksi Hama Wereng</p>
                        <p class="text-xs text-slate-500 mt-1">Ditangani dengan penyemprotan insektisida berdasarkan saran LLM.</p>
                    </div>
                </div>

                <button class="w-full mt-6 p-3 border-2 border-dashed border-green-300 text-green-600 rounded-xl font-bold hover:bg-green-50 transition-colors">
                    + Catat Aktivitas Baru
                </button>
            </div>
>>>>>>> Stashed changes
        </div>

    </div>

<<<<<<< Updated upstream
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Task Card 1 -->
        <div class="bg-white dark:bg-slate-900 rounded-[2rem] border border-slate-100 dark:border-slate-800 p-6 shadow-sm">
            <div class="w-12 h-12 bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 rounded-2xl flex items-center justify-center mb-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14C19 17.866 15.866 21 12 21C8.13401 21 5 17.866 5 14C5 10.134 12 3 12 3C12 3 19 10.134 19 14Z"></path></svg>
            </div>
            <h3 class="font-bold text-slate-800 dark:text-white mb-2">Kebutuhan Air</h3>
            <p class="text-sm text-slate-500 dark:text-slate-400">Kebutuhan tinggi selama anakan aktif. Pertahankan kedalaman air 3-5 cm.</p>
        </div>

        <!-- Task Card 2 -->
        <div class="bg-white dark:bg-slate-900 rounded-[2rem] border border-slate-100 dark:border-slate-800 p-6 shadow-sm">
            <div class="w-12 h-12 bg-green-50 dark:bg-green-500/10 text-green-600 dark:text-green-400 rounded-2xl flex items-center justify-center mb-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
            </div>
            <h3 class="font-bold text-slate-800 dark:text-white mb-2">Jadwal Pemupukan</h3>
            <p class="text-sm text-slate-500 dark:text-slate-400">Pemberian urea direkomendasikan dalam 5 hari untuk inisiasi malai maksimal.</p>
        </div>
    </div>
</div>
=======
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('tanamanManager', () => ({
                showFormModal: false,
                showLogbookModal: false,
                isEdit: false,
                logbookTitle: '',
                formData: { id: null, nama_tanaman: '', tanggal_tanam: '' },

                openAddModal() {
                    this.isEdit = false;
                    this.formData = { id: null, nama_tanaman: '', tanggal_tanam: '' };
                    this.showFormModal = true;
                },
                
                openLogbookModal(nama) {
                    this.logbookTitle = `Komoditas: ${nama}`;
                    this.showLogbookModal = true;
                },

                submitForm() {
                    // Logic submit form yang sama dengan source asli Anda
                    document.getElementById('mainForm').submit();
                }
            }));
        });
    </script>
>>>>>>> Stashed changes
@endsection