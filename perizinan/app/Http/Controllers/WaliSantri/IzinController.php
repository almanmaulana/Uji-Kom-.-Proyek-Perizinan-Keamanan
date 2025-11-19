<?php

namespace App\Http\Controllers\WaliSantri;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Izin;

use Illuminate\Support\Facades\Auth;

class IzinController extends Controller
{
public function index(Request $request)
{
    $santriList = Auth::user()->santriAsWali()->get();
    $izinList = Izin::whereIn('santri_id', $santriList->pluck('id'))
                    ->latest()
                    ->get();

    // Flag apakah sedang buka form create
    $isCreate = $request->has('create');

    return view('izin.walisantri.index', compact('santriList', 'izinList', 'isCreate'));
}


    public function store(Request $request)
    {
        $request->validate([
            'santri_id' => 'required|exists:santris,id',
            'jenis_izin' => 'required|in:Sakit,Kegiatan,Lainnya',
            'alasan' => 'nullable|string'
        ]);

        Izin::create([
            'santri_id' => $request->santri_id,
            'jenis_izin' => $request->jenis_izin,
            'alasan' => $request->alasan,
            'status' => 'pending_wali_kelas'
        ]);

        return redirect()->back()->with('success', 'Izin berhasil diajukan!');
    }
}
