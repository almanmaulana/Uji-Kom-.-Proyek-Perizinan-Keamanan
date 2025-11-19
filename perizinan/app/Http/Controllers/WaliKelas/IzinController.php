<?php

namespace App\Http\Controllers\Walikelas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Izin;

class IzinController extends Controller
{
    public function index()
    {
        // Ambil semua izin yang sudah masuk wali kelas
        $izinList = Izin::whereIn('status', [
            'pending_wali_kelas',
            'pending_keamanan',
            'ditolak_wali_kelas',
            'disetujui_keamanan',
            'ditolak_keamanan'
        ])->latest()->get();

        return view('izin.walikelas.index', compact('izinList'));
    }

    public function approve(Izin $izin)
    {
        // Saat wali kelas setuju, langsung lempar ke keamanan
        $izin->update([
            'status' => 'pending_keamanan',
            'catatan' => null, // reset catatan kalau ada
        ]);

        return redirect()->back()->with('success', 'Izin disetujui, menunggu keamanan.');
    }

    public function reject(Request $request, Izin $izin)
    {
        $request->validate([
            'catatan' => 'nullable|string|max:255'
        ]);

        $izin->update([
            'status' => 'ditolak_wali_kelas',
            'catatan' => $request->catatan,
        ]);

        return redirect()->back()->with('success', 'Izin ditolak.');
    }
}
