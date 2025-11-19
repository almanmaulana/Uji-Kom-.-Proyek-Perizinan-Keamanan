<x-app-layout>
    <div class="max-w-lg mx-auto mt-10 bg-white dark:bg-[#2a2a2a] shadow-lg rounded-2xl p-6 transition duration-300">
        {{-- Tombol Kembali & Judul --}}
        <div class="flex items-center justify-between mb-6">
            <a href="{{ route('kelas.index') }}"
               class="flex items-center gap-2 text-gray-600 dark:text-gray-300 hover:text-green-600 transition">
                <i class="fa-solid fa-arrow-left"></i>
                <span>Kembali</span>
            </a>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">
                <i class="fa-solid fa-pen-to-square text-green-500 mr-2"></i> Edit Kelas
            </h1>
        </div>

        {{-- Error Validation --}}
        @if($errors->any())
            <div class="mb-4 p-3 bg-red-100 dark:bg-red-900/40 text-red-700 dark:text-red-300 rounded-lg text-sm">
                <ul class="list-disc pl-4">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('kelas.update', $kelas->id) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            {{-- Nama Kelas --}}
            <div>
                <label for="nama_kelas" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                    <i class="fa-solid fa-chalkboard mr-1 text-green-500"></i> Nama Kelas
                </label>
                <input type="text" name="nama_kelas" id="nama_kelas"
                       value="{{ old('nama_kelas', $kelas->nama_kelas) }}"
                       class="w-full border border-gray-300 dark:border-gray-600 bg-white dark:bg-[#3a3a3a]
                              rounded-lg p-2 text-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-green-400 focus:outline-none transition">
            </div>

            {{-- Jenjang --}}
            <div>
                <label for="jenjang" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                    <i class="fa-solid fa-school mr-1 text-green-500"></i> Jenjang
                </label>
                <select name="jenjang" id="jenjang"
                        class="w-full border border-gray-300 dark:border-gray-600 bg-white dark:bg-[#3a3a3a]
                               rounded-lg p-2 text-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-green-400 focus:outline-none transition">
                    <option value="">-- Pilih Jenjang --</option>
                    <option value="SMP" {{ old('jenjang', $kelas->jenjang) == 'SMP' ? 'selected' : '' }}>SMP</option>
                    <option value="SMA" {{ old('jenjang', $kelas->jenjang) == 'SMA' ? 'selected' : '' }}>SMA</option>
                    <option value="SMK" {{ old('jenjang', $kelas->jenjang) == 'SMK' ? 'selected' : '' }}>SMK</option>
                </select>
            </div>

            {{-- Tingkat --}}
            <div>
                <label for="tingkat" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                    <i class="fa-solid fa-layer-group mr-1 text-green-500"></i> Tingkat
                </label>
                <select name="tingkat" id="tingkat"
                        class="w-full border border-gray-300 dark:border-gray-600 bg-white dark:bg-[#3a3a3a]
                               rounded-lg p-2 text-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-green-400 focus:outline-none transition">
                    <option value="">-- Pilih Tingkat --</option>
                </select>
            </div>

            {{-- Jurusan --}}
            <div id="jurusan-wrapper" style="display:none">
                <label for="jurusan" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                    <i class="fa-solid fa-briefcase mr-1 text-green-500"></i> Jurusan
                </label>
                <select name="jurusan" id="jurusan"
                        class="w-full border border-gray-300 dark:border-gray-600 bg-white dark:bg-[#3a3a3a]
                               rounded-lg p-2 text-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-green-400 focus:outline-none transition">
                    <option value="">-- Pilih Jurusan --</option>
                </select>
            </div>

            {{-- Wali Kelas --}}
            <div>
                <label for="wali_kelas_id" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                    <i class="fa-solid fa-user-tie mr-1 text-green-500"></i> Wali Kelas
                </label>
                <select name="wali_kelas_id" id="wali_kelas_id"
                        class="w-full border border-gray-300 dark:border-gray-600 bg-white dark:bg-[#3a3a3a]
                               rounded-lg p-2 text-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-green-400 focus:outline-none transition" required>
                    <option value="">-- Pilih Wali Kelas --</option>
                    @foreach($waliKelasList as $wali)
                        <option value="{{ $wali->id }}" {{ old('wali_kelas_id', $kelas->wali_kelas_id) == $wali->id ? 'selected' : '' }}>
                            {{ $wali->nama }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Tombol Update --}}
            <button type="submit"
                    class="w-full mt-4 px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg 
                           flex items-center justify-center gap-2 transition">
                <i class="fa-solid fa-floppy-disk"></i> Update
            </button>
        </form>
    </div>

    {{-- Script --}}
    @include('kelas.partials.form-script', [
        'selectedJenjang' => old('jenjang', $kelas->jenjang),
        'selectedTingkat' => old('tingkat', $kelas->tingkat),
        'selectedJurusan' => old('jurusan', $kelas->jurusan),
    ])
</x-app-layout>
