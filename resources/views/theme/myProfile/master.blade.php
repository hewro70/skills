{{-- Navbar --}}
@include('theme.partials.header')

<section>
  <link rel="stylesheet" href="{{ asset('assets/css/profile.css') }}">




{{-- أيقونات --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

  <div class="container-fluid">
    <div class="row">
      {{-- Sidebar --}}
      @include('theme.myProfile.sections.sidebar', [
        'user' => $user
      ])

      {{-- Sidebar Toggle Button (للموبايل) --}}
      <button class="sidebar-toggle" id="sidebarToggle">
        <i class="fas fa-bars"></i>
      </button>

      {{-- Main Content --}}
      <div class="main-content" id="mainContent">
        <div class="tab-content">
          {{-- تبويب معلومات الحساب --}}
          @include('theme.myProfile.sections.profile-info', [
            'user' => $user
          ])

          {{-- تبويبا تعديل الحساب + المؤهلات معًا --}}
          @include('theme.myProfile.sections.edit-and-qualifications', [
            'user' => $user,
            'skills' => $skills,
            'languages' => $languages,
            'countries' => $countries,
            'classifications' => $classifications
          ])
        </div>
      </div>
    </div>
  </div>

  @include('theme.myProfile.sections.scripts') 
</section>
