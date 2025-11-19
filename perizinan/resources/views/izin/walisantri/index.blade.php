<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            📝 Data & Ajukan Izin Santri
        </h2>
    </x-slot>

    <div class="py-8" x-data="{ open: false }">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            {{-- Tombol Ajukan --}}
            <div class="mb-6">
                <button @click="open = true"
                    class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg shadow transition">
                    ➕ Ajukan Izin
                </button>
            </div>

            {{-- Modal Ajukan Izin (Alpine) --}}
            <div x-show="open" x-transition
                 class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
                <div class="bg-white rounded-lg shadow-lg w-full max-w-lg p-6">
                    <form action="{{ route('izin.walisantri.store') }}" method="POST">
                        @csrf

                        <div class="flex justify-between items-center border-b pb-2 mb-4">
                            <h5 class="text-lg font-semibold">Ajukan Izin</h5>
                            <button type="button" @click="open = false"
                                class="text-gray-500 hover:text-gray-700">&times;</button>
                        </div>

                        <div class="space-y-4">
                            {{-- Pilih Santri --}}
                            <div>
                                <label class="block text-sm font-semibold text-gray-700">Santri</label>
                                <select name="santri_id" class="w-full rounded border-gray-300" required>
                                    <option value="">Pilih Santri</option>
                                    @foreach($santriList as $santri)
                                        <option value="{{ $santri->id }}">{{ $santri->nama }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Jenis Izin --}}
                            <div>
                                <label class="block text-sm font-semibold text-gray-700">Jenis Izin</label>
                                <select name="jenis_izin" class="w-full rounded border-gray-300" required>
                                    <option value="Sakit">Sakit</option>
                                    <option value="Kegiatan">Kegiatan</option>
                                    <option value="Lainnya">Lainnya</option>
                                </select>
                            </div>

                            {{-- Alasan --}}
                            <div>
                                <label class="block text-sm font-semibold text-gray-700">Alasan</label>
                                <textarea name="alasan" class="w-full rounded border-gray-300" rows="3"></textarea>
                            </div>
                        </div>

                        {{-- Footer --}}
                        <div class="flex justify-end mt-6 space-x-2">
                            <button type="submit"
                                class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg shadow">
                                Ajukan
                            </button>
                            <button type="button" @click="open = false"
                                class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg shadow">
                                Batal
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Tabel Riwayat Izin --}}
            <div class="bg-white overflow-hidden shadow-md sm:rounded-xl mt-6">
                <div class="p-6 text-gray-900">
                    <table class="table-auto w-full border border-gray-200">
                        <thead>
                            <tr class="bg-gray-100 text-left">
                                <th class="px-4 py-2 border">No</th>
                                <th class="px-4 py-2 border">Santri</th>
                                <th class="px-4 py-2 border">Jenis Izin</th>
                                <th class="px-4 py-2 border">Alasan</th>
                                <th class="px-4 py-2 border">Status</th>
                                <th class="px-4 py-2 border">Tanggal Mulai - Kembali</th>
                                <th class="px-4 py-2 border">Catatan / Denda</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($izinList as $index => $izin)
                                <tr class="border">
                                    <td class="px-4 py-2 border">{{ $index + 1 }}</td>
                                    <td class="px-4 py-2 border">{{ $izin->santri->nama }}</td>
                                    <td class="px-4 py-2 border">{{ $izin->jenis_izin }}</td>
                                    <td class="px-4 py-2 border">{{ $izin->alasan ?? '-' }}</td>
                                    <td class="px-4 py-2 border">
                                        @if($izin->status == 'pending_wali_kelas')
                                            <span class="px-2 py-1 rounded bg-yellow-300 text-yellow-900">Pending Wali Kelas</span>
                                        @elseif($izin->status == 'disetujui_wali_kelas')
                                            <span class="px-2 py-1 rounded bg-blue-300 text-blue-900">Disetujui Wali Kelas</span>
                                        @elseif($izin->status == 'ditolak_wali_kelas')
                                            <span class="px-2 py-1 rounded bg-red-300 text-red-900">Ditolak Wali Kelas</span>
                                        @elseif($izin->status == 'disetujui_keamanan')
                                            <span class="px-2 py-1 rounded bg-green-300 text-green-900">Disetujui Keamanan</span>
                                        @elseif($izin->status == 'ditolak_keamanan')
                                            <span class="px-2 py-1 rounded bg-gray-700 text-white">Ditolak Keamanan</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-2 border">
                                        {{ $izin->tgl_mulai_disetujui?->format('d/m/Y') ?? '-' }} 
                                        - 
                                        {{ $izin->tgl_selesai_disetujui?->format('d/m/Y') ?? '-' }}
                                    </td>
                                    <td class="px-4 py-2 border">
                                        @if(in_array($izin->status, ['ditolak_wali_kelas','ditolak_keamanan']))
                                            {{ $izin->catatan }}
                                        @elseif($izin->status == 'disetujui_keamanan')
                                            Rp {{ number_format($izin->denda,0,',','.') }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">Belum ada data izin</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
