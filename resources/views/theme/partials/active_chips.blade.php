{{-- resources/views/theme/partials/active_chips.blade.php --}}
@php
  $rq = request();
  $countries = isset($countries) ? $countries : collect();
  $classifications = isset($classifications) ? $classifications : collect();

  $genderMap = ['male' => 'ذكر', 'female' => 'أنثى'];
  $typeMap   = ['language'=>'لغات','tech'=>'تقنية','music'=>'موسيقى','art'=>'فن','academic'=>'أكاديمي'];
@endphp

<div class="active-chips d-flex flex-wrap gap-2">
  {{-- بحث --}}
  @if($rq->filled('search'))
    <span class="chip-active">
      بحث: {{ $rq->get('search') }}
      <a href="{{ $rq->fullUrlWithQuery(['search'=>null,'page'=>null]) }}" data-ajax-link aria-label="حذف البحث">
        <i class="bi bi-x"></i>
      </a>
    </span>
  @endif

  {{-- النوع --}}
  @php $tVal = $rq->get('type'); @endphp
  @if(!is_null($tVal) && $tVal!=='')
    <span class="chip-active">
      النوع: {{ $typeMap[$tVal] ?? $tVal }}
      <a href="{{ $rq->fullUrlWithQuery(['type'=>'','page'=>null]) }}" data-ajax-link aria-label="حذف النوع">
        <i class="bi bi-x"></i>
      </a>
    </span>
  @endif

  {{-- الشارات --}}
  @foreach((array)$rq->input('badges', []) as $b)
    <span class="chip-active">
      شارة: {{ $b }}
      <a href="{{ $rq->fullUrlWithQuery([
            'badges'=>collect($rq->input('badges'))->reject(fn($x)=>$x===$b)->values()->all(),
            'page'=>null
          ]) }}" data-ajax-link aria-label="حذف الشارة">
        <i class="bi bi-x"></i>
      </a>
    </span>
  @endforeach

  {{-- الجنس --}}
  @foreach((array)$rq->input('gender', []) as $g)
    <span class="chip-active">
      الجنس: {{ $genderMap[$g] ?? $g }}
      <a href="{{ $rq->fullUrlWithQuery([
            'gender'=>collect($rq->input('gender'))->reject(fn($x)=>$x===$g)->values()->all(),
            'page'=>null
          ]) }}" data-ajax-link aria-label="حذف الجنس">
        <i class="bi bi-x"></i>
      </a>
    </span>
  @endforeach

  {{-- الدول --}}
  @foreach((array)$rq->input('countries', []) as $c)
    @php $cn = optional($countries->firstWhere('id',$c))->name; @endphp
    <span class="chip-active">
      الدولة: {{ $cn ?? $c }}
      <a href="{{ $rq->fullUrlWithQuery([
            'countries'=>collect($rq->input('countries'))->reject(fn($x)=>$x==$c)->values()->all(),
            'page'=>null
          ]) }}" data-ajax-link aria-label="حذف الدولة">
        <i class="bi bi-x"></i>
      </a>
    </span>
  @endforeach

  {{-- الفئات --}}
  @foreach((array)$rq->input('classifications', []) as $cl)
    @php $cln = optional($classifications->firstWhere('id',$cl))->name; @endphp
    <span class="chip-active">
      الفئة: {{ $cln ?? $cl }}
      <a href="{{ $rq->fullUrlWithQuery([
            'classifications'=>collect($rq->input('classifications'))->reject(fn($x)=>$x==$cl)->values()->all(),
            'page'=>null
          ]) }}" data-ajax-link aria-label="حذف الفئة">
        <i class="bi bi-x"></i>
      </a>
    </span>
  @endforeach
</div>
