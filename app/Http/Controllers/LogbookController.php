<?php

namespace App\Http\Controllers;

use App\Models\LogbookEntry;
use App\Models\Lahan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogbookController extends Controller
{
    public function index()
    {
        $lahan = Auth::user()->lahan()->with('logbookEntries')->get();
        $entries = LogbookEntry::whereIn('lahan_id', $lahan->pluck('id'))->latest()->get();
        return view('logbook.index', compact('lahan', 'entries'));
    }

    public function create()
    {
        $lahan = Auth::user()->lahan;
        return view('logbook.create', compact('lahan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'lahan_id' => 'required|exists:lahan,id',
            'tanggal' => 'required|date',
            'tipe' => 'required|in:tanam,pupuk,irigasi,panen,hama,lainnya',
            'detail' => 'required|string',
        ]);

        LogbookEntry::create($request->all());

        return redirect()->route('logbook.index')->with('success', 'Catatan logbook ditambahkan!');
    }

    public function destroy(LogbookEntry $logbookEntry)
    {
        if (!Auth::user()->lahan->contains($logbookEntry->lahan_id)) {
            abort(403);
        }

        $logbookEntry->delete();
        return redirect()->route('logbook.index')->with('success', 'Catatan dihapus!');
    }
}

