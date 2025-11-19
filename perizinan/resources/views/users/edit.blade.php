<x-app-layout>
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight mb-6">
        ✏️ Edit User
    </h2>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-[#2a2a2a] shadow-lg rounded-2xl overflow-hidden transition duration-300">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    {{-- Error Validation --}}
                    @if ($errors->any())
                        <div class="mb-4 p-3 bg-red-100 dark:bg-red-900/40 text-red-700 dark:text-red-300 rounded-lg text-sm">
                            <ul class="list-disc pl-5">
                                @foreach ($errors->all() as $err)
                                    <li>{{ $err }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Flash Success --}}
                    @if (session('success'))
                        <div class="mb-4 p-3 bg-green-100 dark:bg-green-900/40 text-green-700 dark:text-green-300 rounded-lg text-sm">
                            ✅ {{ session('success') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('users.update', $user->id) }}" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        @method('PUT')

                        {{-- Nama --}}
                        <div>
                            <label for="nama" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                                <i class="fa-solid fa-user mr-1 text-blue-500"></i> Nama Lengkap
                            </label>
                            <input type="text" name="nama" id="nama"
                                   value="{{ old('nama', $user->nama) }}"
                                   class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 
                                          bg-white dark:bg-[#3a3a3a] text-gray-800 dark:text-gray-100 
                                          focus:ring-2 focus:ring-blue-400 focus:outline-none transition" required>
                        </div>

                        {{-- Email --}}
                        <div>
                            <label for="email" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                                <i class="fa-solid fa-envelope mr-1 text-blue-500"></i> Email
                            </label>
                            <input type="email" name="email" id="email"
                                   value="{{ old('email', $user->email) }}"
                                   class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 
                                          bg-white dark:bg-[#3a3a3a] text-gray-800 dark:text-gray-100 
                                          focus:ring-2 focus:ring-blue-400 focus:outline-none transition" required>
                        </div>

                        {{-- Password (Opsional) --}}
                        <div>
                            <label for="password" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                                <i class="fa-solid fa-lock mr-1 text-blue-500"></i> Password Baru
                                <span class="text-gray-500 dark:text-gray-400 text-sm">(kosongkan jika tidak diubah)</span>
                            </label>
                            <input type="password" name="password" id="password"
                                   class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 
                                          bg-white dark:bg-[#3a3a3a] text-gray-800 dark:text-gray-100 
                                          focus:ring-2 focus:ring-blue-400 focus:outline-none transition"
                                   placeholder="Isi untuk mengganti password">
                        </div>

                        {{-- Role --}}
                        <div>
                            <label for="role" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                                <i class="fa-solid fa-user-tag mr-1 text-blue-500"></i> Role
                            </label>
                            <select name="role" id="role"
                                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 
                                           bg-white dark:bg-[#3a3a3a] text-gray-800 dark:text-gray-100 
                                           focus:ring-2 focus:ring-blue-400 transition" required>
                                <option value="">-- Pilih Role --</option>
                                <option value="keamanan" {{ $user->role == 'keamanan' ? 'selected' : '' }}>Keamanan</option>
                                <option value="wali_kelas" {{ $user->role == 'wali_kelas' ? 'selected' : '' }}>Wali Kelas</option>
                                <option value="wali_santri" {{ $user->role == 'wali_santri' ? 'selected' : '' }}>Wali Santri</option>
                            </select>
                        </div>

                        {{-- Kode Keluarga (hanya untuk Wali Santri) --}}
                        <div id="kodeKeluargaField" style="{{ $user->role == 'wali_santri' ? '' : 'display: none;' }}">
                            <label for="kode_keluarga" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                                <i class="fa-solid fa-link mr-1 text-blue-500"></i> Kode Keluarga
                            </label>
                            <input type="text" name="kode_keluarga" id="kode_keluarga"
                                   value="{{ old('kode_keluarga', $user->kode_keluarga) }}"
                                   class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 
                                          bg-white dark:bg-[#3a3a3a] text-gray-800 dark:text-gray-100 
                                          focus:ring-2 focus:ring-blue-400 transition"
                                   placeholder="Contoh: KK-12345">
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                Jika dikosongkan, sistem akan membuat otomatis (jika belum ada).
                            </p>
                        </div>

                        {{-- Nomor HP --}}
                        <div>
                            <label for="no_hp" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                                <i class="fa-solid fa-phone mr-1 text-blue-500"></i> Nomor HP
                            </label>
                            <input type="text" name="no_hp" id="no_hp"
                                   value="{{ old('no_hp', $user->no_hp) }}"
                                   class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 
                                          bg-white dark:bg-[#3a3a3a] text-gray-800 dark:text-gray-100 
                                          focus:ring-2 focus:ring-blue-400 transition"
                                   placeholder="081234567890">
                        </div>

                         {{-- Foto profile --}}
                        <div class="mt-3">
                            <label class="block font-medium text-gray-700">Foto Profil</label>

                            <!-- Preview foto lama -->
                            @if ($user->photo)
                                <img src="{{ asset('storage/' . $user->photo) }}" 
                                    class="w-24 h-24 rounded-full object-cover mb-2 border border-gray-700">
                            @endif

                            <input type="file" name="photo" 
                                class="mt-1 w-full bg-gray-800 text-white px-3 py-2 rounded-lg border border-gray-700">
                        </div>


                        {{-- Tombol Full Width --}}
                        <div class="pt-4 space-y-3">
                            <a href="{{ route('users.index') }}"
                               class="w-full flex items-center justify-center gap-2 bg-gray-500 hover:bg-gray-600 
                                      text-white font-semibold px-4 py-3 rounded-lg transition">
                                ← Kembali
                            </a>
                            <button type="submit"
                                    class="w-full flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 
                                           text-white font-semibold px-4 py-3 rounded-lg transition">
                                <i class="fa-solid fa-rotate-right"></i> Perbarui
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    {{-- Script tampilkan/hidden kode keluarga --}}
    <script>
        const roleSelect = document.getElementById('role');
        const kodeField = document.getElementById('kodeKeluargaField');

        roleSelect.addEventListener('change', function() {
            kodeField.style.display = this.value === 'wali_santri' ? 'block' : 'none';
        });

        // Tampilkan otomatis kalau reload halaman (misal validasi gagal)
        if (roleSelect.value === 'wali_santri') {
            kodeField.style.display = 'block';
        }
    </script>
</x-app-layout>
