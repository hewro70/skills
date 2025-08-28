<div class="sidebar" id="sidebar">
  <div class="pt-4 px-3">
    <div class="text-center mb-4">
      <img id="sidebarProfileImage" src="{{ $user->image_url }}"
           class="rounded-circle profile-img mb-3 hover-lift"
           alt="{{ __('profile.image.alt') }}">
      <h5 class="fw-bold">{{ $user->fullName() }}</h5>
      <p class="text-muted">{{ $user->email }}</p>
    </div>

    <ul class="nav flex-column">
      <li class="nav-item">
        <a class="nav-link active" href="#profile-info" data-bs-toggle="tab">
          <i class="fas fa-user me-2"></i>{{ __('profile.sidebar.my_account') }}
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link" href="#edit-profile" data-bs-toggle="tab">
          <i class="fas fa-edit me-2"></i>{{ __('profile.sidebar.edit_profile') }}
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link" href="#qualifications" data-bs-toggle="tab">
          <i class="fas fa-graduation-cap me-2"></i>{{ __('profile.sidebar.qualifications') }}
        </a>
      </li>
    </ul>
  </div>
</div>
