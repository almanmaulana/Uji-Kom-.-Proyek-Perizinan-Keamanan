<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-100 flex items-center gap-2">
            <i class="fa-solid fa-user-graduate text-blue-600 dark:text-blue-400"></i> Detail Santri
        </h2>
    </x-slot>

    <div class="py-10 bg-[#2a2a2a] min-h-screen transition-colors duration-300">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 flex flex-col gap-6">
            @php
                session(['return_to' => url()->previous()]);
            @endphp

            {{-- Kartu Profil --}}
            <div class="bg-[#2a2a2a] shadow-2xl rounded-2xl overflow-hidden flex flex-col lg:flex-row transition-colors duration-300 text-gray-100">

                {{-- Foto Sidebar --}}
                <div class="lg:w-1/3 bg-[#3a3a3a] flex flex-col items-center justify-center p-6">
                    @if($santri->foto)
                        <img src="{{ asset('storage/' . $santri->foto) }}" 
                             alt="Foto {{ $santri->nama }}" 
                             class="w-56 h-56 object-cover rounded-xl border border-gray-600 shadow-lg cursor-pointer"
                             onclick="document.getElementById('foto-modal').classList.remove('hidden')">
                    @else
                        <div class="w-56 h-56 flex items-center justify-center bg-gray-600 text-gray-400 rounded-xl border border-gray-600">
                            Tidak ada foto
                        </div>
                    @endif
                    <h3 class="mt-4 text-xl font-bold">{{ $santri->nama }}</h3>
                    <p class="text-gray-300">{{ $santri->nis }}</p>
                </div>

                {{-- Konten Info --}}
                <div class="lg:w-2/3 p-6 sm:p-8 flex flex-col gap-6">
                    {{-- Data Santri --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-3">
                            <h4 class="font-semibold border-b border-gray-600 pb-1"> <i class="fa-solid fa-id-card text-blue-500"></i> Data Pribadi</h4>
                            <p><strong>Nama:</strong> {{ $santri->nama }}</p>
                            <p><strong>NIS:</strong> {{ $santri->nis }}</p>
                            <p><strong>Jenis Kelamin:</strong> {{ $santri->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</p>
                            <p><strong>Kelas:</strong>
                                @if ($santri->kelas)
                                    @php $kelas = $santri->kelas; @endphp
                                    @if ($kelas->jenjang === 'SMA')
                                        {{ $kelas->tingkat }} {{ $kelas->jurusan }} {{ $kelas->nama_kelas }}
                                    @elseif ($kelas->jenjang === 'SMK')
                                        {{ $kelas->tingkat }} {{ $kelas->nama_kelas }} {{ $kelas->jurusan }}
                                    @elseif ($kelas->jenjang === 'SMP')
                                        {{ $kelas->tingkat }} {{ $kelas->nama_kelas }}
                                    @else
                                        {{ $kelas->tingkat }} {{ $kelas->nama_kelas }} {{ $kelas->jurusan }}
                                    @endif
                                @else
                                    -
                                @endif
                            </p>
                        </div>

                        <div class="space-y-3">
                            <h4 class="font-semibold border-b border-gray-600 pb-1"><i class="fa-solid fa-user mr-1 text-blue-500"></i> Wali Santri</h4>
                            <p><strong>Nama Wali:</strong> {{ $santri->waliSantri?->nama ?? '-' }}</p>
                            <p><strong>No HP:</strong> {{ $santri->waliSantri?->no_hp ?? '-' }}</p>
                            <p><strong>Alamat:</strong> {{ $santri->alamat ?? '-' }}</p>
                        </div>
                    </div>

                    {{-- Catatan --}}
                    <div class="bg-[#3a3a3a] p-4 rounded-lg shadow-inner">
                        <h4 class="font-semibold mb-2"><i class="fa-solid fa-file-lines mr-1 text-blue-500"></i> Catatan</h4>
                        <p>{{ $santri->catatan ?? '-' }}</p>
                    </div>
                </div>
            </div>

            {{-- Tombol Aksi di bawah --}}
            <div class="flex flex-wrap gap-3 justify-end">
                <a href="{{ session('return_to') }}"
                class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg shadow transition">
                ← Kembali
                </a>

                <a href="{{ route('santri.edit', ['santri' => $santri->id, 'return_to' => session('return_to')]) }}"
                class="flex items-center gap-2 bg-yellow-500 hover:bg-yellow-600 text-white px-5 py-2 rounded-lg shadow transition">
                    <i class="fa-solid fa-pen-to-square"></i> Edit
                </a>

            </div>
        </div>
    </div>


    <!-- Modal Foto -->
    <div id="foto-modal" 
        class="hidden fixed inset-0 z-50 bg-black/70 flex items-center justify-center overflow-auto p-4"
        onclick="this.classList.add('hidden')">
        <img src="{{ asset('storage/' . $santri->foto) }}" 
            alt="Foto {{ $santri->nama }}" 
            class="max-h-[90vh] max-w-[90vw] rounded-xl shadow-lg">
    </div>

</x-app-layout>
