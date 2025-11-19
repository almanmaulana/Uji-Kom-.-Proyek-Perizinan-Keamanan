<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class KelasController extends Controller
{
    /**
     * Tampilkan daftar kelas.
     * - keamanan → semua kelas
     * - wali_kelas → hanya kelas yang ditanggung jawabnya
     */
public function index(Request $request)
{
    $user = auth()->user();
    $query = Kelas::with('waliKelas');
    session(['previous_url' => url()->full()]);


    // 🔒 Hak akses
    if ($user->role === 'wali_kelas') {
        $query->where('wali_kelas_id', $user->id);
    }

    // 🔍 Filter jenjang
    if ($request->filled('jenjang')) {
        $query->where('jenjang', $request->jenjang);
    }

    // 🔍 Pencarian nama kelas
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where('nama_kelas', 'like', "%{$search}%");
    }

    // 🔄 Urutkan data
    switch ($request->sort) {
        case 'nama_asc':
            $query->orderBy('nama_kelas', 'asc');
            break;
        case 'nama_desc':
            $query->orderBy('nama_kelas', 'desc');
            break;
        case 'jenjang_asc':
            $query->orderByRaw("FIELD(jenjang, 'SMP', 'SMA', 'SMK')");
            break;
        case 'jenjang_desc':
            $query->orderByRaw("FIELD(jenjang, 'SMK', 'SMA', 'SMP')");
            break;
        default:
            $query->latest();
            break;
    }

    $kelas = $query->paginate(10)->withQueryString();

    return view('kelas.index', compact('kelas'));
}


    /**
     * Form tambah kelas — hanya keamanan
     */
    public function create()
    {
        $user = auth()->user();

        if ($user->role !== 'keamanan') {
            abort(403, 'Akses ditolak — hanya keamanan yang bisa menambah kelas.');
        }

        $waliKelasList = User::where('role', 'wali_kelas')->get();
        return view('kelas.create', compact('waliKelasList'));
    }

    /**
     * Simpan data kelas baru — hanya keamanan
     */
    public function store(Request $request)
    {
        $user = auth()->user();

        if ($user->role !== 'keamanan') {
            abort(403, 'Akses ditolak — hanya keamanan yang bisa menambah kelas.');
        }

        $request->validate([
            'nama_kelas'     => 'required|string|max:50',
            'jenjang'        => 'required|in:SMP,SMA,SMK',
            'tingkat'        => 'required|string|max:20',
            'jurusan'        => 'nullable|string|max:50',
            'wali_kelas_id'  => 'required|exists:users,id',
        ]);

        $kelas = Kelas::create($request->only([
            'nama_kelas', 'jenjang', 'tingkat', 'jurusan', 'wali_kelas_id'
        ]));

        Log::info("Kelas baru ditambahkan oleh {$user->nama}: {$kelas->nama_kelas}");

        return redirect()->route('kelas.index')->with('success', 'Kelas berhasil ditambahkan!');
    }

    /**
     * Form edit kelas — hanya keamanan
     */
    public function edit($id)
    {
        $user = auth()->user();

        if ($user->role !== 'keamanan') {
            abort(403, 'Akses ditolak — hanya keamanan yang bisa mengedit kelas.');
        }

        $kelas = Kelas::with('waliKelas')->findOrFail($id);
        $waliKelasList = User::where('role', 'wali_kelas')->get();

        return view('kelas.edit', compact('kelas', 'waliKelasList'));
    }

    /**
     * Update kelas — hanya keamanan
     */
    public function update(Request $request, $id)
    {
        $user = auth()->user();

        if ($user->role !== 'keamanan') {
            abort(403, 'Akses ditolak — hanya keamanan yang bisa memperbarui kelas.');
        }

        $kelas = Kelas::findOrFail($id);

        $request->validate([
            'nama_kelas'     => 'required|string|max:50',
            'jenjang'        => 'required|in:SMP,SMA,SMK',
            'tingkat'        => 'required|string|max:20',
            'jurusan'        => 'nullable|string|max:50',
            'wali_kelas_id'  => 'required|exists:users,id',
        ]);

        $kelas->update($request->only([
            'nama_kelas', 'jenjang', 'tingkat', 'jurusan', 'wali_kelas_id'
        ]));

        Log::info("Kelas {$kelas->nama_kelas} diperbarui oleh {$user->nama}");

        return redirect()->route('kelas.index')->with('success', 'Kelas berhasil diperbarui!');
    }

    /**
     * Hapus kelas — hanya keamanan
     */
    public function destroy($id)
    {
        $user = auth()->user();

        if ($user->role !== 'keamanan') {
            abort(403, 'Akses ditolak — hanya keamanan yang bisa menghapus kelas.');
        }

        $kelas = Kelas::findOrFail($id);
        $kelas->delete();

        Log::warning("Kelas {$kelas->nama_kelas} dihapus oleh {$user->nama}");

        return redirect()->route('kelas.index')->with('success', 'Kelas berhasil dihapus!');
    }

    /**
     * Detail kelas — semua role bisa lihat,
     * tapi wali kelas hanya bisa melihat kelasnya sendiri.
     */
    public function show($id)
    {
        $user = auth()->user();

        // Jika wali_kelas, hanya bisa melihat kelas yang dia tanggung
        if($user->role === 'wali_kelas') {
            $kelas = Kelas::where('id', $id)
                ->where('wali_kelas_id', $user->id)
                ->with('santris', 'waliKelas')
                ->firstOrFail();
        } else {
            // keamanan bisa melihat semua
            $kelas = Kelas::with('santris', 'waliKelas')->findOrFail($id);
        }

        return view('kelas.show', compact('kelas'));
    }


    /**
     * Ambil kelas berdasarkan jenjang (ajax)
     */
    public function byJenjang($jenjang)
    {
        $kelas = Kelas::where('jenjang', $jenjang)->get();
        return response()->json($kelas);
    }
}
