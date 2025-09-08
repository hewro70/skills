<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">@php
  $TITLE = 'Maharat Hub';
  $DESC  = 'Connect with people globally to share knowledge, learn new skills, and exchange expertise for free.';
@endphp

<title>{{ $TITLE }}</title>
<meta name="description" content="{{ $DESC }}">
<meta name="keywords" content="Maharat Hub, skill exchange, share knowledge, online learning, free learning, community learning">

<link rel="icon" type="image/png" href="{{ asset('img/logo.png') }}">

<!-- Open Graph -->
<meta property="og:title" content="{{ $TITLE }}">
<meta property="og:description" content="{{ $DESC }}">
<meta property="og:image" content="{{ asset('img/logo.png') }}">
<meta property="og:url" content="{{ url('/') }}">
<meta property="og:type" content="website">
<meta property="og:locale" content="en_US">

<!-- Robots (يسمح بأطول سنِّبت ممكن) -->
<meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">


  {{-- مهم للـ Echo/Pusher عشان /broadcasting/auth --}}
<meta name="csrf-token" content="{{ csrf_token() }}">
@vite(['resources/js/app.js'])


  {{-- بقية رأس الصفحة (روابط Bootstrap/AOS… إلخ) --}}
  @include('theme.partials.head')

  {{-- ستايلات الصفحات الداخلية --}}
  @stack('styles')
</head>
<body class="index-page" style="color:#37423b!important;">

  @include('theme.partials.header')

  <main class="main">
    @yield('content')
  </main>

  @include('theme.partials.footer')

  <!-- Scroll Top -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center">
    <i class="bi bi-arrow-up-short"></i>
  </a>

  <!-- Preloader -->
  <div id="preloader"></div>

  {{-- سكربتات القالب (انتبه: لا تعيد تحميل app.js من هنا) --}}
  @include('theme.partials.scripts')

  {{-- سكربتات الصفحات الداخلية --}}
  @stack('scripts')
</body>
</html>
