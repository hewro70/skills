<script>
/* ===== i18n bridge ===== */
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
    common: { not_set: @json(__('common.not_set')) },
    // === مفاتيح بسيطة للمستويات (غيّرها للعربي لو بدك) ===
    skills: {
      level_label: 'Level',
      desc_label: 'Description',
      levels: { 1:'Beginner', 2:'Junior', 3:'Intermediate', 4:'Senior', 5:'Expert' }
    }
  };
  window.T = T;
})();
</script>

<script>
/* ===== Upload / Remove profile image ===== */
async function uploadProfileImage(file) {
  if (!file) return;
  const validTypes = ['image/jpeg','image/png','image/gif','image/webp'];
  if (!validTypes.includes(file.type)) {
    Swal.fire({ title:T.swal.error_title, text:T.swal.image_error_type, icon:'error' }); return;
  }
  const maxSize = 5 * 1024 * 1024;
  if (file.size > maxSize) {
    Swal.fire({ title:T.swal.error_title, text:T.swal.image_error_size, icon:'error' }); return;
  }
  const formData = new FormData();
  formData.append('profile_image', file);
  formData.append('_token', '{{ csrf_token() }}');

  Swal.fire({ title:T.swal.uploading_image, html:T.swal.please_wait, allowOutsideClick:false, didOpen:()=>Swal.showLoading() });
  try{
    const res = await axios.post('{{ url("/profile/upload-image") }}', formData, { headers:{'Content-Type':'multipart/form-data'} });
    Swal.fire({ title:T.swal.success_title, text:T.swal.profile_image_updated, icon:'success' });
    if(res.data.image_url){
      const url = res.data.image_url + '?' + Date.now();
      $('#profileImage, #sidebarProfileImage').attr('src', url);
    }
  }catch(e){
    Swal.fire({ title:T.swal.error_title, text: (e.response?.data?.message || T.swal.delete_error), icon:'error' });
  }
}

