<div class="p-3">
  <div class="text-center mb-4">
    <img id="sidebarProfileImage" src="{{ $user->image_url }}" class="rounded-circle profile-img mb-3 hover-lift" alt="{{ __('profile.image.alt') }}">
    <h5 class="fw-bold mb-1">{{ $user->fullName() }}</h5>
    <p class="text-muted small mb-0">{{ $user->email }}</p>
  </div>

  <ul class="nav flex-column">
    <li class="nav-item">
      <button class="nav-link text-start active" data-bs-toggle="tab" data-bs-target="#pane-info" type="button">
        <i class="fas fa-user me-2"></i>{{ __('profile.sidebar.my_account') }}
      </button>
    </li>
    <li class="nav-item">
      <button class="nav-link text-start" data-bs-toggle="tab" data-bs-target="#pane-edit" type="button">
        <i class="fas fa-edit me-2"></i>{{ __('profile.sidebar.edit_profile') }}
      </button>
    </li>
    {{--  <li class="nav-item">
      <button class="nav-link text-start" data-bs-toggle="tab" data-bs-target="#pane-qual" type="button">
        <i class="fas fa-graduation-cap me-2"></i>{{ __('profile.sidebar.qualifications') }}
      </button>
    </li>  --}}
  </ul>
</div>
