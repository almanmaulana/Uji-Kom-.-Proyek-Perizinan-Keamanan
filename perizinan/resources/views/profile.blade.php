<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-200 flex items-center gap-2">
            <i class="fa-solid fa-user-circle text-blue-400"></i>
            Profil Saya
        </h2>
    </x-slot>

    <div class="py-10 bg-[#2a2a2a] min-h-screen text-gray-200">
        <div class="max-w-5xl mx-auto px-4 flex flex-col gap-8">

            {{-- ALERT --}}
            @if(session('success'))
                <div class="bg-green-600 text-white p-3 rounded-lg shadow">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                {{-- FOTO PROFIL --}}
                <div class="bg-[#3a3a3a] p-6 rounded-2xl shadow-lg flex flex-col items-center">
                    <div class="relative">
                        <img src="{{ $user->photo ? asset('storage/'.$user->photo) : asset('image/user.png') }}"
                             class="w-40 h-40 rounded-xl object-cover border border-gray-600 shadow-lg">

                        <form method="POST" action="{{ route('profile.photo') }}" enctype="multipart/form-data"
                              class="mt-4">
                            @csrf
                            <label class="block">
                                <input type="file" name="photo" class="text-sm text-gray-300">
                            </label>
                            <button class="mt-3 bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-lg text-white">
                                Ubah Foto
                            </button>
                        </form>
                    </div>
                </div>

                {{-- FORM INFORMASI PROFIL --}}
                <div class="bg-[#3a3a3a] p-6 rounded-2xl shadow-lg md:col-span-2">
                    <h3 class="text-xl font-bold mb-4 flex items-center gap-2">
                        <i class="fa-solid fa-id-card text-blue-400"></i> Informasi Akun
                    </h3>

                    <form method="POST" action="{{ route('profile.update') }}">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label>Nama</label>
                                <input type="text" name="nama" value="{{ $user->nama }}"
                                       class="w-full px-3 py-2 rounded bg-[#2a2a2a] border border-gray-700 text-gray-200">
                            </div>

                            <div>
                                <label>Email</label>
                                <input type="email" name="email" value="{{ $user->email }}"
                                       class="w-full px-3 py-2 rounded bg-[#2a2a2a] border border-gray-700 text-gray-200">
                            </div>

                            <div>
                                <label>No HP</label>
                                <input type="text" name="no_hp" value="{{ $user->no_hp }}"
                                       class="w-full px-3 py-2 rounded bg-[#2a2a2a] border border-gray-700 text-gray-200">
                            </div>

                            <div>
                                <label>Kode Keluarga (KK)</label>

                                @if($user->role === 'wali_santri')
                                    <input type="text" value="{{ $user->kode_keluarga }}" readonly
                                           class="w-full px-3 py-2 bg-gray-700 text-gray-400 rounded">
                                    <p class="text-red-400 text-sm">Wali santri tidak dapat mengubah kode keluarga.</p>
                                @else
                                    <input type="text" name="kode_keluarga" value="{{ $user->kode_keluarga }}"
                                           class="w-full px-3 py-2 rounded bg-[#2a2a2a] border border-gray-700">
                                @endif
                            </div>
                        </div>

                        <button class="mt-4 bg-green-600 hover:bg-green-700 px-5 py-2 rounded-lg text-white">
                            Simpan Perubahan
                        </button>
                    </form>
                </div>
            </div>

            {{-- FORM PASSWORD --}}
            <div class="bg-[#3a3a3a] p-6 rounded-2xl shadow-lg">
                <h3 class="text-xl font-bold mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-lock text-blue-400"></i> Ubah Password
                </h3>

                <form method="POST" action="{{ route('profile.password') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    @csrf

                    <input type="password" name="password" placeholder="Password baru"
                           class="px-3 py-2 rounded bg-[#2a2a2a] border border-gray-700 text-gray-200">

                    <input type="password" name="password_confirmation" placeholder="Konfirmasi password"
                           class="px-3 py-2 rounded bg-[#2a2a2a] border border-gray-700 text-gray-200">

                    <button class="bg-yellow-600 hover:bg-yellow-700 px-4 py-2 rounded-lg text-white">
                        Update Password
                    </button>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>
