<?php

namespace App\Http\Controllers;

use App\Models\LogbookEntry;
use App\Models\SiklusTanam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogbookController extends Controller
{
    public function index(Request $request)
    {
        $petani = Auth::user()->petani;

        if (!$petani) {
            return view('logbook.index', [
                'siklusAktif' => collect(),
                'entries' => collect(),
                'lahan' => collect()
            ]);
        }

        $lahan = $petani->lahan;
        $selectedLahanId = $request->query('lahan_id');

        // Query dasar untuk entries
        $query = LogbookEntry::with('siklusTanam.lahan', 'siklusTanam.komoditas')
            ->whereHas('siklusTanam', function ($q) use ($petani, $selectedLahanId) {
                $q->whereIn('lahan_id', $petani->lahan()->pluck('id'));

                // Filter berdasarkan lahan jika dipilih
                if ($selectedLahanId) {
                    $q->where('lahan_id', $selectedLahanId);
                }
            });

        $entries = $query->latest('activity_date')->paginate(10)->withQueryString();

        return view('logbook.index', compact('entries', 'lahan', 'selectedLahanId'));
    }

    public function create()
    {
        $petani = Auth::user()->petani;

        // Form logbook hanya memunculkan siklus tanam yang statusnya masih aktif saja
        $siklusList = $petani
            ? SiklusTanam::with('lahan', 'komoditas')
            ->whereIn('lahan_id', $petani->lahan()->pluck('id'))
            ->where('status', 'aktif')
            ->get()
            : collect();

        return view('logbook.create', compact('siklusList'));
    }

    public function store(Request $request)
    {
        $petani = Auth::user()->petani;
        if (!$petani) {
            return redirect()->back()->with('error', 'Data petani tidak ditemukan.');
        }

        $lahanIds = $petani->lahan()->pluck('id')->toArray();

        $validated = $request->validate([
            'siklus_tanam_id' => [
                'required',
                'exists:siklus_tanam,id',
                function ($attribute, $value, $fail) use ($lahanIds) {
                    $siklus = SiklusTanam::find($value);
                    if (!$siklus || !in_array($siklus->lahan_id, $lahanIds)) {
                        $fail('Siklus tanam tidak valid atau bukan milik Anda.');
                    }
                },
            ],
            'activity_date'  => 'required|date',
            'jenis_kegiatan' => 'required|string|max:100',
            'title'          => 'required|string|max:200',
            'description'    => 'nullable|string',
            'kuantitas'      => 'nullable|numeric|min:0',
            'satuan'         => 'nullable|string|max:50',
        ]);

        LogbookEntry::create($validated);

        return redirect()->route('logbook.index')->with('success', 'Catatan logbook berhasil ditambahkan!');
    }

    public function destroy(LogbookEntry $logbookEntry)
    {
        $petani = Auth::user()->petani;
        $lahanIds = $petani ? $petani->lahan()->pluck('id')->toArray() : [];

        // Proteksi: periksa apakah entri logbook ini milik lahan petani yang login
        $lahanIdSiklus = $logbookEntry->siklusTanam->lahan_id;

        if (!in_array($lahanIdSiklus, $lahanIds)) {
            abort(403, 'Anda tidak memiliki akses untuk menghapus catatan ini.');
        }

        $logbookEntry->delete();
        return redirect()->route('logbook.index')->with('success', 'Catatan logbook berhasil dihapus!');
    }
}