document.addEventListener('DOMContentLoaded', () => {
  // Select2
  $('#classificationFilter').select2({ placeholder: T.select2.classification, allowClear:true, language:{ noResults:()=>T.select2.no_results } });
  $('#country_id').select2({ placeholder: T.select2.country, dir: "{{ app()->isLocale('ar') ? 'rtl' : 'ltr' }}", width:'100%' });

  // Image upload
  $('#uploadImageBtn').on('click', e => { e.preventDefault(); $('#profileImageInput').trigger('click'); });
  $('#profileImageInput').on('change', function(){ if(this.files?.[0]) uploadProfileImage(this.files[0]); });

  // Remove image
  const removeBtn = document.getElementById('removeImageBtn');
  if(removeBtn){
    removeBtn.addEventListener('click', function(){
      Swal.fire({ title:T.swal.delete_confirm_question, icon:'warning', showCancelButton:true, confirmButtonText:T.swal.delete_confirm, cancelButtonText:T.swal.cancel })
        .then((r)=>{
          if(!r.isConfirmed) return;
          fetch('{{ route('profile.remove-image') }}',{
            method:'POST',
            headers:{ 'X-CSRF-TOKEN':'{{ csrf_token() }}', 'Content-Type':'application/json' },
            body: JSON.stringify({})
          })
          .then(x=>x.json())
          .then(data=>{
            if(data.success){
              const gender = '{{ $user->gender }}';
              const def = gender==='female' ? 'https://cdn-icons-png.flaticon.com/512/4140/4140047.png' : 'https://cdn-icons-png.flaticon.com/512/4140/4140048.png';
              document.getElementById('profileImage').src = def;
              document.getElementById('sidebarProfileImage').src = def + '?v=' + Date.now();
              Swal.fire(T.swal.success_title, T.swal.delete_success, 'success');
            }else{
              Swal.fire(T.swal.error_title, T.swal.delete_error, 'error');
            }
          }).catch(()=>Swal.fire(T.swal.error_title, T.swal.network_error, 'error'));
        });
    });
  }

  /* ====== Skills logic (Level + Description) ====== */
  const locale = document.documentElement.lang || 'ar';
  const skillsPerPage = 10;
  let currentSkillsPage = 1;

  function toText(val){
    if (typeof val === 'string') return val;
    if (val && typeof val === 'object') {
      if (typeof val.name_text === 'string') return val.name_text;
      if (val[locale]) return val[locale];
      const first = Object.values(val)[0];
      if (typeof first === 'string') return first;
    }
    return '';
  }

  const $skillsTableBody = $('#skillsTableBody');
  const $skillsPagination = $('#skillsPagination');
  const rawSkills = @json($skills ?? []);
  const normalizedSkills = (rawSkills || []).map(s => {
    const nameText = (typeof s.name_text === 'string' && s.name_text) || toText(s.name) || '';
    const cls = s.classification || null;
    const clsText = (cls && (typeof cls.name_text === 'string')) ? cls.name_text : (cls ? toText(cls.name) : '') || (T.common?.not_set || '');
    return { ...s, name_text: nameText, classification_name_text: clsText };
  });

  let filteredSkills = normalizedSkills.slice();

  // ✅ نبني المختار مسبقاً من pivot: { id: {level, description} }
  let selectedSkills = @json(
    $user->skills->mapWithKeys(function($s){
      return [
        $s->id => [
          'level' => (int)($s->pivot->level ?? 3),
          'description' => $s->pivot->description
        ]
      ];
    })
  ) || {};

  $('#skillFilterType').on('change', function(){
    const v = $(this).val();
    $('#skillNameFilterContainer').toggleClass('d-none', v!=='skill');
    $('#classificationFilterContainer').toggleClass('d-none', v!=='classification');
    if(v==='none'){ filteredSkills = normalizedSkills.slice(); currentSkillsPage=1; renderSkills(); }
  });
  $('#skillNameFilter').on('keyup', function(){
    const q = ($(this).val() || '').toLowerCase();
    filteredSkills = normalizedSkills.filter(s => (s.name_text || '').toLowerCase().includes(q));
    currentSkillsPage=1; renderSkills();
  });
  $('#classificationFilter').on('change', function(){
    const id = $(this).val();
    filteredSkills = id ? normalizedSkills.filter(s => String(s.classification_id) === String(id)) : normalizedSkills.slice();
    currentSkillsPage=1; renderSkills();
  });

  function escapeHtml(str){ return String(str).replace(/[&<>"'`=\/]/g, s => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;', "'":'&#39;','/':'&#x2F;','`':'&#x60;','=':'&#x3D;'}[s])); }

  function renderSkills(){
    const start = (currentSkillsPage-1)*skillsPerPage;
    const page  = filteredSkills.slice(start, start+skillsPerPage);

    let rows = '';
    page.forEach(skill=>{
      const sel   = selectedSkills[String(skill.id)] || null;
      const isOn  = !!sel;
      const lvl   = sel?.level ?? 3;
      const desc  = sel?.description ?? '';

      const levelOptions = [1,2,3,4,5].map(n => {
        const label = (window.T?.skills?.levels?.[n]) || n;
        return `<option value="${n}" ${n===lvl?'selected':''}>${label}</option>`;
      }).join('');

      rows += `
        <tr data-skill-id="${skill.id}">
          <td>${escapeHtml(skill.name_text||'')}</td>
          <td>${escapeHtml(skill.classification_name_text||'')}</td>
          <td class="text-center">
            <select class="form-select form-select-sm skill-level" ${isOn?'':'disabled'}>
              ${levelOptions}
            </select>
          </td>
          <td>
            <input type="text" class="form-control form-control-sm skill-desc"
                   placeholder="${(window.T?.skills?.desc_label)||'Description'}"
                   value="${escapeHtml(desc)}" ${isOn?'':'disabled'}>
          </td>
          <td class="text-center">
            <div class="form-check d-inline-block">
              <input class="form-check-input skill-checkbox" type="checkbox" value="${skill.id}" ${isOn?'checked':''}>
            </div>
          </td>
        </tr>`;
    });
    $skillsTableBody.html(rows);
    renderSkillsPagination();
  }

  function renderSkillsPagination(){
    const total = Math.max(1, Math.ceil(filteredSkills.length/skillsPerPage));
    let html = `
      <li class="page-item ${currentSkillsPage===1?'disabled':''}">
        <a class="page-link" href="#" data-page="prev">${T.profile.pagination_prev}</a>
      </li>`;
    for(let i=1;i<=total;i++){
      html += `<li class="page-item ${i===currentSkillsPage?'active':''}"><a class="page-link" href="#" data-page="${i}">${i}</a></li>`;
    }
    html += `
      <li class="page-item ${currentSkillsPage===total?'disabled':''}">
        <a class="page-link" href="#" data-page="next">${T.profile.pagination_next}</a>
      </li>`;
    $skillsPagination.html(html);
  }

  $skillsPagination.on('click', 'a.page-link', function(e){
    e.preventDefault();
    const p = $(this).data('page');
    const total = Math.max(1, Math.ceil(filteredSkills.length/skillsPerPage));
    if(p==='prev' && currentSkillsPage>1) currentSkillsPage--;
    else if(p==='next' && currentSkillsPage<total) currentSkillsPage++;
    else if(!isNaN(parseInt(p))) currentSkillsPage = parseInt(p);
    renderSkills();
  });

  // أحداث تفاعلية للـcheckbox / level / description
  $skillsTableBody.on('change', '.skill-checkbox', function(){
    const id = String($(this).val());
    const $row = $(this).closest('tr');
    const $lvl = $row.find('.skill-level');
    const $dsc = $row.find('.skill-desc');

    if (this.checked) {
      const level = parseInt($lvl.val() || '3', 10);
      selectedSkills[id] = { level: Math.max(1, Math.min(5, level)), description: $dsc.val() || '' };
      $lvl.prop('disabled', false);
      $dsc.prop('disabled', false);
    } else {
      delete selectedSkills[id];
      $lvl.prop('disabled', true);
      $dsc.prop('disabled', true);
    }
  });

  $skillsTableBody.on('change', '.skill-level', function(){
    const $row = $(this).closest('tr');
    const id   = String($row.data('skill-id'));
    if (!selectedSkills[id]) return;
    const lvl = parseInt($(this).val() || '3', 10);
    selectedSkills[id].level = Math.max(1, Math.min(5, lvl));
  });

  $skillsTableBody.on('input', '.skill-desc', function(){
    const $row = $(this).closest('tr');
    const id   = String($row.data('skill-id'));
    if (!selectedSkills[id]) return;
    selectedSkills[id].description = $(this).val() || '';
  });

  renderSkills();

  /* ===== Languages logic (كما هي) ===== */
  const languagesPerPage = 10;
  let currentLanguagesPage = 1;
  let allLanguagesData = @json($languages ?? []);
  let filteredLanguages = allLanguagesData.slice();

  // selectedLanguages: { [id]: { selected: true, level: 'متوسط' } }
  let selectedLanguages = @json(
    $user->languages->mapWithKeys(fn($l)=>[$l->id => ['selected'=>true, 'level'=>$l->pivot->level]])->toArray()
  ) || {};

  function renderLanguages(){
    const start = (currentLanguagesPage-1)*languagesPerPage;
    const page = filteredLanguages.slice(start, start+languagesPerPage);

    let html='';
    page.forEach(language=>{
      const isSel = Object.prototype.hasOwnProperty.call(selectedLanguages, language.id);
      const level = isSel ? (selectedLanguages[language.id].level||'') : '';
      html += `
      <tr>
        <td>${language.name||''}</td>
        <td>
          <select class="form-select language-level" name="languages[${language.id}][level]" ${isSel?'':'disabled'}>
            <option value="">${T.profile.languages_select_level}</option>
            <option value="مبتدئ جدًا" ${level==='مبتدئ جدًا'?'selected':''}>@json(__('profile.languages.level.a1'))</option>
            <option value="مبتدئ" ${level==='مبتدئ'?'selected':''}>@json(__('profile.languages.level.a2'))</option>
            <option value="ما قبل المتوسط" ${level==='ما قبل المتوسط'?'selected':''}>@json(__('profile.languages.level.b1'))</option>
            <option value="متوسط" ${level==='متوسط'?'selected':''}>@json(__('profile.languages.level.b2'))</option>
            <option value="فوق المتوسط" ${level==='فوق المتوسط'?'selected':''}>@json(__('profile.languages.level.c1'))</option>
            <option value="متقدم جدًا" ${level==='متقدم جدًا'?'selected':''}>@json(__('profile.languages.level.c2'))</option>
          </select>
        </td>
        <td class="text-center">
          <input class="form-check-input language-checkbox" type="checkbox"
                 name="languages[${language.id}][selected]" value="${language.id}" ${isSel?'checked':''}>
        </td>
      </tr>`;
    });
    $('#languagesTableBody').html(html);
    renderLanguagesPagination();
  }

  function renderLanguagesPagination(){
    const total = Math.max(1, Math.ceil(filteredLanguages.length/languagesPerPage));
    let html = `
      <li class="page-item ${currentLanguagesPage===1?'disabled':''}">
        <a class="page-link" href="#" data-page="prev">${T.profile.pagination_prev}</a>
      </li>`;
    for(let i=1;i<=total;i++){
      html += `<li class="page-item ${i===currentLanguagesPage?'active':''}"><a class="page-link" href="#" data-page="${i}">${i}</a></li>`;
    }
    html += `
      <li class="page-item ${currentLanguagesPage===total?'disabled':''}">
        <a class="page-link" href="#" data-page="next">${T.profile.pagination_next}</a>
      </li>`;
    $('#languagesPagination').html(html);
  }

  $('#languageFilter').on('keyup', function(){
    const q = ($(this).val()||'').toLowerCase();
    filteredLanguages = allLanguagesData.filter(l => (l.name||'').toLowerCase().includes(q));
    currentLanguagesPage=1; renderLanguages();
  });

  $('#languagesPagination').on('click','a.page-link', function(e){
    e.preventDefault();
    const p = $(this).data('page');
    const total = Math.max(1, Math.ceil(filteredLanguages.length/languagesPerPage));
    if(p==='prev' && currentLanguagesPage>1) currentLanguagesPage--;
    else if(p==='next' && currentLanguagesPage<total) currentLanguagesPage++;
    else if(!isNaN(parseInt(p))) currentLanguagesPage = parseInt(p);
    renderLanguages();
  });

  $(document).on('change', '.language-checkbox', function(){
    const row = $(this).closest('tr');
    const levelSelect = row.find('.language-level');
    const id = parseInt($(this).val());
    if(this.checked){
      if(!levelSelect.val()) levelSelect.val('مبتدئ');
      levelSelect.prop('disabled', false);
      selectedLanguages[id] = { selected:true, level: levelSelect.val() };
    }else{
      levelSelect.prop('disabled', true);
      delete selectedLanguages[id];
    }
  });
  $(document).on('change', '.language-level', function(){
    const id = parseInt($(this).closest('tr').find('.language-checkbox').val());
    if(selectedLanguages[id]) selectedLanguages[id].level = $(this).val();
  });

  renderLanguages();

  /* ===== Form submit ===== */
  $('#profileForm').on('submit', function(e){
    e.preventDefault();
    const form = this;

    // تحقق من اللغات بدون مستوى
    const invalid = [];
    const validLangs = {};
    Object.keys(selectedLanguages).forEach(id=>{
      const entry = selectedLanguages[id];
      if(!entry.level){
        const name = (allLanguagesData.find(l=>String(l.id)===String(id))||{}).name || T.common.not_set;
        invalid.push(name);
      }else{
        validLangs[id] = { selected:true, level: entry.level };
      }
    });
    if(invalid.length){
      Swal.fire({ title:T.swal.error_title, html: @json(__('profile.languages.select_level')) + '<br>' + invalid.join('<br>'), icon:'error' });
      return;
    }

    // === skills_data كـ Object: { skillId: {level, description} }
    $('.dynamic-skill-input, .dynamic-language-input').remove();

    const skillsPayload = {};
    Object.keys(selectedSkills).forEach(id => {
      const e = selectedSkills[id] || {};
      let d = (e.description || '').toString();
      if (d.length > 1000) d = d.slice(0, 1000); // قص بسيط
      const lvl = Math.max(1, Math.min(5, Number(e.level||3)));
      skillsPayload[id] = { level: lvl, description: d };
    });

    $('<input>',{
      type:'hidden', name:'skills_data',
      value: JSON.stringify(skillsPayload),
      class:'dynamic-skill-input'
    }).appendTo(form);

    $('<input>',{
      type:'hidden', name:'languages_data',
      value: JSON.stringify(validLangs),
      class:'dynamic-language-input'
    }).appendTo(form);

    Swal.fire({
      title:T.swal.confirm_title, text:T.swal.save_question, icon:'question',
      showCancelButton:true, confirmButtonText:T.swal.save_confirm, cancelButtonText:T.swal.cancel
    }).then(res=>{ if(res.isConfirmed) form.submit(); });
  });

  @if (session('success'))
    Swal.fire({ title:T.swal.success_title, text:@json(session('success')), icon:'success' });
  @endif

  // keep navbar height in CSS var
  (function(){
    const root = document.documentElement;
    const nav  = document.querySelector('.navbar.fixed-top') || document.querySelector('.navbar');
    function sync(){ if(!nav) return; root.style.setProperty('--nav-h', Math.ceil(nav.getBoundingClientRect().height) + 'px'); }
    sync(); window.addEventListener('resize', sync);
  })();
});
</script>

<script>
/* ====== (تبقى كما هي) سكربت مؤهلات منفصل إن وجد ====== */
(function () {
  const $form   = $('#qualificationsForm');
  const $alert  = $('#qualificationsAlert');
  const $button = $('#qualificationsSubmitBtn');

  function autosize(el){
    el.style.height = 'auto';
    el.style.height = (el.scrollHeight + 2) + 'px';
  }
  function updateCount(el){
    const id = el.id;
    const cc = document.querySelector('.char-count[data-for="'+id+'"] .count');
    if (cc) cc.textContent = el.value.length;
  }
  document.querySelectorAll('.skill-description').forEach(ta => {
    autosize(ta); updateCount(ta);
    ta.addEventListener('input', function(){
      autosize(ta); updateCount(ta);
      ta.classList.remove('is-invalid');
      const sid = ta.id.replace('skill-desc-','');
      $('#error-skill-'+sid).addClass('d-none').text('');
    });
  });

  function showAlert(type, msg){
    $alert.removeClass('d-none alert-success alert-danger alert-warning')
          .addClass('alert-'+type)
          .html(msg);
    $alert[0].scrollIntoView({ behavior: 'smooth', block: 'start' });
  }
  function clearAlert(){ $alert.addClass('d-none').removeClass('alert-success alert-danger alert-warning').empty(); }

  function setLoading(state){
    const $spin = $button.find('.spinner-border');
    const $txt  = $button.find('.btn-text');
    if (state){
      $button.attr('disabled', true);
      $txt.addClass('d-none'); $spin.removeClass('د-none');
    } else {
      $button.attr('disabled', false);
      $spin.addClass('d-none'); $txt.removeClass('d-none');
    }
  }

  $form.on('submit', async function(e){
    e.preventDefault();
    clearAlert();

    $('.skill-description').removeClass('is-invalid');
    $('[id^="error-skill-"]').addClass('d-none').text('');

    const url  = this.getAttribute('action');
    const fd   = new FormData(this);
    setLoading(true);

    try{
      const res = await axios.post(url, fd, { headers: { 'Accept': 'application/json' } });
      const msg = (res.data && (res.data.message || res.data.success)) || (window.T?.swal?.updated_successfully) || 'تم التحديث بنجاح';
      showAlert('success', msg);
      if (window.Swal) {
        Swal.fire({ toast: true, position: 'top-end', timer: 2000, showConfirmButton: false, icon: 'success', title: msg });
      }
    } catch (err){
      const r = err?.response;
      if (r?.status === 422 && r?.data?.errors){
        const errs = r.data.errors;
        let firstMsg = null;

        Object.keys(errs).forEach(k => {
          const match = k.match(/^skills\.(\d+)\.description$/);
          if (match){
            const sid = match[1];
            const msg = Array.isArray(errs[k]) ? errs[k][0] : String(errs[k]);
            const $ta = $('#skill-desc-'+sid);
            $ta.addClass('is-invalid');
            const $err = $('#error-skill-'+sid);
            $err.removeClass('d-none').text(msg);
            if (!firstMsg) firstMsg = msg;
          }
        });

        showAlert('danger', firstMsg || (window.T?.swal?.delete_error) || 'تحقّق من الحقول.');
      } else {
        const msg = r?.data?.message || (window.T?.swal?.network_error) || 'حدث خطأ في الاتصال';
        showAlert('danger', msg);
      }
    } finally {
      setLoading(false);
    }
  });
})();
</script>
