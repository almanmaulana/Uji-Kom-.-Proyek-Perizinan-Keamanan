<x-app-layout>
    <div class="max-w-3xl mx-auto mt-10 bg-white dark:bg-[#2a2a2a] shadow-lg rounded-2xl p-6 transition duration-300">
        {{-- Header --}}
        <div class="flex items-center justify-between mb-6">
            <a href="{{ route('santri.index') }}"
               class="flex items-center gap-2 text-gray-600 dark:text-gray-300 hover:text-blue-600 transition">
                <i class="fa-solid fa-arrow-left"></i>
                <span>Kembali</span>
            </a>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">
                <i class="fa-solid fa-user-plus text-blue-500 mr-2"></i> Tambah Santri
            </h1>
        </div>

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

        <form method="POST" action="{{ route('santri.store') }}" class="space-y-4" enctype="multipart/form-data">
            @csrf

            {{-- NIS --}}
            <div>
                <label for="nis" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                    <i class="fa-solid fa-id-card mr-1 text-blue-500"></i> NIS
                </label>
                <input type="text" name="nis" id="nis" value="{{ old('nis') }}"
                       class="w-full border border-gray-300 dark:border-gray-600 bg-white dark:bg-[#3a3a3a]
                              rounded-lg p-2 text-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-blue-400 focus:outline-none transition"
                       required>
            </div>

            {{-- Nama --}}
            <div>
                <label for="nama" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                    <i class="fa-solid fa-user mr-1 text-blue-500"></i> Nama
                </label>
                <input type="text" name="nama" id="nama" value="{{ old('nama') }}"
                       class="w-full border border-gray-300 dark:border-gray-600 bg-white dark:bg-[#3a3a3a]
                              rounded-lg p-2 text-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-blue-400 focus:outline-none transition"
                       required>
            </div>

            {{-- Jenis Kelamin --}}
            <div>
                <label for="jenis_kelamin" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                    <i class="fa-solid fa-venus-mars mr-1 text-blue-500"></i> Jenis Kelamin
                </label>
                <select name="jenis_kelamin" id="jenis_kelamin"
                        class="w-full border border-gray-300 dark:border-gray-600 bg-white dark:bg-[#3a3a3a]
                               rounded-lg p-2 text-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-blue-400 transition" required>
                    <option value="">-- Pilih Jenis Kelamin --</option>
                    <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                    <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                </select>
            </div>

            {{-- Jenjang --}}
            <div>
                <label for="jenjang" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                    <i class="fa-solid fa-school mr-1 text-blue-500"></i> Jenjang
                </label>
                <select name="jenjang" id="jenjang"
                        class="w-full border border-gray-300 dark:border-gray-600 bg-white dark:bg-[#3a3a3a]
                               rounded-lg p-2 text-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-blue-400 transition" required>
                    <option value="">-- Pilih Jenjang --</option>
                    <option value="SMP" {{ old('jenjang') == 'SMP' ? 'selected' : '' }}>SMP</option>
                    <option value="SMA" {{ old('jenjang') == 'SMA' ? 'selected' : '' }}>SMA</option>
                    <option value="SMK" {{ old('jenjang') == 'SMK' ? 'selected' : '' }}>SMK</option>
                </select>
            </div>

            {{-- Kelas --}}
            <div>
                <label for="kelas_id" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                    <i class="fa-solid fa-chalkboard mr-1 text-blue-500"></i> Kelas
                </label>
                <select name="kelas_id" id="kelas_id"
                        class="w-full border border-gray-300 dark:border-gray-600 bg-white dark:bg-[#3a3a3a]
                               rounded-lg p-2 text-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-blue-400 transition" required>
                    <option value="">-- Pilih Kelas --</option>
                    {{-- Diisi otomatis lewat JS --}}
                </select>
            </div>

            {{-- Kode Keluarga --}}
            <div>
                <label for="kode_keluarga" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                    <i class="fa-solid fa-link mr-1 text-blue-500"></i> Kode Keluarga <span class="text-red-500">*</span>
                </label>
                <input type="text" name="kode_keluarga" id="kode_keluarga"
                       value="{{ old('kode_keluarga') }}"
                       class="w-full border border-gray-300 dark:border-gray-600 bg-white dark:bg-[#3a3a3a]
                              rounded-lg p-2 text-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-blue-400 transition"
                       placeholder="Masukkan kode keluarga wali santri" required>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Kode ini digunakan untuk menghubungkan santri dengan wali santri.</p>
                <div id="kodeAlert" class="mt-2 text-sm"></div>
                @error('kode_keluarga')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Foto Santri --}}
            <div>
                <label for="foto" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                    <i class="fa-solid fa-camera mr-1 text-blue-500"></i> Foto Santri
                </label>
                <input type="file" name="foto" id="foto" accept="image/*"
                       class="w-full border border-gray-300 dark:border-gray-600 bg-white dark:bg-[#3a3a3a]
                              rounded-lg p-2 text-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-blue-400 transition">
                <img id="fotoPreview" class="mt-3 w-32 h-32 object-cover rounded-lg hidden shadow-md">
            </div>

            {{-- Alamat --}}
            <div>
                <label for="alamat" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                    <i class="fa-solid fa-map-marker-alt mr-1 text-blue-500"></i> Alamat
                </label>
                <textarea name="alamat" id="alamat" rows="3"
                          class="w-full border border-gray-300 dark:border-gray-600 bg-white dark:bg-[#3a3a3a]
                                 rounded-lg p-2 text-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-blue-400 transition">{{ old('alamat') }}</textarea>
            </div>

            {{-- Tombol Aksi --}}

                <button type="submit"
                        class="w-full flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded-lg transition">
                    <i class="fa-solid fa-floppy-disk"></i> Simpan
                </button>
            </div>
        </form>
    </div>

    {{-- Script Section --}}
    <script>
        // ✅ Preview Foto
        const fotoInput = document.getElementById('foto');
        const fotoPreview = document.getElementById('fotoPreview');
        fotoInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = e => {
                    fotoPreview.src = e.target.result;
                    fotoPreview.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            } else {
                fotoPreview.src = '';
                fotoPreview.classList.add('hidden');
            }
        });

        // ✅ Filter kelas berdasarkan jenjang
        const jenjangSelect = document.getElementById('jenjang');
        const kelasSelect = document.getElementById('kelas_id');
        const allKelas = @json($kelas);

        jenjangSelect.addEventListener('change', function() {
            const jenjang = this.value;
            kelasSelect.innerHTML = '<option value="">-- Pilih Kelas --</option>';
            if (!jenjang) return;
            const filtered = allKelas.filter(k => k.jenjang === jenjang);
            filtered.forEach(k => {
                const opt = document.createElement('option');
                opt.value = k.id;
                opt.textContent = `${k.tingkat} ${k.nama_kelas}${k.jurusan ? ' ' + k.jurusan : ''}`;
                kelasSelect.appendChild(opt);
            });
        });

        // ✅ Cek kode keluarga otomatis
        const kodeInput = document.getElementById('kode_keluarga');
        const alertDiv = document.getElementById('kodeAlert');
        kodeInput.addEventListener('blur', async function() {
            const kode = this.value.trim();
            if (!kode) {
                alertDiv.innerHTML = '';
                return;
            }
            try {
                const res = await fetch(`{{ route('santri.cekKodeKeluarga') }}?kode_keluarga=${kode}`);
                const data = await res.json();
                if (data.exists) {
                    alertDiv.innerHTML = `
                        <div class="p-3 bg-yellow-100 dark:bg-yellow-900/40 text-yellow-800 dark:text-yellow-300 rounded-lg">
                            ⚠️ Kode ini sudah digunakan oleh <strong>${data.santri_nama}</strong>
                            dengan wali <strong>${data.wali_nama}</strong>.<br>
                            Jika ini satu keluarga, lanjutkan. Jika bukan, gunakan kode lain.
                        </div>`;
                } else {
                    alertDiv.innerHTML = `
                        <div class="p-3 bg-green-100 dark:bg-green-900/40 text-green-700 dark:text-green-300 rounded-lg">
                            ✅ Kode keluarga ini belum digunakan.
                        </div>`;
                }
            } catch (err) {
                alertDiv.innerHTML = `
                    <div class="p-3 bg-red-100 dark:bg-red-900/40 text-red-700 dark:text-red-300 rounded-lg">
                        ⚠️ Gagal memeriksa kode keluarga. Silakan coba lagi.
                    </div>`;
            }
        });
    </script>
</x-app-layout>
