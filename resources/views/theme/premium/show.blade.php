@extends('theme.master')

@section('content')
<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
      <div class="card shadow-sm">
        <div class="card-body p-4">
          <h3 class="mb-2 text-center">اشترك في البريميوم</h3>
          <p class="text-muted text-center mb-4">
            احصل على طلبات تبادل <b>غير محدودة</b> ومزايا إضافية.
          </p>

          <ul class="list-group mb-4">
            <li class="list-group-item d-flex align-items-center">
              <i class="bi bi-check-circle me-2"></i> طلبات تبادل غير محدودة
            </li>
            <li class="list-group-item d-flex align-items-center">
              <i class="bi bi-check-circle me-2"></i> أولوية أعلى في المطابقة
            </li>
            <li class="list-group-item d-flex align-items-center">
              <i class="bi bi-check-circle me-2"></i> دعم أسرع
            </li>
          </ul>

          {{-- لو عندك Stripe Cashier --}}
          @if(method_exists(auth()->user(), 'subscribed') && auth()->user()->subscribed('default'))
            <div class="alert alert-success">أنت مشترك حالياً في البريميوم ✅</div>
          @else
            {{-- مثال: رابط Checkout لو مفعّل Cashier --}}
            {{-- <form action="{{ route('checkout') }}" method="POST">@csrf
              <button class="btn btn-primary w-100">اشترك الآن</button>
            </form> --}}
            {{-- أو رابط بسيط للتجربة: --}}
            <a href="#" class="btn btn-primary w-100">اشترك الآن</a>
          @endif

          <div class="text-center mt-3">
            <a href="{{ url()->previous() }}" class="text-muted">رجوع</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
