
    <!-- Bootstrap Bundle with Popper -->
    <!-- jQuery -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
/* ===== i18n bridge (reads from lang/*.json) ===== */
(function () {
  const T = {
    swal: {
      error_title: @json(__('swal.error_title')),
      success_title: @json(__('swal.success_title')),
      confirm_title: @json(__('swal.confirm_title')),
      please_wait: @json(__('swal.please_wait')),
      save_question: @json(__('swal.save_question')),
      save_confirm: @json(__('swal.save_confirm')),
      cancel: @json(__('swal.cancel')),
      delete_confirm_question: @json(__('swal.delete_confirm_question')),
      delete_confirm: @json(__('swal.delete_confirm')),
      delete_success: @json(__('swal.delete_success')),
      delete_error: @json(__('swal.delete_error')),
      network_error: @json(__('swal.network_error')),
      uploading_image: @json(__('swal.uploading_image')),
      updated_successfully: @json(__('swal.updated_successfully')),
      image_error_type: @json(__('swal.image.error_type')),
      image_error_size: @json(__('swal.image.error_size')),
      profile_image_updated: @json(__('swal.profile_image_updated')),
    },
    select2: {
      country: @json(__('select2.placeholder.country')),
      classification: @json(__('select2.placeholder.classification')),
      no_results: @json(__('select2.no_results')),
    },
    profile: {
      pagination_prev: @json(__('profile.pagination.prev')),
      pagination_next: @json(__('profile.pagination.next')),
      languages_select_level: @json(__('profile.languages.select_level')),
    },
    common: {
      not_set: @json(__('common.not_set'))
    }
  };
  window.T = T; // متاح للاستخدام بباقي السكربت
})();
</script>

<script>
/* ===== Upload profile image (i18n) ===== */
async function uploadProfileImage(file) {
  if (!file) return;

  const validTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
  if (!validTypes.includes(file.type)) {
    Swal.fire({
      title: T.swal.error_title,
      text: T.swal.image_error_type,
      icon: 'error',
      confirmButtonColor: '#4e73df'
    });
    return false;
  }

  const maxSize = 5 * 1024 * 1024; // 5MB
  if (file.size > maxSize) {
    Swal.fire({
      title: T.swal.error_title,
      text: T.swal.image_error_size,
      icon: 'error',
      confirmButtonColor: '#4e73df'
    });
    return false;
  }

  const formData = new FormData();
  formData.append('profile_image', file);
  formData.append('_token', '{{ csrf_token() }}');

  Swal.fire({
    title: T.swal.uploading_image,
    html: T.swal.please_wait,
    allowOutsideClick: false,
    didOpen: () => Swal.showLoading()
  });

  try {
    const response = await axios.post('/profile/upload-image', formData, {
      headers: { 'Content-Type': 'multipart/form-data' }
    });

    Swal.fire({
      title: T.swal.success_title,
      text: T.swal.profile_image_updated,
      icon: 'success',
      confirmButtonColor: '#4e73df'
    });

    if (response.data.image_url) {
      const newImageUrl = response.data.image_url + '?' + new Date().getTime();
      $('#profileImage, #sidebarProfileImage').attr('src', newImageUrl);
      setTimeout(() => location.reload(), 1000);
    }

    location.reload();
    return true;

  } catch (error) {
    let errorMessage = T.swal.delete_error;
    if (error.response?.data?.message) errorMessage = error.response.data.message;

    Swal.fire({
      title: T.swal.error_title,
      text: errorMessage,
      icon: 'error',
      confirmButtonColor: '#4e73df'
    });
    return false;
  }
}

