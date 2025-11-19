<x-guest-layout>
    <<form method="POST" action="{{ route('register.walisantri.store') }}">
        @csrf

        <!-- Kode Keluarga -->
        <div>
            <x-input-label for="kode_keluarga" :value="__('Kode Keluarga')" />
            <x-text-input id="kode_keluarga" class="block mt-1 w-full" 
                          type="text" name="kode_keluarga" 
                          :value="old('kode_keluarga')" 
                          required autofocus 
                          placeholder="Masukkan kode keluarga dari pihak pondok" />
            <x-input-error :messages="$errors->get('kode_keluarga')" class="mt-2" />
        </div>

        <!-- Nama -->
        <div class="mt-4">
            <x-input-label for="nama" :value="__('Nama Wali Santri')" />
            <x-text-input id="nama" class="block mt-1 w-full" 
                          type="text" name="nama" 
                          :value="old('nama')" required autocomplete="name" />
            <x-input-error :messages="$errors->get('nama')" class="mt-2" />
        </div>

        <!-- Email -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" 
                          type="email" name="email" 
                          :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Nomor HP -->
        <div class="mt-4">
            <x-input-label for="no_hp" :value="__('Nomor HP (Opsional)')" />
            <x-text-input id="no_hp" class="block mt-1 w-full" 
                          type="text" name="no_hp" 
                          :value="old('no_hp')" placeholder="08xxxxxxxxxx" />
            <x-input-error :messages="$errors->get('no_hp')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full"
                          type="password"
                          name="password"
                          required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Konfirmasi Password')" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                          type="password"
                          name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                {{ __('Sudah punya akun?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Daftar') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
