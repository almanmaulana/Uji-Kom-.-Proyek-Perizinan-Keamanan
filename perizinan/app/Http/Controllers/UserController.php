<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Imports\UserImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;



class UserController extends Controller
{
    // Tampilkan daftar user
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
            $query->where('nama', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%");
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $users = $query->paginate(10)->withQueryString();
        return view('users.index', compact('users'));
    }

    // Form tambah user
    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'role' => 'required|in:keamanan,wali_kelas,wali_santri',
            'no_hp' => 'nullable|string|max:20',
            'kode_keluarga' => 'nullable|string|max:50|unique:users,kode_keluarga',
        ]);

        $validated['password'] = bcrypt($validated['password']);

        // Jika role wali_santri tapi kode_keluarga kosong, generate otomatis
        if ($validated['role'] === 'wali_santri' && empty($validated['kode_keluarga'])) {
            $validated['kode_keluarga'] = strtoupper('KK-' . uniqid());
        }

        User::create($validated);

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan ✅');
    }

        
    // Update user
   public function update(Request $request, User $user)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:keamanan,wali_kelas,wali_santri',
            'no_hp' => 'nullable|string|max:20',
            'password' => 'nullable|min:6',
            'kode_keluarga' => 'nullable|string|max:50|unique:users,kode_keluarga,' . $user->id,
        ]);

        $data = $request->only(['nama', 'email', 'role', 'no_hp', 'kode_keluarga']);

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        // Generate kode keluarga otomatis jika wali_santri tapi kosong
        if ($request->role === 'wali_santri' && empty($request->kode_keluarga) && empty($user->kode_keluarga)) {
            $data['kode_keluarga'] = strtoupper('KK-' . uniqid());
        }

        $user->update($data);

        return redirect()->route('users.index')->with('success', 'User berhasil diperbarui ✅');
    }



    // Form edit user
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }


    // Hapus user
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User berhasil dihapus 🗑');
    }

    // Import Excel
    public function import(Request $request)
    {
        $request->validate(['file' => 'required|mimes:xlsx,xls']);

        try {
            Excel::import(new UserImport, $request->file('file'));
            return back()->with('success', 'Data user berhasil diimport!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->with('error', $e->getMessage());
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat import: ' . $e->getMessage());
        }
    }


    // Cari wali santri (autocomplete)
    public function searchWaliSantri(Request $request)
    {
        $query = $request->get('q', '');

        $wali = User::where('role', 'wali_santri')
            ->where('nama', 'like', "%{$query}%")
            ->limit(10)
            ->get(['id', 'nama', 'no_hp']);

        return response()->json($wali);
    }

     public function bulkDelete(Request $request)
    {  
        if (auth()->user()->role !== 'keamanan') {
            abort(403, 'Akses ditolak');
        }

        $ids = $request->input('ids', []);
        if (empty($ids)) {
            return back()->with('error', 'Tidak ada user yang dipilih untuk dihapus.');
        }

        User::whereIn('id', $ids)->delete();

        return back()->with('success', count($ids).' user berhasil dihapus.');
    }

        public function profile()
    {
        return view('profile', ['user' => auth()->user()]);
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'no_hp' => 'nullable|max:20',
            'kode_keluarga' => $user->role === 'wali_santri'
                ? 'prohibited'   // ❗ wali santri tidak boleh ganti KK
                : 'nullable|string|max:50',
        ]);

        $user->update($request->only('nama', 'email', 'no_hp', 'kode_keluarga'));

        return back()->with('success', 'Profil berhasil diperbarui!');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'password' => 'required|min:6|confirmed',
        ]);

        auth()->user()->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Password berhasil diperbarui!');
    }

    public function updatePhoto(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|max:2048'
        ]);

        $user = auth()->user();

        if ($user->photo && Storage::disk('public')->exists($user->photo)) {
            Storage::disk('public')->delete($user->photo);
        }

        $path = $request->file('photo')->store('profile', 'public');
        $user->update(['photo' => $path]);

        return back()->with('success', 'Foto profil diperbarui!');
    }



}
