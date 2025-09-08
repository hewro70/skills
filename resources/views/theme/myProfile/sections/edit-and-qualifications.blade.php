{{-- Edit Profile Tab --}}
<div class="tab-pane fade" id="edit-profile">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold text-gradient">
      <i class="fas fa-edit me-2"></i>{{ __('profile.edit.title') }}
    </h2>
    <button class="btn btn-primary d-lg-none ms-2 hover-lift" id="mobileSidebarToggle2">
      <i class="fas fa-bars"></i>{{ __('profile.menu') }}
    </button>
  </div>

  <form id="profileForm" method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
    @csrf
    @method('PATCH')

    <div class="row">
      <div class="col-xl-8">
        {{-- Basic Info --}}
        <div class="card mb-4 hover-lift">
          <div class="card-header">
            <h6 class="m-0 font-weight-bold">{{ __('profile.basic.title') }}</h6>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-md-6 mb-3">
                <label for="first_name" class="form-label">{{ __('profile.basic.first_name') }}</label>
                <input type="text" class="form-control" id="first_name" name="first_name"
                       value="{{ old('first_name', $user->first_name) }}" required>
              </div>
              <div class="col-md-6 mb-3">
                <label for="last_name" class="form-label">{{ __('profile.basic.last_name') }}</label>
                <input type="text" class="form-control" id="last_name" name="last_name"
                       value="{{ old('last_name', $user->last_name) }}" required>
              </div>
            </div>

            <div class="mb-3">
              <label for="email" class="form-label">{{ __('profile.basic.email') }}</label>
              <input type="email" class="form-control" id="email" name="email"
                     value="{{ old('email', $user->email) }}" required>
            </div>

            <div class="row">
              <div class="col-md-6 mb-3">
                <label for="phone" class="form-label">{{ __('profile.basic.phone') }}</label>
                <input type="text" class="form-control" id="phone" name="phone"
                       value="{{ old('phone', $user->phone) }}">
              </div>
              <div class="col-md-6 mb-3">
                <label for="date_of_birth" class="form-label">{{ __('profile.basic.birthdate') }}</label>
                <input type="date" class="form-control" id="date_of_birth" name="date_of_birth"
                       value="{{ old('date_of_birth', $user->date_of_birth ? $user->date_of_birth->format('Y-m-d') : '') }}">
              </div>
            </div>

            <div class="row">
              <div class="col-md-6 mb-3">
                <label for="gender" class="form-label">{{ __('profile.basic.gender') }}</label>
                <select class="form-select" id="gender" name="gender">
                  <option value="">{{ __('profile.basic.choose') }}</option>
                  <option value="male"   {{ old('gender', $user->gender) == 'male' ? 'selected' : '' }}>{{ __('profile.gender.male') }}</option>
                  <option value="female" {{ old('gender', $user->gender) == 'female' ? 'selected' : '' }}>{{ __('profile.gender.female') }}</option>
                </select>
              </div>
              <div class="col-md-6 mb-3">
                <label for="country_id" class="form-label">{{ __('profile.basic.country') }}</label>
                <select class="form-select" id="country_id" name="country_id">
                  <option value="">{{ __('profile.basic.choose_country') }}</option>
                  @foreach ($countries as $country)
                    <option value="{{ $country->id }}" {{ old('country_id', $user->country_id) == $country->id ? 'selected' : '' }}>
                      {{ $country->name }}
                    </option>
                  @endforeach
                </select>
              </div>
            </div>

            <div class="mb-3">
              <label for="about_me" class="form-label">{{ __('profile.basic.about_me') }}</label>
              <textarea class="form-control" id="about_me" name="about_me" rows="3">{{ old('about_me', $user->about_me) }}</textarea>
            </div>
          </div>
        </div>

        {{-- Skills --}}
        <div class="card mb-4 hover-lift">
          <div class="card-header">
            <h6 class="m-0 font-weight-bold">{{ __('profile.teachable_skills.title') }}</h6>
          </div>
          <div class="card-body">
            <div class="row mb-3">
              <div class="col-md-4">
                <label for="skillFilterType" class="form-label">{{ __('profile.teachable_skills.filter_type') }}</label>
                <select class="form-select" id="skillFilterType">
                  <option value="none">{{ __('profile.teachable_skills.filter_none') }}</option>
                  <option value="skill">{{ __('profile.teachable_skills.filter_skill') }}</option>
                  <option value="classification">{{ __('profile.teachable_skills.filter_classification') }}</option>
                </select>
              </div>
              <div class="col-md-4" id="skillNameFilterContainer" style="display:none;">
                <label for="skillNameFilter" class="form-label">{{ __('profile.teachable_skills.search_by_skill') }}</label>
                <input type="text" class="form-control" id="skillNameFilter"
                       placeholder="{{ __('profile.teachable_skills.search_by_skill_placeholder') }}">
              </div>
              <div class="col-md-4" id="classificationFilterContainer" style="display:none;">
                <label for="classificationFilter" class="form-label">{{ __('profile.teachable_skills.search_by_classification') }}</label>
                <select class="form-select" id="classificationFilter">
                  <option value="">{{ __('profile.teachable_skills.select_classification') }}</option>
                  @foreach ($classifications as $classification)
                    <option value="{{ $classification->id }}">{{ $classification->name_text  }}</option>
                  @endforeach
                </select>
              </div>
            </div>

            <div class="table-responsive">
              <table class="table table-bordered table-hover">
                <thead class="table-light">
                  <tr>
                    <th width="40%">{{ __('profile.teachable_skills.table.skill') }}</th>
                    <th width="40%">{{ __('profile.teachable_skills.table.classification') }}</th>
                    <th width="20%">{{ __('profile.teachable_skills.table.select') }}</th>
                  </tr>
                </thead>
                <tbody id="skillsTableBody">
                  @foreach ($skills->forPage(1, 10) as $skill)
                    <tr>
                      <td>{{ $skill->name }}</td>
                      <td>{{ $skill->classification->name ?? '' }}</td>
                      <td class="text-center">
                        <input class="form-check-input" type="checkbox"
                               name="skills[{{ $skill->id }}]" id="skill_{{ $skill->id }}"
                               value="{{ $skill->id }}"
                               @if ($user->skills->contains($skill->id)) checked @endif>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>

            <div class="row mt-3">
              <div class="col-md-6">
                <p class="text-muted">
                  {{ __('profile.teachable_skills.pagination.showing', ['from'=>1, 'to'=>10, 'total'=>$skills->count()]) }}
                </p>
              </div>
              <div class="col-md-6">
                <nav aria-label="Skills pagination" class="float-end">
