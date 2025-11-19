<?php

namespace App\Http\Controllers;

use App\Imports\SantriImport;
use App\Models\Kelas;
use App\Models\Santri;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class SantriController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = Santri::with('kelas');
        session(['previous_url' => url()->full()]);

        // 🔐 Filter otomatis untuk wali kelas
        if ($user->role === 'wali_kelas') {
            $kelasWali = Kelas::where('wali_kelas_id', $user->id)->first();
            $query->where('kelas_id', $kelasWali->id ?? null);
        }

        // 🔍 Pencarian
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->search . '%')
                ->orWhere('nis', 'like', '%' . $request->search . '%');
            });
        }

        // 🏫 Filter kelas
        if ($request->filled('kelas_id')) {
            $query->where('kelas_id', $request->kelas_id);
        }

        // 🔠 Sorting (A-Z / Z-A)
        $sortOrder = $request->get('sort', 'asc');
        $query->orderBy('nama', $sortOrder);

        // 🔢 Jumlah data per halaman (ambil dari dropdown)
        $perPage = (int) $request->get('per_page', 10); 
        if (!in_array($perPage, [10, 20, 30, 40, 50, 75, 100])) {
            $perPage = 10; // fallback aman
        }

        // 📄 Pagination tetap aktif tapi menyesuaikan jumlah per halaman
        $santris = $query->paginate($perPage)->withQueryString();
        $kelasList = Kelas::all();

        // 🏷️ Format label kelas
        foreach ($santris as $santri) {
            if ($santri->kelas) {
                $tingkat = $santri->kelas->tingkat;
                $namaKelas = $santri->kelas->nama_kelas;
                $jurusan = $santri->kelas->jurusan;
                $jenjang = strtolower($santri->kelas->jenjang ?? '');
                $santri->kelas_label = match ($jenjang) {
                    'smp' => "{$tingkat} {$namaKelas}",
                    'sma' => "{$tingkat} {$jurusan} {$namaKelas}",
                    'smk' => "{$tingkat} {$namaKelas} {$jurusan}",
                    default => "{$tingkat} {$namaKelas} {$jurusan}",
                };
            } else {
                $santri->kelas_label = '-';
            }
        }

        return view('santri.index', compact('santris', 'kelasList', 'sortOrder', 'perPage'));
    }



    public function create()
    {
        $kelas = Kelas::all();
        $waliSantriList = User::where('role', 'wali_santri')->get();
        return view('santri.create', compact('kelas', 'waliSantriList'));
    }

   public function store(Request $request)
{
    $request->validate([
        'nis' => 'required|unique:santris,nis',
        'nama' => 'required|string|max:255',
        'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:51200',
        'jenis_kelamin' => 'required|in:L,P',
        'kelas_id' => 'required|exists:kelas,id',
        'kode_keluarga' => 'required|string|max:20',
        'alamat' => 'nullable|string|max:255',
    ]);

    $kode = trim($request->kode_keluarga);

    // 🔍 Cari apakah kode keluarga ini sudah ada di wali santri
    $wali = User::where('kode_keluarga', $kode)
                ->where('role', 'wali_santri')
                ->first();

    // Siapkan data
    $data = $request->only(['nis', 'nama', 'jenis_kelamin', 'kelas_id', 'alamat']);
    $data['kode_keluarga'] = $kode;
    $data['wali_santri_id'] = $wali?->id;

    if ($request->hasFile('foto')) {
        $data['foto'] = $request->file('foto')->store('santri', 'public');
    }

    Santri::create($data);

    return redirect()->route('santri.index')->with('success', '✅ Santri berhasil ditambahkan!');
}



    public function edit($id)
    {
        $santri = Santri::findOrFail($id);
        $kelas = Kelas::all();
        $waliSantriList = User::where('role','wali_santri')->get();

         if (request()->has('return_to')) {
            session(['return_to' => request('return_to')]);
    
        }

        return view('santri.edit', compact('santri','kelas','waliSantriList'));
    }

 public function update(Request $request, $id)
{
    $santri = Santri::findOrFail($id);

    $request->validate([
        'nis' => 'required|unique:santris,nis,' . $santri->id,
        'nama' => 'required|string|max:255',
        'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:51200',
        'jenis_kelamin' => 'required|in:L,P',
        'kelas_id' => 'required|exists:kelas,id',
        'kode_keluarga' => 'required|string|max:20',
        'alamat' => 'nullable|string|max:255',
    ]);

    $kode = trim($request->kode_keluarga);

    $wali = User::where('kode_keluarga', $kode)
                ->where('role', 'wali_santri')
                ->first();

    $data = $request->only(['nis', 'nama', 'jenis_kelamin', 'kelas_id', 'alamat']);
    $data['kode_keluarga'] = $kode;
    $data['wali_santri_id'] = $wali?->id;

    if ($request->hasFile('foto')) {
        if ($santri->foto && Storage::disk('public')->exists($santri->foto)) {
            Storage::disk('public')->delete($santri->foto);
        }
        $data['foto'] = $request->file('foto')->store('santri', 'public');
    }

    $santri->update($data);

    return redirect(session('return_to', route('santri.index')))
    ->with('success', '✨ Santri berhasil diperbarui!');

}



    public function destroy($id)
    {
        $santri = Santri::findOrFail($id);
        if($santri->foto && Storage::disk('public')->exists($santri->foto)){
            Storage::disk('public')->delete($santri->foto);
        }
        $santri->delete();
        return redirect()->route('santri.index')->with('success','Santri berhasil dihapus ❌');
    }

    public function import(Request $request)
    {
        $request->validate(['file' => 'required|mimes:xlsx,xls']);

        $import = new SantriImport();
        Excel::import($import, $request->file('file'));
        $failures = $import->failures();

        if ($failures->isNotEmpty()) {
            $messages = [];
            foreach ($failures as $failure) {
                foreach ($failure->errors() as $error) {
                    $messages[] = "Baris {$failure->row()}: {$error}";
                }
            }
            return redirect()->route('santri.index')
                ->with('error', '⚠️ Beberapa data gagal diimport.')
                ->with('import_errors', $messages);
        }

        return redirect()->route('santri.index')->with('success', '✅ Semua data santri berhasil diimport!');
    }

    public function show($id)
    {
        $santri = Santri::with('kelas','waliSantri')->findOrFail($id);
        
        session(['return_to' => url()->previous()]);

        return view('santri.show', compact('santri'));
    }

    public function bulkDelete(Request $request)
    {
        if (auth()->user()->role !== 'keamanan') {
            abort(403, 'Akses ditolak');
        }

        $ids = $request->input('ids', []);
        if (empty($ids)) {
            return redirect()->route('santri.index')->with('error', 'Tidak ada santri yang dipilih untuk dihapus.');
        }

        $santris = Santri::whereIn('id', $ids)->get();
        foreach ($santris as $santri) {
            if ($santri->foto && Storage::disk('public')->exists($santri->foto)) {
                Storage::disk('public')->delete($santri->foto);
            }
            $santri->delete();
        }

        return redirect()->route('santri.index')->with('success', count($ids) . ' santri berhasil dihapus ❌');
    }

    public function bulkMove(Request $request)
    {
        $user = auth()->user();

        if (!in_array($user->role, ['keamanan', 'wali_kelas'])) {
            abort(403, 'Akses ditolak');
        }

        $ids = $request->input('ids', []);
        $kelasId = $request->input('kelas_id');

        if (empty($ids)) {
            return redirect()->route('santri.index')->with('error', 'Tidak ada santri yang dipilih.');
        }

        if (!$kelasId) {
            return redirect()->route('santri.index')->with('error', 'Pilih kelas tujuan terlebih dahulu.');
        }

        Santri::whereIn('id', $ids)->update(['kelas_id' => $kelasId]);

        return redirect()->route('santri.index')->with('success', count($ids) . ' santri berhasil dipindahkan ke kelas baru 🎓');
    }

    public function cekKodeKeluarga(Request $request)
{
    $kode = $request->get('kode_keluarga');

    if (!$kode) {
        return response()->json(['exists' => false]);
    }

    // Cek apakah kode keluarga sudah dipakai oleh santri lain
    $santri = \App\Models\Santri::with('waliSantri')
        ->where('kode_keluarga', $kode)
        ->first();

    if ($santri) {
        return response()->json([
            'exists' => true,
            'santri_nama' => $santri->nama,
            'wali_nama' => $santri->waliSantri ? $santri->waliSantri->nama : 'Belum ada wali',
        ]);
    }

    return response()->json(['exists' => false]);
}

}
