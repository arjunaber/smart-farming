<?php

namespace App\Http\Controllers;

use App\Models\Lahan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LahanController extends Controller
{
    // Daftar lokasi diselaraskan dengan DashboardController (BMKG)
    public static $lokasiWilayah = [
        '32.73.12.1001' => 'Kota Bandung (Citarum)',
        '32.73.05.1001' => 'Kota Bandung (Antapani)',
        '32.73.20.1001' => 'Kota Bandung (Arcamanik)',
        '32.73.01.1001' => 'Kota Bandung (Andir)',
        '32.73.08.1001' => 'Kota Bandung (Astana Anyar)',
        '32.73.04.1001' => 'Kota Bandung (Babakan Ciparay)',
        '32.73.22.1001' => 'Kota Bandung (Bandung Kidul)',
        '32.73.03.1001' => 'Kota Bandung (Bandung Kulon)',
        '32.73.13.1001' => 'Kota Bandung (Bandung Wetan)',
        '32.73.11.1001' => 'Kota Bandung (Batununggal)',
        '32.73.09.1001' => 'Kota Bandung (Bojongloa Kaler)',
        '32.73.10.1001' => 'Kota Bandung (Bojongloa Kidul)',
        '32.73.23.1001' => 'Kota Bandung (Buahbatu)',
        '32.73.15.1001' => 'Kota Bandung (Cibeunying Kaler)',
        '32.73.14.1001' => 'Kota Bandung (Cibeunying Kidul)',
        '32.73.25.1001' => 'Kota Bandung (Cibiru)',
        '32.73.02.1001' => 'Kota Bandung (Cicendo)',
        '32.73.18.1001' => 'Kota Bandung (Cidadap)',
        '32.73.29.1001' => 'Kota Bandung (Cinambo)',
        '32.73.16.1001' => 'Kota Bandung (Coblong)',
        '32.73.26.1001' => 'Kota Bandung (Gedebage)',
        '32.73.19.1001' => 'Kota Bandung (Kiaracondong)',
        '32.73.17.1001' => 'Kota Bandung (Lengkong)',
        '32.73.30.1001' => 'Kota Bandung (Mandalajati)',
        '32.73.28.1001' => 'Kota Bandung (Panyileukan)',
        '32.73.24.1001' => 'Kota Bandung (Rancasari)',
        '32.73.07.1001' => 'Kota Bandung (Regol)',
        '32.73.06.1001' => 'Kota Bandung (Sukajadi)',
        '32.73.21.1001' => 'Kota Bandung (Sukasari)',
        '32.73.27.1001' => 'Kota Bandung (Ujung Berung)',
        '32.04.13.2001' => 'Kab. Bandung (Bojongsoang)',
        '32.04.14.1001' => 'Kab. Bandung (Dayeuhkolot)',
        '32.04.12.1001' => 'Kab. Bandung (Baleendah)',
        '32.04.34.2001' => 'Kab. Bandung (Soreang)',
        '32.04.16.2001' => 'Kab. Bandung (Banjaran)',
        '32.04.28.2001' => 'Kab. Bandung (Cileunyi)',
        '32.04.29.1001' => 'Kab. Bandung (Cimenyan)',
        '32.04.09.2001' => 'Kab. Bandung (Pangalengan)',
        '32.04.30.2001' => 'Kab. Bandung (Cilengkrang)',
        '32.04.27.2001' => 'Kab. Bandung (Rancaekek)',
        '32.04.32.2001' => 'Kab. Bandung (Majalaya)',
        '32.04.11.2001' => 'Kab. Bandung (Ciparay)',
        '32.17.03.2001' => 'Kab. Bandung Barat (Lembang)',
        '32.17.01.2001' => 'Kab. Bandung Barat (Ngamprah)',
        '32.17.02.2001' => 'Kab. Bandung Barat (Padalarang)',
        '32.17.13.2001' => 'Kab. Bandung Barat (Parongpong)',
        '32.75.01.1001' => 'Kota Bekasi (Bekasi Jaya)',
        '32.16.01.2001' => 'Kab. Bekasi (Cikarang Pusat)',
        '32.71.01.1001' => 'Kota Bogor (Bogor Timur)',
        '32.01.01.2001' => 'Kab. Bogor (Cibinong)',
        '32.77.01.1001' => 'Kota Cimahi (Cimahi)',
        '32.72.01.1001' => 'Kota Sukabumi (Cikole)',
        '32.05.01.2001' => 'Kab. Garut (Tarogong Kidul)',
        '32.78.01.1001' => 'Kota Tasikmalaya (Tawang)',
        '32.74.01.1001' => 'Kota Cirebon (Kejaksan)',
    ];

    public function index()
    {
        $lahan = Auth::user()->lahan()
            ->withCount('logbookEntries')
            ->latest()
            ->paginate(10);

        return view('lahan.index', compact('lahan'));
    }

    public function create()
    {
        $komoditasList = ['Padi', 'Jagung', 'Kedelai', 'Kopi', 'Teh', 'Jahe Gajah', 'Kunyit', 'Kencur'];
        $lokasiList = self::$lokasiWilayah;

        return view('lahan.create', compact('komoditasList', 'lokasiList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_lahan' => 'required|string|max:255',
            'luas' => 'required|numeric|min:0',
            'lokasi' => 'required|string',
            'komoditas_utama' => 'required|string',
            'status' => 'required|in:Aktif,Persiapan,Istirahat',
            'deskripsi' => 'nullable|string',
        ]);

        // Sangat Penting: Menyertakan user_id dari user yang sedang login
        Auth::user()->lahan()->create($validated);

        return redirect()->route('lahan.index')->with('success', 'Lahan berhasil ditambahkan!');
    }

    public function show(Lahan $lahan)
    {
        $this->authorizeOwner($lahan);
        $lahan->load(['logbookEntries' => fn($q) => $q->latest()]);

        return view('lahan.show', compact('lahan'));
    }

    public function edit(Lahan $lahan)
    {
        $this->authorizeOwner($lahan);
        $komoditasList = ['Padi', 'Jagung', 'Kedelai', 'Kopi', 'Teh', 'Jahe Gajah', 'Kunyit', 'Kencur'];
        $lokasiList = self::$lokasiWilayah;

        return view('lahan.edit', compact('lahan', 'komoditasList', 'lokasiList'));
    }

    public function update(Request $request, Lahan $lahan)
    {
        $this->authorizeOwner($lahan);

        $validated = $request->validate([
            'nama_lahan' => 'required|string|max:100',
            'luas' => 'required|numeric|min:0',
            'lokasi' => 'required|string',
            'status' => 'required|in:Aktif,Persiapan,Istirahat',
            'deskripsi' => 'nullable|string',
        ]);

        $lahan->update($validated);

        return redirect()->route('lahan.show', $lahan)->with('success', 'Data lahan berhasil diperbarui!');
    }

    public function destroy(Lahan $lahan)
    {
        $this->authorizeOwner($lahan);
        $lahan->delete();

        return redirect()->route('lahan.index')->with('success', 'Lahan telah dihapus.');
    }

    private function authorizeOwner(Lahan $lahan)
    {
        if ($lahan->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke data lahan ini.');
        }
    }
}
