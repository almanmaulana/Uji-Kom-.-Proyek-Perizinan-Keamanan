<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight flex items-center gap-2">
             Manajemen Izin Santri (Wali Kelas)
        </h2>
    </x-slot>

    <div class="py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">

            {{-- 🪶 Card Wrapper --}}
            <div class="bg-white dark:bg-gray-800 shadow-lg hover:shadow-2xl transition-all duration-300 rounded-2xl overflow-hidden border border-gray-200 dark:border-gray-700">

                {{-- 🧾 Card Header --}}
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100 flex items-center gap-2">
                         Daftar Pengajuan Izin Santri
                    </h3>
                </div>

                {{-- 📨 Flash Message --}}
                @if (session('success'))
                    <div class="mx-6 mt-4 p-3 bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 rounded-lg">
                        ✅ {{ session('success') }}
                    </div>
                @endif

                {{-- 📊 Table Container --}}
                <div class="overflow-x-auto p-6">
                    <table class="min-w-full text-sm border-collapse">
                        <thead class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 uppercase">
                            <tr>
                                <th class="px-4 py-3 text-left">NIS</th>
                                <th class="px-4 py-3 text-left">Nama</th>
                                <th class="px-4 py-3 text-left">Jenis Izin</th>
                                <th class="px-4 py-3 text-left">Alasan</th>
                                <th class="px-4 py-3 text-left">Status</th>
                                <th class="px-4 py-3 text-center">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse ($izinList as $izin)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                                    <td class="px-4 py-3">{{ $izin->santri->nis }}</td>
                                    <td class="px-4 py-3 font-medium">{{ $izin->santri->nama }}</td>
                                    <td class="px-4 py-3">{{ $izin->jenis_izin }}</td>
                                    <td class="px-4 py-3">{{ $izin->alasan }}</td>
                                    <td class="px-4 py-3">
                                        <span class="px-2 py-1 text-xs font-semibold rounded 
                                            @if($izin->status == 'pending_wali_kelas') bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200
                                            @elseif($izin->status == 'pending_keamanan') bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200
                                            @elseif($izin->status == 'ditolak_wali_kelas') bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200
                                            @elseif($izin->status == 'disetujui_keamanan') bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200
                                            @elseif($izin->status == 'ditolak_keamanan') bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200
                                            @endif">
                                            {{ ucfirst(str_replace('_',' ',$izin->status)) }}
                                        </span>
                                    </td>

                                    <td class="px-4 py-3 text-center">
                                        @if($izin->status == 'pending_wali_kelas')
                                            <div class="flex flex-col sm:flex-row justify-center gap-2">

                                                {{-- ✅ Setujui --}}
                                                <form action="{{ route('izin.walikelas.approve', $izin->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit"
                                                        class="flex items-center gap-1 px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white rounded-lg text-xs font-semibold shadow transition">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                                        </svg>
                                                        Setujui
                                                    </button>
                                                </form>

                                                {{-- ❌ Tolak --}}
                                                <form action="{{ route('izin.walikelas.reject', $izin->id) }}" method="POST" class="flex flex-col sm:flex-row gap-2">
                                                    @csrf
                                                    <input type="text" name="catatan" placeholder="Alasan..."
                                                        class="w-full sm:w-auto border border-gray-300 dark:border-gray-600 rounded px-2 py-1 text-xs bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-200 focus:ring-1 focus:ring-red-500">
                                                    <button type="submit"
                                                        class="flex items-center gap-1 px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white rounded-lg text-xs font-semibold shadow transition">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                                        </svg>
                                                        Tolak
                                                    </button>
                                                </form>
                                            </div>
                                        @elseif($izin->status == 'pending_keamanan')
                                            <em class="text-blue-600 dark:text-blue-300 text-xs">Menunggu keamanan</em>
                                        @else
                                            <em class="text-gray-500 dark:text-gray-400 text-xs">Selesai</em>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                        Tidak ada pengajuan izin.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
