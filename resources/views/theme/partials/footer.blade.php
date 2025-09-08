<footer id="site-footer" class="footer border-top bg-white">
  <div class="container footer-top py-4 py-lg-5">
    <div class="row g-4 align-items-start">

      {{-- About / Contact / Social --}}
      <div class="col-lg-4 col-md-6">
        <a href="{{ route('theme.index') }}" class="logo d-inline-flex align-items-center text-decoration-none mb-2">
          <span class="sitename">{{ __('footer.brand') }}</span>
        </a>
        <!--@if (filled(__('footer.tagline')))-->
        <!--  <p class="text-muted mb-3 small">{{ __('footer.tagline') }}</p>-->
        <!--@endif-->

        <ul class="list-unstyled mb-3 small footer-contact">
          <!--<li class="mb-1">-->
          <!--  <strong class="me-1">{{ __('footer.contact.phone_label') }}:</strong>-->
          <!--  <a href="tel:****" class="text-reset text-decoration-none">****</a>-->
          <!--</li>-->
          <li>
            <strong class="me-1">{{ __('footer.contact.email_label') }}:</strong>
            <a href="mailto:info@maharathub.com" class="text-reset text-decoration-none">info@maharathub.com</a>
          </li>
        </ul>

        <div class="social-links d-flex flex-wrap gap-2 mt-2">
          <!--<a class="social-btn" href="#" aria-label="X / Twitter"><i class="bi bi-twitter-x"></i></a>-->
     <a class="social-btn"
   href="https://www.facebook.com/people/Maharat-Hub/61576152626321/"
   target="_blank" rel="noopener"
   aria-label="Facebook">
  <i class="bi bi-facebook"></i>
</a>
          <a class="social-btn" href="https://www.instagram.com/maharathub/" target="_blank" rel="noopener" aria-label="Instagram">
            <i class="bi bi-instagram"></i>
          </a>
          <a class="social-btn" href="https://www.linkedin.com/company/maharat-hub" target="_blank" rel="noopener" aria-label="LinkedIn">
            <i class="bi bi-linkedin"></i>
          </a>
        </div>
      </div>

      {{-- Useful Links --}}
      <div class="col-lg-8 col-md-6">
        <h4 class="h6 mb-3 fw-bold">{{ __('footer.useful_links') }}</h4>

        <ul class="footer-links list-unstyled row row-cols-2 row-cols-sm-3 g-2 m-0">
          <li><a href="{{ route('theme.index') }}">{{ __('footer.links.home') }}</a></li>
          <li><a href="{{ route('theme.about') }}">{{ __('footer.links.about') }}</a></li>
          <li><a href="{{ route('theme.skills') }}">{{ __('footer.links.skills') }}</a></li>
          <li><a href="{{ route('theme.contact') }}">{{ __('footer.links.contact') }}</a></li>
          <li><a href="{{ route('theme.termsOfServices') }}">{{ __('footer.links.terms') }}</a></li>
          <li><a href="{{ route('theme.privacyPolicy') }}">{{ __('footer.links.privacy') }}</a></li>
          <li><a href="{{ route('theme.index') }}#faq-header">{{ __('footer.links.faq') }}</a></li>
        </ul>
      </div>

    </div>
  </div>

  <div class="container text-center py-3 border-top">
    <small class="text-muted">
      {{ __('footer.copyright', ['year' => now()->year, 'brand' => __('footer.brand')]) }}
    </small>
  </div>
</footer>
@push('styles')
<style>
  #site-footer .sitename{ font-weight:800; font-size:1.15rem; color:var(--bs-primary); }
  #site-footer .footer-contact strong{ color:#111; }
  #site-footer .social-btn{
    width:38px; height:38px; display:inline-flex; align-items:center; justify-content:center;
    border-radius:50%; background:#f1f3f5; color:#111; transition:.2s ease; border:1px solid #e9ecef;
  }
  #site-footer .social-btn:hover{ background:var(--bs-primary); color:#fff; transform:translateY(-2px); }

  #site-footer .footer-links a{
    display:inline-block; padding:.35rem .25rem; border-radius:.5rem;
    text-decoration:none; color:#495057; transition:.15s ease;
  }
  #site-footer .footer-links a:hover{
    color:#111; background:rgba(0,0,0,.06);
  }

  /* دعم RTL/LTR للـ “underline” الانسيابي */
  [dir="rtl"] #site-footer .footer-links a{ padding-inline: .4rem; }
</style>
@endpush
@push('styles')
<style>
  /* === Footer Base === */
  #site-footer .sitename{ font-weight:800; font-size:1.15rem; color:var(--bs-primary); }
  #site-footer .footer-contact strong{ color:#111; }
  #site-footer .social-btn{
    width:38px; height:38px; display:inline-flex; align-items:center; justify-content:center;
    border-radius:50%; background:#f1f3f5; color:#111; transition:.2s ease; border:1px solid #e9ecef;
  }
  #site-footer .social-btn:hover{ background:var(--bs-primary); color:#fff; transform:translateY(-2px); }

  /* === Links (إلغاء underline الافتراضي من Bootstrap + تنسيق أنيق) === */
  #site-footer .footer-links a{
    position:relative;
    display:inline-block;
    padding:.35rem .25rem;
    border-radius:.5rem;
    text-decoration:none !important;   /* يلغي underline بكل الحالات */
    color:#495057;
    transition:color .15s ease, background-color .15s ease;
    outline:0;
    /* خط سفلي أنيق عبر background-size (RTL/LTR-friendly) */
    background-image: linear-gradient(currentColor, currentColor);
    background-position: 0% 100%;
    background-repeat: no-repeat;
    background-size: 0% 2px;           /* مخفي مبدئياً */
  }
  #site-footer .footer-links a:hover,
  #site-footer .footer-links a:focus {
    color:#111;
    background-color: rgba(0,0,0,.06);
    text-decoration:none !important;    /* يمنع Bootstrap من إعادة underline */
    background-size: 100% 2px;          /* يظهر الخط المتحرك */
  }

  /* دعم RTL: نعكس اتجاه خط الـ underline المتحرك */
  [dir="rtl"] #site-footer .footer-links a{
    padding-inline: .4rem;
    background-position: 100% 100%;
  }

  /* تحسين الـ focus للوصولية بدون underline */
  #site-footer .footer-links a:focus-visible{
    box-shadow: 0 0 0 .2rem rgba(13,110,253,.25);
    border-radius:.5rem;
  }

  /* تقليل الحركة عند تفضيل تقليل الحركة */
  @media (prefers-reduced-motion: reduce){
    #site-footer .footer-links a,
    #site-footer .social-btn{
      transition: none;
    }
  }
</style>
@endpush