$(document).ready(function () {
  /* ===== Select2 (i18n) ===== */
  $('#classificationFilter').select2({
    placeholder: T.select2.classification,
    allowClear: true,
    language: { noResults: () => T.select2.no_results }
  });

  $('#country_id').select2({
    placeholder: T.select2.country,
    dir: "{{ app()->isLocale('ar') ? 'rtl' : 'ltr' }}",
    width: '100%'
  });

  /* ===== Profile Image Upload ===== */
  $('#uploadImageBtn').on('click', function (e) {
    e.preventDefault();
    $('#profileImageInput').trigger('click');
  });

  $('#profileImageInput').on('change', function () {
    if (this.files && this.files[0]) uploadProfileImage(this.files[0]);
  });

  /* ===== Sidebar ===== */
  const sidebar = $('#sidebar');
  const sidebarToggle = $('#sidebarToggle');
  const mobileSidebarToggle = $('#mobileSidebarToggle');
  const mobileSidebarToggle2 = $('#mobileSidebarToggle2');

  function toggleSidebar() {
    sidebar.toggleClass('show');
    $('body').toggleClass('sidebar-open');
  }
  function initSidebar() {
    if ($(window).width() < 992) sidebar.removeClass('show');
    else sidebar.addClass('show');
  }
  initSidebar();

  [sidebarToggle, mobileSidebarToggle, mobileSidebarToggle2].forEach(btn => {
    btn.on('click', function (e) { e.preventDefault(); e.stopPropagation(); toggleSidebar(); });
  });

  $(document).on('click', function (e) {
    if ($(window).width() < 992 &&
        !$(e.target).closest('#sidebar, #sidebarToggle, #mobileSidebarToggle, #mobileSidebarToggle2').length &&
        sidebar.hasClass('show')) {
      toggleSidebar();
    }
  });
  $(window).on('resize', initSidebar);

  /* ===== Skills ===== */
  const skillsPerPage = 10;
  let currentSkillsPage = 1;
  let filteredSkills = @json($skills);
  let allSelectedSkills = @json($user->skills->pluck('id')->toArray()) || [];

  $('#skillFilterType').change(function () {
    const filterType = $(this).val();
    $('#skillNameFilterContainer').toggle(filterType === 'skill');
    $('#classificationFilterContainer').toggle(filterType === 'classification');

    if (filterType === 'none') {
      filteredSkills = @json($skills);
      currentSkillsPage = 1;
      renderSkillsTable();
    }
  });

  $('#skillNameFilter').keyup(function () {
    const searchTerm = $(this).val().toLowerCase();
    filteredSkills = @json($skills).filter(skill => (skill.name || '').toLowerCase().includes(searchTerm));
    currentSkillsPage = 1;
    renderSkillsTable();
  });

  $('#classificationFilter').change(function () {
    const classificationId = $(this).val();
    filteredSkills = classificationId
      ? @json($skills).filter(skill => skill.classification_id == classificationId)
      : @json($skills);
    currentSkillsPage = 1;
    renderSkillsTable();
  });

  $(document).on('change', '.form-check-input[type="checkbox"][name^="skills"]', function () {
    const skillId = parseInt($(this).val());
    if ($(this).is(':checked')) {
      if (!allSelectedSkills.includes(skillId)) allSelectedSkills.push(skillId);
    } else {
      allSelectedSkills = allSelectedSkills.filter(id => id !== skillId);
    }
  });

  $(document).on('click', '.skills-page-link', function (e) {
    e.preventDefault();
    currentSkillsPage = parseInt($(this).data('page'));
    renderSkillsTable();
  });
  $(document).on('click', '#skillsPrevPage', function (e) {
    e.preventDefault();
    if (currentSkillsPage > 1) { currentSkillsPage--; renderSkillsTable(); }
  });
  $(document).on('click', '#skillsNextPage', function (e) {
    e.preventDefault();
    if (currentSkillsPage < Math.ceil(filteredSkills.length / skillsPerPage)) {
      currentSkillsPage++; renderSkillsTable();
    }
  });

  function renderSkillsTable() {
    const startIndex = (currentSkillsPage - 1) * skillsPerPage;
    const paginatedSkills = filteredSkills.slice(startIndex, startIndex + skillsPerPage);

    let html = '';
    paginatedSkills.forEach(skill => {
      const clsName = (skill.classification && skill.classification.name) ? skill.classification.name : T.common.not_set;
      html += `
        <tr>
          <td>${skill.name || ''}</td>
          <td>${clsName}</td>
          <td class="text-center">
            <input class="form-check-input" type="checkbox"
              name="skills[${skill.id}]"
              id="skill_${skill.id}"
              value="${skill.id}"
              ${allSelectedSkills.includes(skill.id) ? 'checked' : ''}>
          </td>
        </tr>`;
    });

    $('#skillsTableBody').html(html);
    updateSkillsPaginationInfo();
  }

  function updateSkillsPaginationInfo() {
    const startIndex = (currentSkillsPage - 1) * skillsPerPage;
    $('#skillsFrom').text(startIndex + 1);
    $('#skillsTo').text(Math.min(startIndex + skillsPerPage, filteredSkills.length));
    $('#skillsTotal').text(filteredSkills.length);

    const totalPages = Math.ceil(filteredSkills.length / skillsPerPage);
    let paginationHtml = `
      <li class="page-item ${currentSkillsPage === 1 ? 'disabled' : ''}" id="skillsPrevPage">
        <a class="page-link" href="#" tabindex="-1">${T.profile.pagination_prev}</a>
      </li>`;

    for (let i = 1; i <= totalPages; i++) {
      paginationHtml += `
        <li class="page-item ${i === currentSkillsPage ? 'active' : ''}">
          <a class="page-link skills-page-link" href="#" data-page="${i}">${i}</a>
        </li>`;
    }

    paginationHtml += `
      <li class="page-item ${currentSkillsPage === totalPages ? 'disabled' : ''}" id="skillsNextPage">
        <a class="page-link" href="#">${T.profile.pagination_next}</a>
      </li>`;

    $('.pagination').first().html(paginationHtml);
  }

  /* ===== Languages ===== */
  const languagesPerPage = 10;
  let currentLanguagesPage = 1;
  let filteredLanguages = @json($languages);
  let allLanguagesData = @json($languages);
  let selectedLanguages = @json(
    $user->languages->mapWithKeys(function ($lang) {
      return [$lang->id => ['selected' => true, 'level' => $lang->pivot->level]];
    })->toArray()
  ) || {};

  @foreach ($user->languages as $lang)
    selectedLanguages[{{ $lang->id }}] = { selected: true, level: '{{ $lang->pivot->level }}' };
  @endforeach

  renderSkillsTable();
  renderLanguagesTable();

  $('#languageFilter').keyup(function () {
    const searchTerm = $(this).val().toLowerCase();
    filteredLanguages = allLanguagesData.filter(language => (language.name || '').toLowerCase().includes(searchTerm));
    currentLanguagesPage = 1;
    renderLanguagesTable();
  });

  $(document).on('click', '.languages-page-link', function (e) {
    e.preventDefault();
    currentLanguagesPage = parseInt($(this).data('page'));
    renderLanguagesTable();
  });
  $(document).on('click', '#languagesPrevPage', function (e) {
    e.preventDefault();
    if (currentLanguagesPage > 1) { currentLanguagesPage--; renderLanguagesTable(); }
  });
  $(document).on('click', '#languagesNextPage', function (e) {
    e.preventDefault();
    if (currentLanguagesPage < Math.ceil(filteredLanguages.length / languagesPerPage)) {
      currentLanguagesPage++; renderLanguagesTable();
    }
  });

  $(document).on('change', '.language-checkbox', function () {
    const row = $(this).closest('tr');
    const levelSelect = row.find('.language-level');
    const languageId = parseInt($(this).val());

    if ($(this).is(':checked')) {
      // NOTE: القيم (value) تبقى عربية كما هي لحفظها في الداتابيس
      if (!levelSelect.val()) levelSelect.val('مبتدئ');
      levelSelect.prop('disabled', false);
      selectedLanguages[languageId] = { selected: true, level: levelSelect.val() };
    } else {
      levelSelect.prop('disabled', true);
      delete selectedLanguages[languageId];
    }
  });

  $(document).on('change', '.language-level', function () {
    const languageId = parseInt($(this).closest('tr').find('.language-checkbox').val());
    if (selectedLanguages[languageId]) {
      selectedLanguages[languageId].level = $(this).val();
    }
  });

  function renderLanguagesTable() {
    const startIndex = (currentLanguagesPage - 1) * languagesPerPage;
    const paginatedLanguages = filteredLanguages.slice(startIndex, startIndex + languagesPerPage);

    let html = '';
    paginatedLanguages.forEach(language => {
      const isSelected = Object.prototype.hasOwnProperty.call(selectedLanguages, language.id);
      const level = isSelected ? selectedLanguages[language.id].level : '';

      html += `
        <tr>
          <td>${language.name || ''}</td>
          <td>
            <select class="form-select language-level" name="languages[${language.id}][level]" ${isSelected ? '' : 'disabled'}>
              <option value="">${T.profile.languages_select_level}</option>
              <option value="مبتدئ جدًا" ${level === 'مبتدئ جدًا' ? 'selected' : ''}>@json(__('profile.languages.level.a1'))</option>
              <option value="مبتدئ" ${level === 'مبتدئ' ? 'selected' : ''}>@json(__('profile.languages.level.a2'))</option>
              <option value="ما قبل المتوسط" ${level === 'ما قبل المتوسط' ? 'selected' : ''}>@json(__('profile.languages.level.b1'))</option>
              <option value="متوسط" ${level === 'متوسط' ? 'selected' : ''}>@json(__('profile.languages.level.b2'))</option>
              <option value="فوق المتوسط" ${level === 'فوق المتوسط' ? 'selected' : ''}>@json(__('profile.languages.level.c1'))</option>
              <option value="متقدم جدًا" ${level === 'متقدم جدًا' ? 'selected' : ''}>@json(__('profile.languages.level.c2'))</option>
            </select>
          </td>
          <td class="text-center">
            <input class="form-check-input language-checkbox" type="checkbox"
              name="languages[${language.id}][selected]"
              id="language_${language.id}" value="${language.id}"
              ${isSelected ? 'checked' : ''}>
          </td>
        </tr>`;
    });

    $('#languagesTableBody').html(html);
    updateLanguagesPaginationInfo();
  }

  function updateLanguagesPaginationInfo() {
    const startIndex = (currentLanguagesPage - 1) * languagesPerPage;
    $('#languagesFrom').text(startIndex + 1);
    $('#languagesTo').text(Math.min(startIndex + languagesPerPage, filteredLanguages.length));
    $('#languagesTotal').text(filteredLanguages.length);

    const totalPages = Math.ceil(filteredLanguages.length / languagesPerPage);
    let paginationHtml = `
      <li class="page-item ${currentLanguagesPage === 1 ? 'disabled' : ''}" id="languagesPrevPage">
        <a class="page-link" href="#" tabindex="-1">${T.profile.pagination_prev}</a>
      </li>`;

    for (let i = 1; i <= totalPages; i++) {
      paginationHtml += `
        <li class="page-item ${i === currentLanguagesPage ? 'active' : ''}">
          <a class="page-link languages-page-link" href="#" data-page="${i}">${i}</a>
        </li>`;
    }

    paginationHtml += `
      <li class="page-item ${currentLanguagesPage === totalPages ? 'disabled' : ''}" id="languagesNextPage">
        <a class="page-link" href="#">${T.profile.pagination_next}</a>
      </li>`;

    $('.pagination').last().html(paginationHtml);
  }

  /* ===== Form submit (i18n) ===== */
  $('#profileForm').on('submit', function (e) {
    e.preventDefault();
    const form = this;

    $('.dynamic-skill-input').remove();
    $('.dynamic-language-input').remove();

    const invalidLanguages = [];
    const validLanguagesData = {};

    Object.keys(selectedLanguages).forEach(langId => {
      const langIdNum = parseInt(langId);
      if (isNaN(langIdNum) || langIdNum <= 0) return;

      if (!selectedLanguages[langId].level) {
        const langName = (allLanguagesData.find(l => l.id == langId) || {}).name || T.common.not_set;
        invalidLanguages.push(langName);
      } else {
        validLanguagesData[langId] = { selected: true, level: selectedLanguages[langId].level };
      }
    });

    if (invalidLanguages.length > 0) {
      Swal.fire({
        title: T.swal.error_title,
        html: @json(__('profile.languages.select_level')) + '<br>' + invalidLanguages.join('<br>'),
        icon: 'error',
        confirmButtonColor: '#4e73df'
      });
      return false;
    }

    $('<input>').attr({
      type: 'hidden',
      name: 'skills_data',
      value: JSON.stringify(allSelectedSkills.filter(id => id > 0)),
      class: 'dynamic-skill-input'
    }).appendTo(form);

    $('<input>').attr({
      type: 'hidden',
      name: 'languages_data',
      value: JSON.stringify(validLanguagesData),
      class: 'dynamic-language-input'
    }).appendTo(form);

    Swal.fire({
      title: T.swal.confirm_title,
      text: T.swal.save_question,
      icon: 'question',
      showCancelButton: true,
      confirmButtonColor: '#4e73df',
      cancelButtonColor: '#d33',
      confirmButtonText: T.swal.save_confirm,
      cancelButtonText: T.swal.cancel
    }).then((result) => { if (result.isConfirmed) form.submit(); });
  });

  @if (session('success'))
    Swal.fire({
      title: T.swal.success_title,
      text: @json(session('success')),
      icon: 'success',
      confirmButtonColor: '#4e73df'
    });
  @endif
});
</script>