<ul id="skillsPagination" class="pagination">
                    <li class="page-item disabled" id="skillsPrevPage">
                      <a class="page-link" href="#" tabindex="-1">{{ __('profile.pagination.prev') }}</a>
                    </li>
                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                    @for ($i = 2; $i <= ceil($skills->count() / 10); $i++)
                      <li class="page-item"><a class="page-link" href="#">{{ $i }}</a></li>
                    @endfor
                    <li class="page-item" id="skillsNextPage">
                      <a class="page-link" href="#">{{ __('profile.pagination.next') }}</a>
                    </li>
                  </ul>
                </nav>
              </div>
            </div>
          </div>
        </div>

        {{-- Languages --}}
        <div class="card mb-4 hover-lift">
          <div class="card-header">
            <h6 class="m-0 font-weight-bold">{{ __('profile.languages_section.title') }}</h6>
          </div>
          <div class="card-body">
            <div class="row mb-3">
              <div class="col-md-4">
                <label for="languageFilter" class="form-label">{{ __('profile.languages_section.filter') }}</label>
                <input type="text" class="form-control" id="languageFilter"
                       placeholder="{{ __('profile.languages_section.filter.placeholder') }}">
              </div>
            </div>

            <div class="table-responsive">
              <table class="table table-bordered table-hover">
                <thead class="table-light">
                  <tr>
                    <th width="50%">{{ __('profile.languages_section.table.language') }}</th>
                    <th width="30%">{{ __('profile.languages_section.table.level') }}</th>
                    <th width="20%">{{ __('profile.languages_section.table.select') }}</th>
                  </tr>
                </thead>
                <tbody id="languagesTableBody">
                  @foreach ($languages->forPage(1, 10) as $language)
                    <tr>
                      <td>{{ $language->name }}</td>
                      <td>
                        <select class="form-select language-level"
                                name="languages[{{ $language->id }}][level]"
                                @if (!$user->languages->contains($language->id)) disabled @endif>
                          <option value="">{{ __('profile.languages.select_level') }}</option>
                          <option value="مبتدئ جدًا" @if ($user->languages->find($language->id)) {{ $user->languages->find($language->id)->pivot->level == 'مبتدئ جدًا' ? 'selected' : '' }} @endif>
                            {{ __('profile.languages.level.a1') }}
                          </option>
                          <option value="مبتدئ" @if ($user->languages->find($language->id)) {{ $user->languages->find($language->id)->pivot->level == 'مبتدئ' ? 'selected' : '' }} @endif>
                            {{ __('profile.languages.level.a2') }}
                          </option>
                          <option value="ما قبل المتوسط" @if ($user->languages->find($language->id)) {{ $user->languages->find($language->id)->pivot->level == 'ما قبل المتوسط' ? 'selected' : '' }} @endif>
                            {{ __('profile.languages.level.b1') }}
                          </option>
                          <option value="متوسط" @if ($user->languages->find($language->id)) {{ $user->languages->find($language->id)->pivot->level == 'متوسط' ? 'selected' : '' }} @endif>
                            {{ __('profile.languages.level.b2') }}
                          </option>
                          <option value="فوق المتوسط" @if ($user->languages->find($language->id)) {{ $user->languages->find($language->id)->pivot->level == 'فوق المتوسط' ? 'selected' : '' }} @endif>
                            {{ __('profile.languages.level.c1') }}
                          </option>
                          <option value="متقدم جدًا" @if ($user->languages->find($language->id)) {{ $user->languages->find($language->id)->pivot->level == 'متقدم جدًا' ? 'selected' : '' }} @endif>
                            {{ __('profile.languages.level.c2') }}
                          </option>
                        </select>
                      </td>
                      <td class="text-center">
                        <input class="form-check-input language-checkbox" type="checkbox"
                               name="languages[{{ $language->id }}][selected]"
                               id="language_{{ $language->id }}" value="1"
                               @if ($user->languages->contains($language->id)) checked @endif>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>

            <div class="row mt-3">
              <div class="col-md-6">
                <p class="text-muted">
                  {{ __('profile.languages_section.pagination.showing', ['from'=>1, 'to'=>10, 'total'=>$languages->count()]) }}
                </p>
              </div>
              <div class="col-md-6">
                <nav aria-label="Languages pagination" class="float-end">
                  <ul class="pagination">
                    <li class="page-item disabled" id="languagesPrevPage">
                      <a class="page-link" href="#" tabindex="-1">{{ __('profile.pagination.prev') }}</a>
                    </li>
                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                    @for ($i = 2; $i <= ceil($languages->count() / 10); $i++)
                      <li class="page-item"><a class="page-link" href="#">{{ $i }}</a></li>
                    @endfor
                    <li class="page-item" id="languagesNextPage">
                      <a class="page-link" href="#">{{ __('profile.pagination.next') }}</a>
                    </li>
                  </ul>
                </nav>
              </div>
            </div>
          </div>
        </div>

        {{-- Save --}}
        <div class="text-center mt-4">
          <button type="submit" class="btn btn-primary btn-lg hover-lift">
            <i class="fas fa-save me-1"></i> {{ __('profile.btn.save_changes') }}
          </button>
        </div>
      </div>
    </div>
  </form>
