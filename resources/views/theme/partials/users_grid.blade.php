{{-- talent list --}}
@push('styles')
<style>
  :root{
    --tl-border:#e5e7eb;
    --tl-soft:#f1f5f9;
    --tl-text:#1e293b;
    --tl-muted:#475569;
  }
  .talent-card{
    background:#fff; border:1px solid var(--tl-border); border-radius:12px;
    padding:16px; margin-bottom:16px;
    box-shadow:0 2px 8px rgba(0,0,0,.04);
    transition:transform .15s ease, box-shadow .15s ease;
  }
  .talent-card:hover{ transform:translateY(-2px); box-shadow:0 6px 16px rgba(0,0,0,.08); }
  .talent-header{ display:flex; align-items:center; gap:12px; }
  .talent-avatar{ width:64px; height:64px; border-radius:50%; object-fit:cover; border:2px solid var(--tl-soft); }
  .talent-info h4{ margin:0; font-size:1.1rem; font-weight:800; color:var(--tl-text); }
  .talent-langs{ margin:0; font-size:.9rem; color:var(--tl-muted); }
  .talent-location{ font-size:.85rem; color:#64748b; }
  .talent-stats{ display:flex; flex-wrap:wrap; gap:1rem; margin-top:.75rem; font-size:.9rem; color:#334155; }
  .talent-stats span i{ margin-inline-end:4px; }
  .talent-description{ margin-top:.75rem; font-size:.95rem; color:var(--tl-muted); line-height:1.6; }
  .talent-skills{ margin-top:.75rem; display:flex; flex-wrap:wrap; gap:.4rem; }
  .skill-tag{
    background:var(--tl-soft); color:var(--bs-primary); font-size:.8rem; font-weight:700;
    border-radius:999px; padding:.35rem .7rem; display:inline-flex; align-items:center; gap:.25rem;
  }
  .talent-actions{ margin-top:1rem; display:flex; gap:.5rem; flex-wrap:wrap; }
</style>
@endpush

@forelse ($users as $user)
  @php
    $rating = $user->avg_rating ?? null;           // إن وجد
    $ratingCount = $user->ratings_count ?? null;   // إن وجد
    $responseRate = $user->response_rate ?? null;  // إن وجد
  @endphp

  <div class="talent-card">
    {{-- Header --}}
    <div class="talent-header">
      <img src="{{ $user->getImageUrlAttribute() }}" alt="Talent" class="talent-avatar">
      <div class="talent-info">
        <h4 class="talent-name">{{ $user->fullName() }}</h4>

        {{-- لغات --}}
        <p class="talent-langs">
          @if($user->languages->count())
            @foreach ($user->languages as $language)
              <span>
                {{ $language->name }}
                ({{ $language->pivot->level ?? '—' }})
              </span>@if(!$loop->last) | @endif
            @endforeach
          @else
            <span class="text-muted">{{ __('talent.no_languages') }}</span>
          @endif
        </p>

        {{-- دولة --}}
        <span class="talent-location">
          <i class="bi bi-geo-alt"></i>
          {{ $user->country->name ?? __('talent.location_unknown') }}
        </span>
      </div>
    </div>

    {{-- إحصائيات (تظهر فقط إذا فيه داتا) --}}
    <div class="talent-stats">
      @if($rating)
        <span>
          <i class="bi bi-star-fill text-warning"></i>
          {{ number_format($rating, 1) }}
          @if($ratingCount) ({{ $ratingCount }} {{ __('talent.reviews') }}) @endif
        </span>
      @endif

      @if($responseRate)
        <span>
          <i class="bi bi-check-circle-fill text-success"></i>
          {{ $responseRate }}% {{ __('talent.response_rate') }}
        </span>
      @endif
    </div>

    {{-- الوصف --}}
    <div class="talent-description">
      <p>{{ $user->about_me ?? __('talent.no_bio') }}</p>
    </div>

    {{-- المهارات --}}
    <div class="talent-skills">
      @forelse ($user->skills as $skill)
        <span class="skill-tag"><i class="bi bi-lightning-fill"></i> {{ $skill->name }}</span>
      @empty
        <span class="text-muted">{{ __('talent.no_skills') }}</span>
      @endforelse
    </div>

    {{-- الأكشن --}}
    <div class="talent-actions">
      <a href="{{ route('theme.profile.show', $user) }}" class="btn btn-sm btn-primary">
        <i class="bi bi-person-badge"></i> {{ __('talent.view_profile') }}
      </a>

      @if (auth()->check() && auth()->id() !== $user->id)
        <form action="{{ route('invitations.send') }}" method="POST" class="d-inline invitation-form"
              data-user-id="{{ $user->id }}" data-user-name="{{ $user->fullName() }}"
              id="invitation-form-{{ $user->id }}">
          @csrf
          <input type="hidden" name="destination_user_id" value="{{ $user->id }}">
          <button type="button" class="btn btn-sm btn-outline-primary send-invitation-btn">
            <i class="bi bi-send"></i> {{ __('talent.invite') }}
          </button>
        </form>
      @endif
    </div>
  </div>
@empty
  <div class="col-12 text-center py-5">
    <h4 class="text-muted">{{ __('talent.no_results') }}</h4>
  </div>
@endforelse