<script>
/* ===== Remove image (i18n) ===== */
document.addEventListener('DOMContentLoaded', function () {
  const removeBtn = document.getElementById('removeImageBtn');
  const profileImage = document.getElementById('profileImage');
  const sidebarImage = document.getElementById('sidebarProfileImage');

  if (!removeBtn) return;

  removeBtn.addEventListener('click', function () {
    Swal.fire({
      title: T.swal.delete_confirm_question,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: T.swal.delete_confirm,
      cancelButtonText: T.swal.cancel
    }).then((result) => {
      if (!result.isConfirmed) return;

      fetch('{{ route('profile.remove-image') }}', {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}',
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({})
      })
      .then(r => r.json())
      .then(data => {
        if (data.success) {
          const userGender = '{{ auth()->user()->gender }}';
          const defaultImage = userGender === 'female'
            ? 'https://cdn-icons-png.flaticon.com/512/4140/4140047.png'
            : 'https://cdn-icons-png.flaticon.com/512/4140/4140048.png';
          if (profileImage) profileImage.src = defaultImage;
          if (sidebarImage) sidebarImage.src = defaultImage + '?v=' + new Date().getTime();

          const fileInput = document.getElementById('imageInput');
          if (fileInput) fileInput.value = '';

          Swal.fire(T.swal.success_title, T.swal.delete_success, 'success');
        } else {
          Swal.fire(T.swal.error_title, T.swal.delete_error, 'error');
        }
      })
      .catch(() => Swal.fire(T.swal.error_title, T.swal.network_error, 'error'));
    });
  });
});
</script>

<script>
/* keep navbar height sync */
(function(){
  const root = document.documentElement;
  const nav  = document.querySelector('.navbar.fixed-top') || document.querySelector('.navbar');
  function syncNavH(){
    if(!nav) return;
    const h = Math.ceil(nav.getBoundingClientRect().height);
    root.style.setProperty('--nav-h', h + 'px');
  }
  syncNavH();
  window.addEventListener('resize', syncNavH);
})();
</script>

