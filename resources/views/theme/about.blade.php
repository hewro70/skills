@extends('theme.master')
@section('about-active', 'active')

@section('content')
  <main class="main">

    {{-- Hero (اختياري) --}}
    
    @include('theme.partials.heroSection', [
      'title'       => __('about.hero.title'),
      'description' => __('about.hero.subtitle'),
      'current'     => __('about.hero.current'),
      'bgImage'     => asset('img/hero-about.jpg'),
      'height'      => 'sm',
      'overlay'     => 'auto',
    ])
  

    <!-- About Us Section -->
    <section id="about-us" class="section about-us mt-4">
      <div class="container">
        <div class="row gy-4">
          <div class="col-lg-6 order-1 order-lg-2" data-aos="fade-up" data-aos-delay="100">
            <img src="{{ asset('assets/img/about-2.jpg') }}" class="img-fluid" alt="{{ __('about.image_alt') }}">
          </div>

          <div class="col-lg-6 order-2 order-lg-1 content" data-aos="fade-up" data-aos-delay="200">
            <h3>{{ __('about.title') }}</h3>
            <p class="fst-italic">
              {{ __('about.lead') }}
            </p>
            <ul>
              <li><i class="bi bi-check-circle"></i> <span>{{ __('about.points.1') }}</span></li>
              <li><i class="bi bi-check-circle"></i> <span>{{ __('about.points.2') }}</span></li>
              <li><i class="bi bi-check-circle"></i> <span>{{ __('about.points.3') }}</span></li>
            </ul>
          </div>
        </div>
      </div>
    </section>

    <!-- Testimonials Section -->
    <!--<section id="testimonials" class="testimonials section">-->
    <!--  <div class="container section-title" data-aos="fade-up">-->
    <!--    <h2>{{ __('about.testimonials.title') }}</h2>-->
    <!--    <p>{{ __('about.testimonials.subtitle') }}</p>-->
    <!--  </div>-->

    <!--  <div class="container" data-aos="fade-up" data-aos-delay="100">-->
    <!--    <div class="swiper init-swiper">-->
    <!--      <script type="application/json" class="swiper-config">-->
    <!--      {-->
    <!--        "loop": true,-->
    <!--        "speed": 600,-->
    <!--        "autoplay": { "delay": 5000 },-->
    <!--        "slidesPerView": "auto",-->
    <!--        "pagination": { "el": ".swiper-pagination", "type": "bullets", "clickable": true },-->
    <!--        "breakpoints": {-->
    <!--          "320":   { "slidesPerView": 1, "spaceBetween": 40 },-->
    <!--          "1200":  { "slidesPerView": 2, "spaceBetween": 20 }-->
    <!--        }-->
    <!--      }-->
    <!--      </script>-->

    <!--      <div class="swiper-wrapper">-->
    <!--        {{-- مثال عنصر تقييم (كرر أو بدّل بداتا ديناميكية) --}}-->
    <!--        <div class="swiper-slide">-->
    <!--          <div class="testimonial-wrap">-->
    <!--            <div class="testimonial-item">-->
    <!--              <img src="{{ asset('assets/img/testimonials/testimonials-1.jpg') }}" class="testimonial-img" alt="">-->
    <!--              <h3>{{ __('about.testimonials.items.0.name') }}</h3>-->
    <!--              <h4>{{ __('about.testimonials.items.0.role') }}</h4>-->
    <!--              <div class="stars">-->
    <!--                <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>-->
    <!--              </div>-->
    <!--              <p>-->
    <!--                <i class="bi bi-quote quote-icon-left"></i>-->
    <!--                <span>{{ __('about.testimonials.items.0.text') }}</span>-->
    <!--                <i class="bi bi-quote quote-icon-right"></i>-->
    <!--              </p>-->
    <!--            </div>-->
    <!--          </div>-->
    <!--        </div>-->

    <!--        <div class="swiper-slide">-->
    <!--          <div class="testimonial-wrap">-->
    <!--            <div class="testimonial-item">-->
    <!--              <img src="{{ asset('assets/img/testimonials/testimonials-2.jpg') }}" class="testimonial-img" alt="">-->
    <!--              <h3>{{ __('about.testimonials.items.1.name') }}</h3>-->
    <!--              <h4>{{ __('about.testimonials.items.1.role') }}</h4>-->
    <!--              <div class="stars">-->
    <!--                <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>-->
    <!--              </div>-->
    <!--              <p>-->
    <!--                <i class="bi bi-quote quote-icon-left"></i>-->
    <!--                <span>{{ __('about.testimonials.items.1.text') }}</span>-->
    <!--                <i class="bi bi-quote quote-icon-right"></i>-->
    <!--              </p>-->
    <!--            </div>-->
    <!--          </div>-->
    <!--        </div>-->
    <!--      </div>-->

    <!--      <div class="swiper-pagination"></div>-->
    <!--    </div>-->
    <!--  </div>-->
    <!--</section>-->
  </main>
@endsection
