<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 flex items-center gap-2">
            <i class="fa-solid fa-users"></i> Data User
        </h2>
    </x-slot>

    <div class="py-6 px-6 lg:px-8 font-inter w-full">
        <div class="bg-gray-100 dark:bg-gray-800 shadow-lg sm:rounded-2xl p-6 text-gray-900 dark:text-gray-100">

            {{-- ✅ Flash Messages --}}
            @if(session('success'))
                <div class="mb-4 p-3 bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 rounded-lg flex items-center gap-2">
                    <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mb-4 p-3 bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200 rounded-lg flex items-center gap-2">
                    <i class="fa-solid fa-triangle-exclamation"></i> {{ session('error') }}
                </div>
            @endif

            {{-- 🔍 Toolbar --}}
            <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
                {{-- 🔎 Search --}}
                <form method="GET" action="{{ route('users.index') }}" class="flex flex-1 max-w-sm relative">
                    <div class="relative w-full">
                        <input type="text" name="search" id="searchInput" placeholder="Cari nama / email..."
                            value="{{ request('search') }}"
                            class="w-full border rounded px-3 py-2 pr-10 focus:outline-none focus:ring-2 focus:ring-green-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100">
                        @if(request('search'))
                            <button type="button" id="clearSearch"
                                class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-500 dark:text-gray-300 hover:text-gray-700 dark:hover:text-gray-100 transition"
                                title="Hapus Pencarian">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        @endif
                    </div>
                    <button type="submit"
                        class="ml-2 px-3 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition flex items-center gap-1">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                </form>

                {{-- 📁 Filter Role --}}
                <form method="GET" action="{{ route('users.index') }}" class="flex gap-2 items-center">
                    <select name="role" onchange="this.form.submit()"
                        class="px-3 py-2 border rounded dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100 focus:ring-2 focus:ring-green-500">
                        <option value="">Semua Role</option>
                        <option value="keamanan" {{ request('role')=='keamanan'?'selected':'' }}>Keamanan</option>
                        <option value="wali_kelas" {{ request('role')=='wali_kelas'?'selected':'' }}>Wali Kelas</option>
                        <option value="wali_santri" {{ request('role')=='wali_santri'?'selected':'' }}>Wali Santri</option>
                    </select>
                </form>

                {{-- ⚙️ Aksi --}}
                <div class="flex gap-2 flex-wrap mt-2 sm:mt-0 items-center">
                    <a href="{{ asset('files/template-user.xlsx') }}"
                        class="px-3 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition flex items-center gap-1">
                        <i class="fa-solid fa-file-excel"></i> Template
                    </a>

                    <a href="{{ route('users.create') }}"
                        class="px-3 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition flex items-center gap-1">
                        <i class="fa-solid fa-user-plus"></i> Tambah
                    </a>

                    {{-- ✅ Tombol Hapus Ganda hanya untuk Keamanan --}}
                    @if(Auth::user()->role == 'keamanan')
                        <form id="bulkDeleteForm" action="{{ route('users.bulkDelete') }}" method="POST" class="inline-block">
                            @csrf
                            {{-- ⚠️ Jangan pakai @method('DELETE') --}}
                            <button type="submit" id="deleteSelectedBtn"
                                onclick="return confirm('Yakin ingin menghapus user yang dipilih?')"
                                class="px-3 py-2 bg-red-600 text-white rounded hover:bg-red-700 transition flex items-center gap-1">
                                <i class="fa-solid fa-trash"></i> Hapus Terpilih
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            {{-- 📋 Tabel --}}
            <div class="overflow-x-auto border rounded-lg">
                <table class="w-full text-sm border-collapse">
                    <thead>
                        <tr class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200">
                            @if(Auth::user()->role == 'keamanan')
                                <th class="px-4 py-3 text-center">
                                    <input type="checkbox" id="selectAll" class="cursor-pointer">
                                </th>
                            @endif
                            <th class="px-4 py-3 text-left">Nama</th>
                            <th class="px-4 py-3 text-left">Email</th>
                            <th class="px-4 py-3 text-left">Role</th>
                            <th class="px-4 py-3 text-left">No HP</th>
                            <th class="px-4 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $user)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                                @if(Auth::user()->role == 'keamanan')
                                    <td class="px-4 py-3 text-center">
                                        <input type="checkbox" name="ids[]" value="{{ $user->id }}" class="rowCheckbox cursor-pointer">
                                    </td>
                                @endif
                                <td class="px-4 py-3">{{ $user->nama }}</td>
                                <td class="px-4 py-3">{{ $user->email }}</td>
                                <td class="px-4 py-3 capitalize">{{ $user->role }}</td>
                                <td class="px-4 py-3">{{ $user->no_hp ?? '-' }}</td>
                                <td class="px-4 py-3 text-center flex justify-center gap-2 flex-wrap">
                                    <a href="{{ route('users.edit', $user->id) }}" title="Edit"
                                        class="px-2 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600 transition">
                                        <i class="fa-solid fa-pen"></i>
                                    </a>
                                    <!-- <form action="{{ route('users.destroy', $user->id) }}" method="POST"
                                        onsubmit="return confirm('Yakin ingin menghapus user ini?')" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" title="Hapus"
                                            class="px-2 py-1 bg-red-600 text-white rounded hover:bg-red-700 transition">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form> -->
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ Auth::user()->role == 'keamanan' ? 6 : 5 }}"
                                    class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                    Tidak ada data user.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- 📄 Pagination --}}
            <div class="mt-6 flex flex-col sm:flex-row justify-between items-center text-sm text-gray-600 dark:text-gray-400 gap-2">
                <div>
                    Menampilkan {{ $users->firstItem() ?? 0 }} - {{ $users->lastItem() ?? 0 }} dari {{ $users->total() }} data
                </div>
                <div class="mt-2 sm:mt-0">
                    {{ $users->links('vendor.pagination.tailwind') }}
                </div>
            </div>

            {{-- 📥 Import di bawah --}}
            <form action="{{ route('users.import') }}" method="POST" enctype="multipart/form-data" class="mt-6">
                @csrf
                <label for="fileInput"
                    class="block w-full bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 text-center py-3 rounded-lg cursor-pointer hover:bg-gray-300 dark:hover:bg-gray-600 transition font-semibold">
                    <i class="fa-solid fa-file-import"></i> Import Data User
                </label>
                <input type="file" name="file" id="fileInput" class="hidden" onchange="this.form.submit()">
            </form>
        </div>
    </div>

    {{-- 🧠 Script --}}
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const clearBtn = document.getElementById('clearSearch');
            const searchInput = document.getElementById('searchInput');
            if (clearBtn) clearBtn.addEventListener('click', () => {
                searchInput.value = '';
                window.location.href = "{{ route('users.index') }}";
            });

            const selectAll = document.getElementById('selectAll');
            const checkboxes = document.querySelectorAll('.rowCheckbox');
            const form = document.getElementById('bulkDeleteForm');

            if (selectAll) {
                selectAll.addEventListener('change', () => {
                    checkboxes.forEach(cb => cb.checked = selectAll.checked);
                });
            }

            form?.addEventListener('submit', (e) => {
                const selected = Array.from(checkboxes)
                    .filter(cb => cb.checked)
                    .map(cb => `<input type='hidden' name='ids[]' value='${cb.value}'>`)
                    .join('');
                form.insertAdjacentHTML('beforeend', selected);
            });
        });
    </script>
</x-app-layout>
