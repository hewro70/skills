@extends('theme.master')
@section('gamification-active','active')

@section('content')
<main class="main">

  {{-- Hero --}}
  @include('theme.partials.heroSection', [
    'title'       => trans('gamification.hero.title'),
    'description' => trans('gamification.hero.subtitle'),
    'current'     => trans('gamification.hero.current'),
    'height'      => 'sm',
    'overlay'     => 'auto',
  ])

  <section class="section py-4">
    <div class="container">
      <div class="d-flex flex-column align-items-center mb-3">
        <span class="badge rounded-pill bg-warning-subtle text-warning fw-semibold px-3 py-2">
          {{ trans('gamification.badge') }}
        </span>
      </div>

      @php
        // ✅ تجنّب 500 لو ملف الترجمة ناقص
        $cards = trans('gamification.cards');
        if (!is_array($cards)) { $cards = []; }

        $statusClass = [
          'coming_soon' => 'bg-success-subtle text-success',
          'in_dev'      => 'bg-info-subtle text-info',
          'planned'     => 'bg-secondary-subtle text-secondary',
          'beta'        => 'bg-primary-subtle text-primary',
        ];
      @endphp

      <div class="row g-3">
        @forelse($cards as $c)
          <div class="col-md-6 col-lg-4">
            <div class="feature-card h-100 border rounded-4 p-3">
              <div class="d-flex justify-content-between align-items-center mb-2">
                <div class="d-flex align-items-center gap-2">
                  <i class="bi {{ $c['icon'] ?? 'bi-award' }} fs-5"></i>
                  <h6 class="m-0 fw-bold">{{ $c['title'] ?? '' }}</h6>
                </div>
                @php $klass = $statusClass[$c['status'] ?? ''] ?? 'bg-light text-muted'; @endphp
                <span class="badge {{ $klass }}">
                  {{ trans('gamification.statuses.'.($c['status'] ?? 'coming_soon')) }}
                </span>
              </div>
              <p class="mb-0 text-muted small">{{ $c['desc'] ?? '' }}</p>
            </div>
          </div>
        @empty
          <div class="col-12 text-center text-muted">
            {{ __('No items') }}
          </div>
        @endforelse
      </div>

      <div class="text-center mt-4">
        <a href="{{ route('theme.index') }}" class="btn btn-outline-secondary">
          {{ trans('gamification.back') }}
        </a>
      </div>
    </div>
  </section>
</main>
@endsection

@push('styles')
<style>
  .feature-card{ background:#fff }
  .feature-card:hover{
    box-shadow:0 10px 24px rgba(2,6,23,.06);
    transform: translateY(-2px);
    transition:.2s;
  }
</style>
@endpush