</div>

{{-- Qualifications Tab --}}
<div class="tab-pane fade" id="qualifications">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold text-gradient">
      <i class="fas fa-graduation-cap me-2"></i>{{ __('profile.qualifications.title') }}
    </h2>
    <button class="btn btn-primary d-lg-none ms-2 hover-lift" id="mobileSidebarToggle3">
      <i class="fas fa-bars"></i>{{ __('profile.menu') }}
    </button>
  </div>

  <form id="qualificationsForm" method="POST" action="{{ route('profile.update-qualifications') }}">
    @csrf
    @method('PUT')

    <div class="card mb-4 hover-lift">
      <div class="card-header">
        <h6 class="m-0 font-weight-bold">{{ __('profile.qualifications.description_title') }}</h6>
      </div>
      <div class="card-body col-lg-8 mx-auto">
        @if ($user->skills->count() > 0)
          <ul class="list-group list-group-flush">
            @foreach ($user->skills as $skill)
              <li class="list-group-item border-0 px-0">
                <div class="mb-3">
                  <label class="form-label fw-bold">{{ $skill->name_text  }}</label>
                  <textarea class="form-control skill-description"
                            name="skills[{{ $skill->id }}][description]" rows="4"
                            placeholder="{{ __('profile.qualifications.placeholder') }}">{{ $skill->pivot->description ?? '' }}</textarea>
                </div>
              </li>
            @endforeach
          </ul>
        @else
          <div class="alert alert-info">{{ __('profile.qualifications.empty') }}</div>
        @endif
      </div>
    </div>

    @if ($user->skills->count() > 0)
      <div class="text-center">
        <button type="submit" class="btn btn-primary btn-lg hover-lift">
          <i class="fas fa-save me-1"></i> {{ __('profile.btn.save_changes') }}
        </button>
      </div>
    @endif
  </form>
</div>
