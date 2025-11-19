<?php

namespace App\Http\Controllers;

use App\Models\Izin;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $laporan = Izin::with('santri.kelas')
            ->when($request->nama, fn($q) => $q->whereHas('santri', fn($s) => $s->where('nama','like',"%{$request->nama}%")))
            ->when($request->kelas_id, fn($q) => $q->whereHas('santri.kelas', fn($k) => $k->where('id', $request->kelas_id)))
            ->when($request->bulan, fn($q) => $q->whereMonth('tgl_mulai_disetujui', $request->bulan))
            ->get();

        return view('laporan.index', compact('laporan'));
    }

    public function updateStatus(Izin $izin)
    {
        $izin->update([
            'sudah_kembali' => true,
            'tgl_lapor' => now(),
            'denda' => $izin->hitungDenda()
        ]);

        return back()->with('success','Status santri diperbarui.');
    }
}
