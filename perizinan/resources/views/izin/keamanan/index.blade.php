<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight flex items-center gap-2">
            🛡️ Validasi Izin Santri (Keamanan)
        </h2>
    </x-slot>

    <div class="py-6 px-4 sm:px-6 lg:px-8">
        <div class="max-w-6xl mx-auto">

            {{-- FILTER & SEARCH --}}
            <div class="mb-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                <form action="{{ route('izin.keamanan.index') }}" method="GET" class="flex gap-2 flex-wrap">
                    <input type="text" name="q" placeholder="Cari NIS / Nama" 
                        value="{{ request('q') }}" 
                        class="border rounded px-2 py-1 text-sm">
                    
                    <select name="status" class="border rounded px-2 py-1 text-sm">
                        <option value="">Semua Status</option>
                        <option value="pending_keamanan" @selected(request('status')=='pending_keamanan')>Pending</option>
                        <option value="disetujui_keamanan" @selected(request('status')=='disetujui_keamanan')>Disetujui</option>
                        <option value="ditolak_keamanan" @selected(request('status')=='ditolak_keamanan')>Ditolak</option>
                    </select>

                    <select name="status_denda" class="border rounded px-2 py-1 text-sm">
                        <option value="">Semua Denda</option>
                        <option value="belum_dibayar" @selected(request('status_denda')=='belum_dibayar')>Belum Bayar</option>
                        <option value="dibayar" @selected(request('status_denda')=='dibayar')>Sudah Bayar</option>
                    </select>

                    <button type="submit" class="px-3 py-1 bg-blue-600 text-white rounded text-sm">
                        Cari / Filter
                    </button>
                </form>
            </div>

            {{-- TABLE --}}
            <div class="bg-white dark:bg-gray-800 shadow-xl rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700">
                <div class="p-5 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100 flex items-center gap-2">
                        📋 Daftar Pengajuan Izin
                    </h3>
                </div>

                <div class="overflow-x-auto p-4">
                    <table class="w-full text-sm border-collapse">
                        <thead class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 uppercase text-xs">
                            <tr>
                                <th class="px-3 py-2">NIS</th>
                                <th class="px-3 py-2">Nama Santri</th>
                                <th class="px-3 py-2">Jenis Izin</th>
                                <th class="px-3 py-2">Durasi</th>
                                <th class="px-3 py-2">Status</th>
                                <th class="px-3 py-2">Lapor</th>
                                <th class="px-3 py-2">Denda</th>
                                <th class="px-3 py-2 text-center">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse ($izinList as $izin)
                                @php
                                    $kelas = $izin->santri->kelas->tingkat ?? '';
                                    $namaKelas = $izin->santri->kelas->nama_kelas ?? '';
                                    $jurusan = $izin->santri->kelas->jurusan ?? '';
                                    $jenjang = strtolower($izin->santri->kelas->jenjang ?? '');
                                    $namaLengkap = match($jenjang) {
                                        'smp' => "{$kelas} {$namaKelas}",
                                        'sma' => "{$kelas} {$jurusan} {$namaKelas}",
                                        'smk' => "{$kelas} {$namaKelas} {$jurusan}",
                                        default => "{$kelas} {$namaKelas} {$jurusan}",
                                    };

                                    $dendaAktual = $izin->status_lapor == 'sudah_lapor'
                                        ? $izin->denda
                                        : $izin->denda_berjalan;

                                    $warnaDenda = 'text-gray-400';
                                    if ($dendaAktual > 0) {
                                        $warnaDenda = $izin->status_denda == 'dibayar'
                                            ? 'text-green-600'
                                            : 'text-red-600';
                                    }

                                    $isBelumBayar = ($izin->status_denda == 'belum_dibayar' && $dendaAktual > 0);
                                @endphp

                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/40 transition">
                                    <td class="px-3 py-2">{{ $izin->santri->nis }}</td>
                                    <td class="px-3 py-2 font-semibold text-gray-800 dark:text-gray-100">
                                        {{ $izin->santri->nama }} ({{ $namaLengkap }})
                                    </td>
                                    <td class="px-3 py-2">
                                        <button x-data @click="$dispatch('open-izin-{{ $izin->id }}')"
                                            class="text-blue-600 hover:text-blue-800 flex items-center gap-1 text-sm">
                                            {{ $izin->jenis_izin }} 🔍
                                        </button>

                                        {{-- MODAL --}}
                                        <div x-data="{open:false}" x-on:open-izin-{{ $izin->id }}.window="open=true"
                                            x-show="open" class="fixed inset-0 bg-black/40 flex items-center justify-center z-50" x-cloak>
                                            <div class="bg-white rounded-lg p-5 w-80 shadow-xl">
                                                <h2 class="text-lg font-bold mb-3">📄 Alasan Izin</h2>
                                                <p class="text-gray-700 mb-4">{{ $izin->alasan ?? 'Tidak ada alasan.' }}</p>
                                                <button @click="open=false" class="w-full py-2 bg-blue-600 text-white rounded-lg">
                                                    Tutup
                                                </button>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-3 py-2">{{ $izin->status == 'disetujui_keamanan' ? $izin->durasi : '-' }}</td>
                                    <td class="px-3 py-2">
                                        <span class="px-2 py-1 rounded text-xs font-semibold
                                            @if($izin->status=='pending_keamanan') bg-blue-100 text-blue-800
                                            @elseif($izin->status=='disetujui_keamanan') bg-green-100 text-green-800
                                            @else bg-red-100 text-red-800 @endif">
                                            {{ ucfirst(str_replace('_',' ', $izin->status)) }}
                                        </span>
                                    </td>
                                    <td class="px-3 py-2">
                                        @if($izin->status == 'disetujui_keamanan')
                                            @if($izin->status_lapor == 'sudah_lapor')
                                                <span class="px-3 py-1 bg-green-600 text-white rounded text-xs">✔ Sudah</span>
                                            @else
                                                <form action="{{ route('izin.keamanan.lapor', $izin->id) }}" method="POST">
                                                    @csrf
                                                    <button class="px-3 py-1 bg-red-600 text-white rounded text-xs">Belum</button>
                                                </form>
                                            @endif
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-3 py-2 font-bold">
                                        <span @if($isBelumBayar) x-data @click="$dispatch('bayar-{{ $izin->id }}')" class="cursor-pointer {{ $warnaDenda }}" @else class="{{ $warnaDenda }}" @endif>
                                            {{ $dendaAktual > 0 ? 'Rp '.number_format($dendaAktual,0,',','.') : '-' }}
                                        </span>

                                        @if($isBelumBayar)
                                        <div x-data="{open:false}" x-on:bayar-{{ $izin->id }}.window="open=true"
                                            x-show="open" x-cloak class="fixed inset-0 bg-black/40 flex items-center justify-center z-50">
                                            <div class="bg-white p-5 rounded-lg shadow-lg w-80">
                                                <h2 class="text-lg font-bold mb-3">💰 Konfirmasi Pembayaran</h2>
                                                <p class="mb-4">Bayar denda sebesar <b>Rp {{ number_format($dendaAktual,0,',','.') }}</b> ?</p>
                                                <form action="{{ route('izin.keamanan.bayar', $izin->id) }}" method="POST">@csrf
                                                    <button class="w-full py-2 bg-green-600 text-white rounded mb-2">Setujui Pembayaran</button>
                                                </form>
                                                <button @click="open=false" class="w-full py-2 bg-gray-400 text-white rounded">Batal</button>
                                            </div>
                                        </div>
                                        @endif
                                    </td>
                                    <td class="px-3 py-2 text-center">
                                        @if($izin->status == 'pending_keamanan')
                                        <div class="flex flex-col sm:flex-row gap-2 justify-center">
                                            <form action="{{ route('izin.keamanan.approve', $izin->id) }}" method="POST" class="flex gap-1">@csrf
                                                <input type="date" name="tgl_mulai_disetujui" class="border rounded px-2 py-1 text-xs">
                                                <input type="date" name="tgl_selesai_disetujui" class="border rounded px-2 py-1 text-xs">
                                                <button class="px-3 py-1 bg-green-600 text-white rounded text-xs">✔</button>
                                            </form>
                                            <form action="{{ route('izin.keamanan.reject', $izin->id) }}" method="POST" class="flex gap-1">@csrf
                                                <input type="text" name="catatan" placeholder="Catatan" class="border rounded px-2 py-1 text-xs">
                                                <button class="px-3 py-1 bg-red-600 text-white rounded text-xs">✘</button>
                                            </form>
                                        </div>
                                        @else
                                        <span class="text-gray-400 text-xs">Selesai</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="px-6 py-4 text-center text-gray-500">
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
