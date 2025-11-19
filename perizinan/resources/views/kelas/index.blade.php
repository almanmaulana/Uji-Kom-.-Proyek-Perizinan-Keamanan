<x-app-layout>
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">

        {{-- 🧭 Header --}}
        <div class="flex flex-col sm:flex-row justify-between sm:items-center mb-6 gap-4">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100 flex items-center gap-2">
                <i class="fa-solid fa-chalkboard text-blue-600 dark:text-blue-400"></i>
                Daftar Kelas
            </h1>

            @if(Auth::user()->role === 'keamanan')
                <a href="{{ route('kelas.create') }}"
                   class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded-lg shadow transition">
                    <i class="fa-solid fa-plus"></i>
                    Tambah Kelas
                </a>
            @endif
        </div>

        {{-- 🎯 Filter + Search + Urutkan --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-6">
            <form method="GET" action="{{ route('kelas.index') }}" class="flex flex-wrap items-center gap-3 w-full sm:w-auto">

                {{-- Pencarian --}}
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Cari nama kelas..."
                    class="border rounded-lg px-3 py-2 dark:bg-gray-700 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500"
                />

                {{-- Filter jenjang --}}
                <select name="jenjang" onchange="this.form.submit()"
                    class="border rounded-lg px-3 py-2 dark:bg-gray-700 dark:text-gray-100">
                    <option value="">Semua Jenjang</option>
                    <option value="SMP" {{ request('jenjang') == 'SMP' ? 'selected' : '' }}>SMP</option>
                    <option value="SMA" {{ request('jenjang') == 'SMA' ? 'selected' : '' }}>SMA</option>
                    <option value="SMK" {{ request('jenjang') == 'SMK' ? 'selected' : '' }}>SMK</option>
                </select>

                {{-- Urutkan --}}
                <select name="sort" onchange="this.form.submit()"
                    class="border rounded-lg px-3 py-2 dark:bg-gray-700 dark:text-gray-100">
                    <option value="">Urutkan...</option>
                    <option value="nama_asc" {{ request('sort') == 'nama_asc' ? 'selected' : '' }}>Nama Kelas A–Z</option>
                    <option value="nama_desc" {{ request('sort') == 'nama_desc' ? 'selected' : '' }}>Nama Kelas Z–A</option>
                    <option value="jenjang_asc" {{ request('sort') == 'jenjang_asc' ? 'selected' : '' }}>Jenjang: SMP → SMA → SMK</option>
                    <option value="jenjang_desc" {{ request('sort') == 'jenjang_desc' ? 'selected' : '' }}>Jenjang: SMK → SMA → SMP</option>
                </select>

                @if(request()->has('sort') || request()->has('jenjang') || request()->has('search'))
                    <a href="{{ route('kelas.index') }}"
                       class="text-sm text-gray-600 dark:text-gray-300 hover:text-blue-600">
                        Reset
                    </a>
                @endif

                <button type="submit" class="bg-blue-600 text-white px-3 py-1 rounded-lg hover:bg-blue-700 transition">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
            </form>
        </div>

        {{-- ✅ Alert sukses --}}
        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 dark:bg-green-900/40 border border-green-300 dark:border-green-700 
                        text-green-800 dark:text-green-200 rounded-lg flex items-center gap-2">
                <i class="fa-solid fa-circle-check text-green-600 dark:text-green-400"></i>
                {{ session('success') }}
            </div>
        @endif

        {{-- 📋 Tabel Daftar Kelas --}}
        <div class="overflow-x-auto bg-white dark:bg-gray-800 shadow-md rounded-lg border border-gray-300 dark:border-gray-700">
            <table class="min-w-full text-sm text-left text-gray-600 dark:text-gray-200">
                <thead class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 uppercase">
                    <tr>
                        <th class="px-4 py-3">ID</th>
                        <th class="px-4 py-3">Nama Kelas</th>
                        <th class="px-4 py-3">Jenjang</th>
                        <th class="px-4 py-3">Jurusan</th>
                        <th class="px-4 py-3">Wali Kelas</th>
                        <th class="px-4 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($kelas as $k)
                        <tr class="border-b border-gray-300 dark:border-gray-700 transition 
                                   hover:bg-blue-50 dark:hover:bg-gray-700/50">
                            <td class="px-4 py-3">{{ $k->id }}</td>
                            <td class="px-4 py-3 font-medium">{{ $k->formatted_name ?? $k->nama_kelas }}</td>
                            <td class="px-4 py-3">{{ $k->jenjang }}</td>
                            <td class="px-4 py-3">{{ $k->jurusan ?? '-' }}</td>
                            <td class="px-4 py-3">{{ $k->waliKelas->nama ?? '-' }}</td>
                            <td class="px-4 py-3 text-center">
                                <div class="flex justify-center flex-wrap gap-2">
                                    <a href="{{ route('kelas.show', $k->id) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-md shadow transition">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                    @if(Auth::user()->role === 'keamanan')
                                        <a href="{{ route('kelas.edit', $k->id) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded-md shadow transition">
                                            <i class="fa-solid fa-pen"></i>
                                        </a>
                                        <form action="{{ route('kelas.destroy', $k->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" onclick="return confirm('Yakin mau hapus kelas ini?')"
                                                class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded-md shadow transition">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-gray-500 dark:text-gray-400">Belum ada data kelas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- 📄 Pagination --}}
        <div class="mt-6 flex flex-col sm:flex-row justify-between items-center gap-3 text-sm text-gray-600 dark:text-gray-400">
            <div>
                Menampilkan {{ $kelas->firstItem() ?? 0 }}–{{ $kelas->lastItem() ?? 0 }} dari {{ $kelas->total() }} data
            </div>
            <div>
                {{ $kelas->links('vendor.pagination.tailwind') }}
            </div>
        </div>
    </div>
</x-app-layout>
