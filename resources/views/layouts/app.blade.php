<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  {{-- ===== Title + Meta ===== --}}
  <title>@yield('title', 'Maharat Hub')</title>
  <meta name="description" content="@yield('meta_description', 'Connect with people globally to share knowledge, learn new skills, and exchange expertise for free. تواصل مع أشخاص حول العالم لتشارك المعرفة، تتعلم مهارات جديدة، وتتبادل الخبرات مجانًا.')">
  <meta name="keywords" content="Maharat Hub, مهارات هب, skill exchange, share knowledge, online learning, free learning, community learning, learn skills, knowledge exchange, connect with people, global learning">

  <link rel="canonical" href="{{ url()->current() }}">

  {{-- ===== Favicon (مرّة واحدة) ===== --}}
  <link rel="icon" href="{{ url(asset('favicon.ico')) }}" sizes="any">
  <link rel="icon" type="image/png" sizes="32x32"  href="{{ url(asset('favicon-32x32.png')) }}">
  <link rel="icon" type="image/png" sizes="192x192" href="{{ url(asset('favicon-192x192.png')) }}">
  <link rel="apple-touch-icon" href="{{ url(asset('apple-touch-icon.png')) }}">

  {{-- ===== Open Graph ===== --}}
  <meta property="og:site_name"    content="Maharat Hub">
  <meta property="og:type"         content="website">
  <meta property="og:title"        content="@yield('og_title', 'Maharat Hub | مهارات هب')">
  <meta property="og:description"  content="@yield('og_description', 'Connect with people globally to share knowledge, learn new skills, and exchange expertise for free. تواصل مع أشخاص حول العالم لتشارك المعرفة، تتعلم مهارات جديدة، وتتبادل الخبرات مجانًا.')">
  <meta property="og:url"          content="{{ url()->current() }}">
<meta property="og:image" content="@yield('og_image', url('favicon-192x192.png'))">
  <meta property="og:locale"       content="en_US">
  <meta property="og:locale:alternate" content="ar_JO">

  {{-- ===== Twitter ===== --}}
  <meta name="twitter:card"        content="summary_large_image">
  <meta name="twitter:title"       content="@yield('og_title', 'Maharat Hub | مهارات هب')">
  <meta name="twitter:description" content="@yield('og_description', 'Connect with people globally to share knowledge, learn new skills, and exchange expertise for free. تواصل مع أشخاص حول العالم لتشارك المعرفة، تتعلم مهارات جديدة، وتتبادل الخبرات مجانًا.')">
<meta name="twitter:image" content="@yield('og_image', url('favicon-192x192.png'))">

  {{-- ===== Schema.org Organization ===== --}}
  <script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@type": "Organization",
    "name": "Maharat Hub",
    "alternateName": "مهارات هب",
    "url": "{{ url('/') }}",
"logo": "{{ url('favicon.ico') }}",
    "description": "Connect with people globally to share knowledge, learn new skills, and exchange expertise for free. تواصل مع أشخاص حول العالم لتشارك المعرفة، تتعلم مهارات جديدة، وتتبادل الخبرات مجانًا.",
    "sameAs": [
      "https://www.facebook.com/maharathub",
      "https://www.instagram.com/maharathub",
      "https://www.linkedin.com/company/maharathub"
    ]
  }
  </script>

  {{-- ===== Schema.org WebSite ===== --}}
  <script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@type": "WebSite",
    "url": "{{ url('/') }}",
    "name": "Maharat Hub",
    "potentialAction": {
      "@type": "SearchAction",
      "target": "{{ url('/') }}?q={search_term_string}",
      "query-input": "required name=search_term_string"
    }
  }
  </script>

  {{-- CSRF + Vite --}}
  <meta name="csrf-token" content="{{ csrf_token() }}">
  @vite(['resources/css/app.css', 'resources/js/app.js'])

  {{-- للسماح للصفحات تضيف تاغات إضافية --}}
  @stack('head')
</head>

<body class="font-sans antialiased">
  <div class="min-h-screen bg-gray-100">
    @include('layouts.navigation')

    <!-- Page Heading -->
    @if (isset($header))
      <header class="bg-white shadow">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
          {{ $header }}
        </div>
      </header>
    @endif

    <!-- Page Content -->
    <main>
      {{ $slot }}
    </main>
  </div>
</body>
</html>
