@extends('theme.master')
@section('index-active', 'active')

@section('content')
@push('styles')
<style>
  /* خلفية الهيرو كما هي عندك */
  .home-hero{ min-height:90svh; position:relative; padding:clamp(90px,10vw,160px) 0 80px; color:#fff; }
  .home-hero .hero-bg{ position:absolute; inset:0; width:100%; height:100%; object-fit:cover; }
  .home-hero::before{ content:""; position:absolute; inset:0; background:
      radial-gradient(1000px 600px at 75% 10%, rgba(255,255,255,.10), transparent 70%),
      linear-gradient(180deg, rgba(0,0,0,.45), rgba(0,0,0,.25) 55%, rgba(0,0,0,.15)); }

  /* ===== شريط البحث المحسّن ===== */
  .hero-search{
    display:grid; grid-template-columns: 1fr 170px 140px; gap:.6rem;
    max-width:920px; margin-inline:auto;
    background:rgba(255,255,255,.10);
    border:1px solid rgba(255,255,255,.22);
    border-radius:999px; padding:.6rem;
    backdrop-filter:blur(12px) saturate(120%);
    -webkit-backdrop-filter:blur(12px) saturate(120%);
  }
  @media (max-width: 768px){
    .hero-search{ grid-template-columns:1fr; border-radius:20px; }
    .select-wrap{ display:none; }         /* نخفي الدروب داون على الموبايل */
    .btn-search{ width:100%; }
  }

  /* حقل البحث + أيقونة عدسة */
  .hero-search .field .form-control{
    background:rgba(255,255,255,.06); border:1px solid rgba(255,255,255,.18);
    border-radius:999px; color:#fff; padding:.9rem 1rem .9rem 3.5rem;
  }
  .hero-search .field .form-control::placeholder{ color:rgba(255,255,255,.9); }
  .hero-search .field .form-control:focus{ box-shadow:0 0 0 .25rem rgba(13,110,253,.35); outline:0; }

  .hero-search .icon-badge{
    position:absolute; inset-inline-start:.35rem; top:50%; transform:translateY(-50%);
    width:44px; height:44px; display:grid; place-items:center;
    background:rgba(255,255,255,.96); color:#0d6efd; border-radius:50%;
    box-shadow:0 10px 24px rgba(13,110,253,.22);
    pointer-events:none;
  }

  /* الدروب داون بشكل أنيق */
  .select-wrap{ position:relative; }
  .select-wrap select{
    appearance:none; -webkit-appearance:none; -moz-appearance:none;
    background:rgba(255,255,255,.12); color:#fff;
    border:1px solid rgba(255,255,255,.20); border-radius:999px;
    padding:.85rem 2.6rem .85rem 1rem;
  }
  .select-wrap::after{
    content:""; position:absolute; inset-inline-end:1rem; top:50%; transform:translateY(-50%);
    width:10px; height:10px;
    background: url("data:image/svg+xml,%3Csvg width='10' height='10' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1 3l4 4 4-4' stroke='%23FFFFFF' stroke-width='2' fill='none'/%3E%3C/svg%3E") no-repeat center/10px 10px;
    opacity:.9; pointer-events:none;
  }
  /* لون عناصر القائمة المنسدلة داخل النظام */
  .select-wrap select option{ color:#0d1b2a; }

  /* زر البحث */
  .btn-search{
    border-radius:999px; padding:.9rem 1.4rem; font-weight:700;
    background:linear-gradient(90deg, #ffffff, #f6fbff);
    color:#0d6efd; border:1px solid rgba(255,255,255,.7);
    transition:.15s ease-in-out;
  }
  .btn-search:hover{ background:#fff; transform:translateY(-1px);
    box-shadow:0 .75rem 1.5rem rgba(13,110,253,.25); }
</style>
@endpush




  {{-- Hero (Improved) --}}
<section class="home-hero position-relative overflow-hidden">
  <!-- خلفية تغطي القسم -->
  <img class="hero-bg" src="{{ asset('img/hero.jpg') }}" alt="خلفية مهارات هب">

  <div class="container">
    <div class="row justify-content-center">
      <div class="col-12 col-lg-10 text-center">

        <span class="badge bg-white text-primary rounded-pill px-3 py-2 mb-3 shadow-sm">
          مهارات هَب · مجتمع تبادل المهارات
        </span>

        <h1 class="display-4 fw-bolder mb-3">
          تبادل المهارات وأثــر المجتمع
        </h1>

        <p class="lead text-white-75 mb-4">
          تواصل مع المتعلمين والمتحمسين حول العالم. شارِك المعرفة مجاناً وتعلّم سوياً.
        </p>

        <!-- نفس الفنكشنالتي: GET إلى skills ومعه search -->
<form action="{{ route('theme.skills') }}" method="GET"
      class="hero-search" role="search" autocomplete="off" aria-label="بحث عن المهارات">

  <!-- حقل البحث -->
  <div class="field position-relative">
    <span class="icon-badge"><i class="bi bi-search"></i></span>
    <input type="text" name="search" class="form-control"
           placeholder="ابحَث عن مهارة أو موضوع…">
  </div>

  <!-- الدروب داون -->
  <div class="select-wrap d-none d-md-block">
    <select name="type" class="form-select" aria-label="نوع المهارة">
      <option value="">الكل</option>
      <option value="language">لغات</option>
      <option value="tech">تقنية</option>
      <option value="music">موسيقى</option>
      <option value="art">فن</option>
      <option value="academic">أكاديمي</option>
    </select>
  </div>

  <!-- زر البحث -->
  <button type="submit" class="btn btn-search">
    بحث
  </button>
</form>


        <!-- كلمات مقترحة -->
        <div class="d-flex flex-wrap justify-content-center gap-2 mt-3">
          @foreach (['اللغة الإنجليزية','برمجة','تصميم','جيتار','طبخ','رياضيات'] as $q)
            <a class="chip" href="{{ route('theme.skills', ['search' => $q]) }}">#{{ $q }}</a>
          @endforeach
        </div>

        <!-- إحصائيات شكلية -->
        <div class="row g-3 mt-4 justify-content-center">
          <div class="col-4 col-md-3">
            <div class="glass rounded-3 py-3">
              <div class="fs-4 fw-bold">2</div>
              <div class="small text-white-75">Most Popular</div>
            </div>
          </div>
          <div class="col-4 col-md-3">
            <div class="glass rounded-3 py-3">
              <div class="fs-4 fw-bold">3</div>
              <div class="small text-white-75">Skills Available</div>
            </div>
          </div>
          <div class="col-4 col-md-3">
            <div class="glass rounded-3 py-3">
              <div class="fs-4 fw-bold">4</div>
              <div class="small text-white-75">محتوى دُر</div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</section>

    <!-- /Why Skills Hub Section -->

    <!-- How It Works Section -->
    <section id="how-it-works" class="how-it-works section">
        <div class="container">
            <div class="section-title text-center mb-5">
                <h2>كيف تعمل مهارات هَب؟</h2>
                <p>انضم إلى منصتنا بثلاث خطوات بسيطة وابدأ بتبادل المهارات اليوم.</p>
            </div>

            <div class="row gy-4 justify-content-center" id="index-row">
                <!-- Step 1 -->
                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="step-box text-center h-100">
                        <div class="step-number bg-primary text-white mb-3">١</div>
                        <h3>أنشئ ملفك الشخصي</h3>
                        <p>سجّل وحدد المهارات التي يمكنك تعليمها للآخرين.</p>
                    </div>
                </div>

                <!-- Step 2 -->
                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="step-box text-center h-100">
                        <div class="step-number bg-primary text-white mb-3">٢</div>
                        <h3>تواصل مع الآخرين</h3>
                        <p>تصفح أو اعثر على الأشخاص الذين يريدون تعلم ما تعلمه ويمكنهم تعليمك ما تريد تعلمه.</p>
                    </div>
                </div>

                <!-- Step 3 -->
                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="step-box text-center h-100">
                        <div class="step-number bg-primary text-white mb-3">٣</div>
                        <h3>ابدأ بالتبادل</h3>
                        <p>تواصل، وحدد المواعيد، وابدأ بتبادل المعرفة والمهارات مع شريك التعلم الجديد الخاص بك.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /How It Works Section -->

    <!-- Updated Growing Community Section -->
    <section id="community" class="community section bg-light">
        <div class="container">
            <div class="row align-items-center" id="row-community-id">
                <!-- Image on the left -->
                <div class="col-lg-6 order-lg-1" data-aos="fade-right">
                    <img src="https://www.en.agraria.unina.it/images/2023/05/31/courses4.png" id="img-fluid"
                        class="img-fluid rounded" alt="مجتمع مهارات هب">
                </div>

                <!-- Content on the right -->
                <div class="col-lg-6 order-lg-2" data-aos="fade-left">
                    <div class="pe-lg-5">
                        <h2 class="mb-4">انضم إلى مجتمعنا المتنامي</h2>
                        <p class="lead mb-4">مهارات هب هي أكثر من مجرد منصة - إنها مجتمع من المتعلمين والمعلمين المتحمسين
                            يجتمعون معًا لمشاركة المعرفة.</p>

                        <div class="community-features">
                            <div class="feature-item d-flex mb-4">
                                <div class="icon-box bg-primary text-white me-3 flex-shrink-0">
                                    <i class="bi bi-check2-circle"></i>
                                </div>
                                <div>
                                    <p class="mb-0 feature-text">تواصل مع أشخاص يفكرون بنفس طريقتك ويشاركونك اهتماماتك</p>
                                </div>
                            </div>

                            <div class="feature-item d-flex mb-4">
                                <div class="icon-box bg-primary text-white me-3 flex-shrink-0">
                                    <i class="bi bi-check2-circle"></i>
                                </div>
                                <div>
                                    <p class="mb-0 feature-text">شارك في فعاليات المجتمع وجلسات التعلم الجماعية</p>
                                </div>
                            </div>

                            <div class="feature-item d-flex">
                                <div class="icon-box bg-primary text-white me-3 flex-shrink-0">
                                    <i class="bi bi-check2-circle"></i>
                                </div>
                                <div>
                                    <p class="mb-0 feature-text">ابنِ علاقات ذات مغزى أثناء تنمية مهاراتك</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQs Section -->
    <section id="faq" class="faq section">
        <div class="container" data-aos="fade-up">

            <div class="section-title text-right">
                <h2 id="faq-header">الأسئلة الشائعة</h2>
                <p>احصل على إجابات للأسئلة الشائعة حول مهارات هب</p>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-9">

                    <div class="accordion" id="faqAccordion">

                        <!-- FAQ Item 1 -->
                        <div class="accordion-item">
                            <h3 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#faq1" style="color: #37423b !important">
                                    كيف يعمل تبادل المهارات؟
                                </button>
                            </h3>
                            <div id="faq1" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    تطابق مهارات هب المستخدمين بناءً على المهارات التكميلية. سيتم ربطك مع أشخاص يريدون تعلم
                                    ما يمكنك تعليمه، ويمكنهم تعليمك ما تريد تعلمه. يمكنك بعد ذلك ترتيب الجلسات إما افتراضيًا
                                    أو شخصيًا، حسب تفضيلاتك.
                                </div>
                            </div>
                        </div>

                        <!-- FAQ Item 2 -->
                        <div class="accordion-item">
                            <h3 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#faq2" style="color: #37423b !important">
                                    هل مهارات هب مجانية تماماً؟
                                </button>
                            </h3>
                            <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    نعم، جميع تبادلات المهارات على مهارات هب مجانية تمامًا - لأننا نؤمن بأن المعرفة يجب أن
                                    تُشارك بشكل مفتوح وبدون حواجز. ومع ذلك، لضمان استدامة المنصة واستمرار تطورها، تُقدم بعض
                                    الميزات المتقدمة وغير المحدودة حصريًا من خلال اشتراكنا المميز.
                                </div>
                            </div>
                        </div>

                        <!-- FAQ Item 3 -->
                        <div class="accordion-item">
                            <h3 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#faq3" style="color: #37423b !important">
                                    كيف أكسب وأستبدل النقاط؟
                                </button>
                            </h3>
                            <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    النقاط هي نظام مكافآت صُمّم لتعزيز تجربة التعلّم على منصتنا. يمكنك كسب النقاط من خلال
                                    إكمال تبادلات المهارات، وتسجيل الدخول اليومي، ودعوة الأصدقاء. ويمكنك استبدال هذه النقاط
                                    مقابل مزايا حصرية مثل الوصول إلى النسخة المدفوعة، وخصومات من شركائنا، وجوائز، أو حضور
                                    ندوات خاصة.
                                </div>
                            </div>
                        </div>

                        <!-- FAQ Item 4 -->
                        <div class="accordion-item">
                            <h3 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#faq4" style="color: #37423b !important">
                                    ماذا لو أردت تعليم شخص ما، ولكنه لا يستطيع تقديم أي شيء في المقابل؟
                                </button>
                            </h3>
                            <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    إذا كنت منفتحًا على التعليم دون توقع مقابل، فستحصل على 1.5 ضعف النقاط مقابل مشاركة
                                    معرفتك ومساعدة الآخرين. يمكنك تفعيل هذا الخيار من ملفك الشخصي، وسيعرض النظام الأشخاص
                                    الأنسب بناءً على تفضيلاتك. هذا لا يحد من قدرتك على المشاركة في التبادلات المتبادلة، بل
                                    يمنحك مرونة أكبر للمساعدة وكسب مكافآت إضافية.
                                </div>
                            </div>
                        </div>

                        <!-- FAQ Item 5 -->
                        <div class="accordion-item">
                            <h3 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#faq5" style="color: #37423b !important">
                                    كيف تتحقق مهارات هب من مهارات المستخدم؟
                                </button>
                            </h3>
                            <div id="faq5" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    نستخدم مجموعة من مراجعات الأقران وتقييمات المهارات وشارات التحقق. يمكن للمستخدمين كسب
                                    شارات من خلال إظهار مهاراتهم وتلقي ملاحظات إيجابية من شركاء التعلم. يخلق هذا مجتمعًا
                                    موثوقًا به من مشاركي المهارات المعتمدين.
                                </div>
                            </div>
                        </div>

                        <!-- FAQ Item 6 -->
                        <div class="accordion-item">
                            <h3 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#faq6" style="color: #37423b !important">
                                    متى سيتم إطلاق مهارات هب؟
                                </button>
                            </h3>
                            <div id="faq6" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    المنصة حالياً قيد التطوير بعد أن فازت الفكرة في هاكاثون رواد الأعمال 2024 من وزارة
                                    الاقتصاد الرقمي والريادة الأردنية. انضم إلى قائمة الانتظار لتكون من أوائل من يعرف عندما
                                    يتم الإطلاق وللحصول على وصول مبكر حصري.
                                </div>
                            </div>
                        </div>

                        <!-- FAQ Item 7 -->
                        <div class="accordion-item">
                            <h3 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#faq7" style="color: #37423b !important">
                                    ما نوع المهارات التي يمكن تبادلها؟
                                </button>
                            </h3>
                            <div id="faq7" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    يمكن تبادل أي مهارة تقريبًا على منصتنا! سواء كانت مهارات إبداعية مثل التصوير والموسيقى،
                                    أو مهارات تقنية مثل البرمجة والتسويق الرقمي، أو مهارات يومية مثل تعلم اللغات والطبخ
                                    واللياقة البدنية والمواد الأكاديمية - إذا كان بإمكانك تعليمها، يمكنك تبادلها.
                                </div>
                            </div>
                        </div>

                    </div>

                </div>
            </div>

        </div>
    </section>
    <!-- /FAQs Section -->

@endsection
