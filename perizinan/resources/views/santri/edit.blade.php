<x-app-layout>
    <div class="max-w-3xl mx-auto mt-10 bg-white dark:bg-[#2a2a2a] shadow-lg rounded-2xl p-6 transition duration-300">
        {{-- Header --}}
        <div class="flex items-center justify-between mb-6">
            <a href="{{ url()->previous() }}"
            class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg shadow transition">
            ← Kembali
            </a>

            <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">
                <i class="fa-solid fa-user-pen text-blue-500 mr-2"></i> Edit Santri
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

        <form method="POST" action="{{ route('santri.update', $santri->id) }}" class="space-y-4" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- NIS --}}
            <div>
                <label for="nis" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                    <i class="fa-solid fa-id-card mr-1 text-blue-500"></i> NIS
                </label>
                <input type="text" name="nis" id="nis"
                       value="{{ old('nis', $santri->nis) }}"
                       class="w-full border border-gray-300 dark:border-gray-600 bg-white dark:bg-[#3a3a3a]
                              rounded-lg p-2 text-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-blue-400 focus:outline-none transition"
                       required>
            </div>

            {{-- Nama --}}
            <div>
                <label for="nama" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                    <i class="fa-solid fa-user mr-1 text-blue-500"></i> Nama
                </label>
                <input type="text" name="nama" id="nama"
                       value="{{ old('nama', $santri->nama) }}"
                       class="w-full border border-gray-300 dark:border-gray-600 bg-white dark:bg-[#3a3a3a]
                              rounded-lg p-2 text-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-blue-400 transition"
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
                    <option value="L" {{ old('jenis_kelamin', $santri->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                    <option value="P" {{ old('jenis_kelamin', $santri->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan</option>
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
                    <option value="SMP" {{ old('jenjang', $santri->kelas->jenjang ?? '') == 'SMP' ? 'selected' : '' }}>SMP</option>
                    <option value="SMA" {{ old('jenjang', $santri->kelas->jenjang ?? '') == 'SMA' ? 'selected' : '' }}>SMA</option>
                    <option value="SMK" {{ old('jenjang', $santri->kelas->jenjang ?? '') == 'SMK' ? 'selected' : '' }}>SMK</option>
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
                    @foreach($kelas as $k)
                        <option value="{{ $k->id }}" {{ old('kelas_id', $santri->kelas_id) == $k->id ? 'selected' : '' }}>
                            {{ $k->jenjang }} {{ $k->tingkat }} {{ $k->nama_kelas }} {{ $k->jurusan ? '(' . $k->jurusan . ')' : '' }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Kode Keluarga --}}
            <div>
                <label for="kode_keluarga" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                    <i class="fa-solid fa-link mr-1 text-blue-500"></i> Kode Keluarga <span class="text-red-500">*</span>
                </label>
                <input type="text" name="kode_keluarga" id="kode_keluarga"
                       value="{{ old('kode_keluarga', $santri->kode_keluarga) }}"
                       class="w-full border border-gray-300 dark:border-gray-600 bg-white dark:bg-[#3a3a3a]
                              rounded-lg p-2 text-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-blue-400 transition
                              @error('kode_keluarga') border-red-500 @enderror"
                       required>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Kode keluarga digunakan untuk menghubungkan santri dengan wali santri.
                </p>
                @error('kode_keluarga')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Foto Santri --}}
            <div>
                <label for="foto" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                    <i class="fa-solid fa-camera mr-1 text-blue-500"></i> Foto Santri
                </label>
                <div class="flex flex-col sm:flex-row sm:items-center sm:gap-6">
                    <img id="fotoPreview"
                         src="{{ $santri->foto ? asset('storage/' . $santri->foto) : '' }}"
                         class="mt-2 w-32 h-32 object-cover rounded-lg border border-gray-200 dark:border-gray-600 {{ $santri->foto ? '' : 'hidden' }}">
                    <div class="flex-1">
                        <input type="file" name="foto" id="foto" accept="image/*"
                               class="w-full border border-gray-300 dark:border-gray-600 bg-white dark:bg-[#3a3a3a]
                                      rounded-lg p-2 text-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-blue-400 transition mt-2 sm:mt-0">
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Biarkan kosong jika tidak ingin mengubah foto.</p>
                    </div>
                </div>
            </div>

            {{-- Alamat --}}
            <div>
                <label for="alamat" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                    <i class="fa-solid fa-map-marker-alt mr-1 text-blue-500"></i> Alamat
                </label>
                <textarea name="alamat" id="alamat" rows="3"
                          class="w-full border border-gray-300 dark:border-gray-600 bg-white dark:bg-[#3a3a3a]
                                 rounded-lg p-2 text-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-blue-400 transition">{{ old('alamat', $santri->alamat) }}</textarea>
            </div>

            {{-- Tombol Aksi --}}
            <div class="pt-4 flex justify-between gap-3">
                <button type="submit"
                        class="w-full flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded-lg transition">
                    <i class="fa-solid fa-rotate-right"></i> Update
                </button>
            </div>
        </form>
    </div>

    {{-- JS Preview Foto dan Filter Kelas --}}
    <script>
        // ✅ Filter kelas berdasarkan jenjang
        const jenjangSelect = document.getElementById('jenjang');
        const kelasSelect = document.getElementById('kelas_id');
        jenjangSelect.addEventListener('change', function() {
            const jenjang = this.value;
            kelasSelect.innerHTML = '<option value="">-- Pilih Kelas --</option>';
            if (!jenjang) return;
            fetch(`/kelas/by-jenjang/${jenjang}`)
                .then(res => res.json())
                .then(data => {
                    data.forEach(k => {
                        const opt = document.createElement('option');
                        opt.value = k.id;
                        opt.textContent = `${k.jenjang} ${k.tingkat} ${k.nama_kelas} ${k.jurusan ? '(' + k.jurusan + ')' : ''}`;
                        kelasSelect.appendChild(opt);
                    });
                });
        });

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
            }
        });
    </script>
</x-app-layout>
