<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 flex items-center gap-2">
            <i class="fa-solid fa-chalkboard text-blue-600 dark:text-blue-400"></i> Detail Kelas
        </h2>
    </x-slot>

    <div class="py-6 px-4 sm:px-6 lg:px-8 font-inter w-full">
        <div class="bg-white dark:bg-gray-900 shadow-2xl sm:rounded-2xl p-6 text-gray-900 dark:text-gray-100">

            {{-- Info Kelas --}}
            <div class="mb-6 border-b border-gray-300 dark:border-gray-700 pb-4">
                @php
                    $jenjang = strtolower($kelas->jenjang ?? '');
                    $kelas_label = match($jenjang) {
                        'smp' => "{$kelas->tingkat} {$kelas->nama_kelas}",
                        'sma' => "{$kelas->tingkat} {$kelas->jurusan} {$kelas->nama_kelas}",
                        'smk' => "{$kelas->tingkat} {$kelas->nama_kelas} {$kelas->jurusan}",
                        default => "{$kelas->tingkat} {$kelas->nama_kelas} {$kelas->jurusan}"
                    };
                @endphp
                <h3 class="text-3xl font-extrabold bg-clip-text text-transparent bg-gradient-to-r from-green-400 to-blue-500 mb-3 animate-gradient-x drop-shadow-lg">
                    {{ $kelas_label }}
                </h3>
                <div class="flex flex-col sm:flex-row justify-between text-sm text-gray-700 dark:text-gray-300 gap-2 mt-1">
                    <span class="flex items-center gap-1">
                        <i class="fa-solid fa-user-tie text-green-500"></i> Wali Kelas: <strong>{{ $kelas->waliKelas->nama ?? '-' }}</strong>
                    </span>
                    <span class="flex items-center gap-1">
                        <i class="fa-solid fa-users text-blue-500"></i> Jumlah Anggota: <strong>{{ $kelas->santris->count() }}</strong>
                    </span>
                </div>
            </div>

            {{-- Tabel Anggota --}}
            <div class="overflow-x-auto border border-gray-300 dark:border-gray-700 rounded-lg shadow-sm">
                <table class="w-full text-sm border-collapse min-w-[600px]">
                    <thead>
                        <tr class="bg-gray-200 dark:bg-gray-800 text-gray-700 dark:text-gray-300 uppercase text-xs tracking-wider">
                            <th class="px-4 py-3 text-center">No</th>
                            <th class="px-4 py-3 text-left">Nama</th>
                            <th class="px-4 py-3 text-left">Alamat</th>
                            <th class="px-4 py-3 text-left">No HP Orang Tua</th>
                            <th class="px-4 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($kelas->santris as $index => $s)
                            <tr class="transition duration-200 hover:bg-blue-50 dark:hover:bg-gray-700 cursor-pointer">
                                <td class="px-4 py-3 text-center">{{ $index + 1 }}</td>
                                <td class="px-4 py-3 font-medium text-gray-800 dark:text-gray-100">{{ $s->nama }}</td>
                                <td class="px-4 py-3 text-gray-700 dark:text-gray-300">{{ $s->alamat ?? '-' }}</td>
                                <td class="px-4 py-3 text-gray-700 dark:text-gray-300">{{ $s->waliSantri->no_hp ?? '-' }}</td>
                                <td class="px-4 py-3 text-center flex justify-center gap-2 flex-wrap">
                                    {{-- Lihat --}}
                                    <a href="{{ route('santri.show', ['santri' => $s->id, 'from' => url()->current()]) }}" title="Lihat"
                                        class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600 dark:hover:bg-blue-700 transition shadow-md flex items-center gap-1">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>

                                    {{-- Edit --}}
                                    @if(in_array(auth()->user()->role, ['keamanan','wali_kelas']))
                                        <a href="{{ route('santri.edit', $s->id) }}" title="Edit"
                                            class="px-3 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600 dark:hover:bg-yellow-700 transition shadow-md flex items-center gap-1">
                                            <i class="fa-solid fa-pen"></i>
                                        </a>

                                        {{-- Hapus --}}
                                        <form action="{{ route('santri.destroy', $s->id) }}" method="POST"
                                              onsubmit="return confirm('Yakin hapus santri {{ $s->nama }}?')"
                                              class="inline">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600 dark:hover:bg-red-700 transition shadow-md flex items-center gap-1">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                    Tidak ada anggota di kelas ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Tombol Kembali --}}
            <div class="mt-6 flex justify-end">
                <a href="{{ route('kelas.index') }}"
                   class="px-5 py-2 bg-gray-600 dark:bg-gray-700 text-white rounded hover:bg-gray-700 dark:hover:bg-gray-600 transition flex items-center gap-2 shadow-md">
                   <i class="fa-solid fa-arrow-left"></i> Kembali
                </a>
            </div>

        </div>
    </div>

    {{-- Gradient Animation --}}
    <style>
        .animate-gradient-x {
            background-size: 200% auto;
            animation: gradient-x 3s linear infinite;
        }
        @keyframes gradient-x {
            0% {background-position:0% 50%;}
            50% {background-position:100% 50%;}
            100% {background-position:0% 50%;}
        }
    </style>
</x-app-layout>
