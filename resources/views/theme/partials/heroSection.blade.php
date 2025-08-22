{{-- Page Title --}}
<style>
:root{
  --pt-accent:#56cfe1;           /* سماوي هادئ */
  --pt-dark:#0f172a;
  --pt-muted:#64748b;
}

/* ===== Wrapper ===== */
.page-title{ margin:0; padding:0; background:transparent; }

/* ===== Heading ===== */
.page-title .heading{
  position:relative; text-align:center; color:#fff;
  padding: clamp(60px, 8vw, 90px) 0 clamp(40px,5vw,60px);
  border-radius:0 0 24px 24px;
  box-shadow:0 8px 22px rgba(2,6,23,.08);
  background: linear-gradient(135deg,#4a90e2,#81c4ff);
  overflow:hidden;
}
.page-title .heading::before{
  content:""; position:absolute; inset:0; z-index:1;
  background: linear-gradient(180deg, rgba(0,0,0,.35), rgba(0,0,0,.18) 60%, rgba(0,0,0,.08));
}
.page-title .heading .container{ position:relative; z-index:2; }

.page-title h1{
  font-weight:800; font-size:clamp(1.8rem, 3vw, 2.4rem);
  margin-bottom:10px; letter-spacing:.3px;
  text-shadow:0 2px 6px rgba(0,0,0,.18);
}
.page-title p{
  color:rgba(255,255,255,.9);
  font-size:clamp(1rem, 1.2vw, 1.1rem);
  max-width:700px; margin:0 auto; line-height:1.6;
}

/* ===== Breadcrumbs ===== */
.page-title .breadcrumbs{
  background:#fff; padding:12px 0;
  border-top:1px solid #f1f5f9; border-bottom:1px solid #f1f5f9;
}
.page-title .breadcrumbs ol{
  margin:0; padding:0; list-style:none;
  display:flex; flex-wrap:wrap; gap:6px; justify-content:center;
}
.page-title .breadcrumbs li{
  font-size:.95rem; font-weight:500; color:var(--pt-muted);
  display:flex; align-items:center;
}
.page-title .breadcrumbs li:not(:last-child)::after{
  content:"›"; margin:0 6px; color:#cbd5e1; font-size:.9rem;
}
.page-title .breadcrumbs li a{
  color:var(--pt-dark); font-weight:600; text-decoration:none;
  transition:.2s;
}
.page-title .breadcrumbs li a:hover{ color:var(--pt-accent); }
.page-title .breadcrumbs li.current{
  color:var(--pt-accent); font-weight:700;
  background:rgba(86,207,225,.12); padding:2px 8px; border-radius:6px;
}
</style>

<!-- Page Title -->
<div class="page-title" data-aos="fade">
  <div class="heading">
    <div class="container">
      <div class="row justify-content-center text-center">
        <div class="col-lg-8">
          <h1>{{ $title ?? 'Page Title' }}</h1>
          <p class="mb-0">{{ $description ?? 'This is the ' . $title . ' page' }}</p>
        </div>
      </div>
    </div>
  </div>

  <nav class="breadcrumbs">
    <div class="container">
      <ol>
        <li><a href="{{ route('theme.index') }}">الرئيسية</a></li>
        @if(isset($breadcrumbs))
          @foreach($breadcrumbs as $breadcrumb)
            @if(!$loop->last)
              <li><a href="{{ $breadcrumb['url'] }}">{{ $breadcrumb['title'] }}</a></li>
            @else
              <li class="current">{{ $breadcrumb['title'] }}</li>
            @endif
          @endforeach
        @else
          <li class="current">{{ $current ?? $title ?? 'Current Page' }}</li>
        @endif
      </ol>
    </div>
  </nav>
</div>
