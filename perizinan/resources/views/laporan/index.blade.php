<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Laporan Santri Pulang
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Filter -->
            <div class="mb-4 p-4 bg-white shadow sm:rounded-lg">
                <form method="GET" action="{{ route('laporan.index') }}" class="flex flex-wrap gap-2 items-end">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nama Santri</label>
                        <input type="text" name="nama" value="{{ request('nama') }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Kelas</label>
                        <select name="kelas_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            <option value="">Semua Kelas</option>
                            @foreach($kelas as $k)
                                <option value="{{ $k->id }}" {{ request('kelas_id')==$k->id?'selected':'' }}>
                                    {{ $k->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Bulan Pulang</label>
                        <select name="bulan" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            <option value="">Semua Bulan</option>
                            @for($i=1;$i<=12;$i++)
                                <option value="{{ $i }}" {{ request('bulan')==$i?'selected':'' }}>
                                    {{ date('F', mktime(0,0,0,$i,1)) }}
                                </option>
                            @endfor
                        </select>
                    </div>

                    <div>
                        <button type="submit"
                                class="px-4 py-2 bg-blue-500 text-white rounded-md shadow hover:bg-blue-600">
                            Filter
                        </button>
                    </div>
                </form>
            </div>

            <!-- Tabel Laporan -->
            <div class="overflow-x-auto bg-white shadow sm:rounded-lg">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIS</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kelas</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Pulang</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Selesai</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Denda</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($laporan as $item)
                        <tr @if($item->hitungDenda() > 0) class="bg-red-100" @endif>
                            <td class="px-6 py-4">{{ $item->santri->nis }}</td>
                            <td class="px-6 py-4">{{ $item->santri->nama }}</td>
                            <td class="px-6 py-4">{{ $item->santri->kelas->nama ?? '-' }}</td>
                            <td class="px-6 py-4">{{ $item->tgl_mulai_disetujui->format('d-m-Y') }}</td>
                            <td class="px-6 py-4">{{ $item->tgl_selesai_disetujui->format('d-m-Y') }}</td>
                            <td class="px-6 py-4">{{ $item->status }}</td>
                            <td class="px-6 py-4">Rp {{ number_format($item->hitungDenda(),0,',','.') }}</td>
                            <td class="px-6 py-4">
                                @if(!$item->sudah_kembali)
                                <form method="POST" action="{{ route('laporan.updateStatus', $item) }}">
                                    @csrf
                                    <button type="submit"
                                            class="px-3 py-1 bg-green-500 text-white rounded hover:bg-green-600">
                                        Sudah Lapor
                                    </button>
                                </form>
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
