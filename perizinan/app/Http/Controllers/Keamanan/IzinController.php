<?php

namespace App\Http\Controllers\Keamanan;

use App\Http\Controllers\Controller;
use App\Models\Izin;
use Illuminate\Http\Request;
use Carbon\Carbon;

class IzinController extends Controller
{
public function index(Request $request)
{
    Izin::$tanggalSimulasi = '2025-11-23';

    $query = Izin::query()->with('santri.kelas')
        ->where(function($q){
            $q->where('status', 'pending_keamanan')
              ->orWhereIn('status', ['disetujui_keamanan','ditolak_keamanan']);
        });

    // Pencarian
    if ($request->q) {
        $q = $request->q;
        $query->whereHas('santri', function($q2) use ($q){
            $q2->where('nama', 'like', "%$q%")
               ->orWhere('nis', 'like', "%$q%");
        });
    }

    // Filter status
    if ($request->status) {
        $query->where('status', $request->status);
    }

    // Filter status denda
    if ($request->status_denda) {
        $query->where('status_denda', $request->status_denda);
    }

    $izinList = $query->orderBy('updated_at','desc')->get();

    return view('izin.keamanan.index', compact('izinList'));
}


    public function approve(Request $request, Izin $izin)
    {
        $izin->update([
            'status' => 'disetujui_keamanan',
            'tgl_mulai_disetujui' => $request->tgl_mulai_disetujui,
            'tgl_selesai_disetujui' => $request->tgl_selesai_disetujui,
        ]);

        return back();
    }

    public function reject(Request $request, Izin $izin)
    {
        $request->validate(['catatan' => 'required|string']);

        $izin->update([
            'status' => 'ditolak_keamanan',
            'catatan' => $request->catatan,
        ]);

        return back();
    }
public function lapor(Izin $izin)
{
    // Hitung denda berjalan di waktu LAPOR
    $dendaFinal = $izin->denda_berjalan;

    $izin->update([
        'status_lapor' => 'sudah_lapor',
        'denda' => $dendaFinal,             // PERMANEN
        'status_denda' => 'belum_dibayar', // BELUM BAYAR
    ]);

    return back();
}

public function bayarDenda(Izin $izin)
{
    if ($izin->status_lapor !== 'sudah_lapor') {
        return back()->with('error', 'Tidak bisa bayar sebelum lapor');
    }

    $izin->update([
        'status_denda' => 'dibayar',
        'tanggal_dibayar' => now(),
    ]);

    return back();
}


}
