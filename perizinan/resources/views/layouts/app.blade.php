<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Aplikasi Perizinan Santri</title>

  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Montserrat:wght@500;600;700&display=swap" rel="stylesheet">
  @vite(['resources/css/app.css', 'resources/js/app.js'])

  <style>
    body {
        font-family: 'Inter', sans-serif;
    }
    h1, h2, h3, h4, h5, h6 {
        font-family: 'Montserrat', sans-serif;
    }
  </style>
</head>
<body>

  <!-- Sidebar -->
  <nav>
    <div class="logo">
      <div class="logo-image">
        <img src="{{ asset('image/altie.png') }}" alt="Logo">
      </div>
      <span class="nama">Al-ITTIHAD</span>
    </div>

    <div class="menu-items">
      <ul class="nav-link">
        <li>
          <a href="{{ route('dashboard') }}">
            <i class="fa-solid fa-house"></i>
            <span class="link-name">Dashboard</span>
          </a>
        </li>

        {{-- Role: KEAMANAN --}}
        @if(Auth::user()->role === 'keamanan')
          <li><a href="{{ route('kelas.index') }}"><i class="fa-solid fa-graduation-cap"></i><span class="link-name">Data Kelas</span></a></li>
          <li><a href="{{ route('santri.index') }}"><i class="fa-solid fa-users"></i><span class="link-name">Data Santri</span></a></li>
          <li><a href="{{ route('users.index') }}"><i class="fa-solid fa-user-tie"></i><span class="link-name">Data User</span></a></li>
          <li><a href="{{ route('izin.keamanan.index') }}"><i class="fa-solid fa-door-open"></i><span class="link-name">Data Izin Santri</span></a></li>
          <li><a href="{{ route('laporan.index') }}"><i class="fa-solid fa-file-alt"></i><span class="link-name">Laporan</span></a></li>
        @endif

        {{-- Role: WALI KELAS --}}
        @if(Auth::user()->role === 'wali_kelas')
          <li><a href="{{ route('kelas.index') }}"><i class="fa-solid fa-graduation-cap"></i><span class="link-name">Data Kelas</span></a></li>
          <li><a href="{{ route('santri.index') }}"><i class="fa-solid fa-users"></i><span class="link-name">Data Santri</span></a></li>
          <li><a href="{{ route('izin.walikelas.index') }}"><i class="fa-solid fa-door-open"></i><span class="link-name">Data Izin Santri</span></a></li>
        @endif

        {{-- Role: WALI SANTRI --}}
        @if(Auth::user()->role === 'wali_santri')
          <li><a href="{{ route('izin.walisantri.index') }}"><i class="fa-solid fa-door-open"></i><span class="link-name">Data Izin Santri</span></a></li>
        @endif
      </ul>

      <ul class="logout-mode">
        <li>
          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full text-left flex items-center gap-2">
              <i class="fa-solid fa-right-from-bracket"></i>
              <span class="link-name">Logout</span>
            </button>
          </form>
        </li>
        <li class="mode">
          <i class="fa-solid fa-moon"></i>
          <span class="link-name">Dark Mode</span>
          <div class="mode-toggle">
            <span class="switch"></span>
          </div>
        </li>
      </ul>
    </div>
  </nav>

  <!-- Overlay untuk mobile -->
  <div class="sidebar-overlay"></div>

  <!-- Dashboard -->
  <section class="dashboard">
    <div class="top">
      <i class="fa-solid fa-bars sidebar-toggle"></i>
      <a href="{{ route('profile') }}">
         <img src="{{ asset('image/amni.png') }}" alt="Logo Kecil" class="cursor-pointer hover:opacity-80 transition">
      </a>

    </div>

    <div class="dash-content">
      @isset($header)
        <div class="mb-6 border-b pb-3">
          {{ $header }}
        </div>
      @endisset

      {{-- INI BAGIAN PENTING: untuk halaman <x-app-layout> --}}
      {{ $slot }}
    </div>
  </section>

  <script src="//unpkg.com/alpinejs" defer></script>
  <script src="{{ asset('js/script.js') }}"></script>
</body>
</html>
