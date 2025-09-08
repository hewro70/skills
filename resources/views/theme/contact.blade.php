@extends('theme.master')
@section('contact-active', 'active')
    

  
@section('content')
      @include('theme.partials.heroSection', [
      'title'       => __('contact.hero.title'),
      'description' => __('contact.hero.subtitle'),
      'current'     => __('contact.hero.current'),
      'bgImage'     => asset('img/hero-about.jpg'),
      'height'      => 'sm',
      'overlay'     => 'auto',
    ])
<main class="main">

  <!-- Contact Section -->
  <section id="contact" class="contact section">
    <div class="container" data-aos="fade-up" data-aos-delay="100">
      <div class="row justify-content-between align-items-start">

        <!-- Info Section -->
        <div class="col-lg-4">
          <!--<div class="info-item d-flex mb-4" data-aos="fade-up" data-aos-delay="500">-->
          <!--  <i class="bi bi-building flex-shrink-0 me-3"></i>-->
          <!--  <div>-->
          <!--    <h3>{{ __('contact.org_name') }}</h3>-->
          <!--    <p>{{ __('contact.org_tagline') }}</p>-->
          <!--  </div>-->
          <!--</div>-->

          <div class="info-item d-flex mb-4" data-aos="fade-up" data-aos-delay="300">
            <i class="bi bi-envelope flex-shrink-0 me-3"></i>
            <div>
              <h3>{{ __('contact.email_title') }}</h3>
              <p>info@maharathub.com</p>
            </div>
          </div>

          <!--<div class="info-item d-flex mb-4" data-aos="fade-up" data-aos-delay="400">-->
          <!--  <i class="bi bi-telephone flex-shrink-0 me-3"></i>-->
          <!--  <div>-->
          <!--    <h3>{{ __('contact.phone_title') }}</h3>-->
          <!--    <p>{{ __('contact.phone_value') }}</p>-->
          <!--  </div>-->
          <!--</div>-->
        </div>

        <!-- Form Section -->
        <div class="col-lg-8">
          <form action="{{ route('contact.submit') }}" method="POST" class="p-4"
                style="border: 1px solid #333; border-radius: .25rem;" novalidate>
            @csrf

            <div class="row gy-4">

              <div class="col-md-6 form-group">
                <label class="form-label" for="first_name">{{ __('contact.first_name') }}</label>
                <input type="text" id="first_name" name="first_name" class="form-control"
                       placeholder="{{ __('contact.first_name_ph') }}"
                       value="{{ old('first_name') }}" required>
                <ul class="text-danger list-unstyled mt-1 field-errors"></ul>
              </div>

              <div class="col-md-6 form-group">
                <label class="form-label" for="last_name">{{ __('contact.last_name') }}</label>
                <input type="text" id="last_name" name="last_name" class="form-control"
                       placeholder="{{ __('contact.last_name_ph') }}"
                       value="{{ old('last_name') }}" required>
                <ul class="text-danger list-unstyled mt-1 field-errors"></ul>
              </div>

              <div class="col-md-6 form-group">
                <label class="form-label" for="email">{{ __('contact.email') }}</label>
                <input type="email" id="email" name="email" class="form-control"
                       placeholder="{{ __('contact.email_ph') }}"
                       value="{{ old('email') }}" required>
                <ul class="text-danger list-unstyled mt-1 field-errors"></ul>
              </div>

              <div class="col-md-6 form-group">
                <label class="form-label" for="phone">{{ __('contact.phone') }}</label>
                <input type="text" id="phone" name="phone" class="form-control"
                       placeholder="{{ __('contact.phone_ph') }}"
                       value="{{ old('phone') }}">
                <ul class="text-danger list-unstyled mt-1 field-errors"></ul>
              </div>

              <div class="col-md-12 form-group">
                <label class="form-label" for="service">{{ __('contact.service') }}</label>
                <select id="service" name="service" class="form-control" required>
                  <option value="" selected disabled>{{ __('contact.service_select') }}</option>
                  <option value="collaboration" {{ old('service')=='collaboration'?'selected':'' }}>
                    {{ __('contact.service_collab') }}
                  </option>
                  <option value="Complaints" {{ old('service')=='Complaints'?'selected':'' }}>
                    {{ __('contact.service_complaint') }}
                  </option>
                  <option value="note" {{ old('service')=='note'?'selected':'' }}>
                    {{ __('contact.service_suggestion') }}
                  </option>
                </select>
                <ul class="text-danger list-unstyled mt-1 field-errors"></ul>
              </div>

              <div class="col-md-12 form-group">
                <label class="form-label" for="message">{{ __('contact.message') }}</label>
                <textarea id="message" class="form-control" name="message" rows="6"
                          placeholder="{{ __('contact.message_ph') }}" required>{{ old('message') }}</textarea>
                <ul class="text-danger list-unstyled mt-1 field-errors"></ul>
              </div>

              <div class="col-md-12 text-center">
                @if (session('success'))
                  <div class="alert alert-success">
                    {{ session('success') }}
                  </div>
                @endif
                <button type="submit" class="btn btn-primary">
                  {{ __('contact.send') }}
                </button>
              </div>

            </div>
          </form>
        </div>

      </div>
    </div>
  </section>
</main>
@endsection

@section('scripts')
<script>
  $(document).ready(function () {
    // نصوص مترجمة للرسائل
    const T = {
      missingDataTitle:  @json(__('contact.alert.missing_title')),
      missingDataText:   @json(__('contact.alert.missing_text')),
      ok:                @json(__('contact.alert.ok')),
      successTitle:      @json(__('contact.alert.success_title')),
      successText:       @json(__('contact.alert.success_text')),
      invalidEmail:      @json(__('contact.alert.invalid_email')),
      errorTitle:        @json(__('contact.alert.error_title')),
      errorText:         @json(__('contact.alert.error_text')),
      fixErrorsTitle:    @json(__('contact.alert.fix_title')),
      fixErrorsText:     @json(__('contact.alert.fix_text')),
      sending:           @json(__('contact.alert.sending')),
    };

    // أسماء الحقول لرسائل "حقل X مطلوب"
    const DISPLAY = {
      first_name: @json(__('contact.first_name')),
      last_name:  @json(__('contact.last_name')),
      email:      @json(__('contact.email')),
      service:    @json(__('contact.service')),
      message:    @json(__('contact.message')),
    };

    function showValidationError(input, message) {
      const errorContainer = input.closest('.form-group').querySelector('.field-errors');
      if (!errorContainer) return;
      errorContainer.innerHTML = `<li>${message}</li>`;
      errorContainer.style.display = 'block';
    }

    function clearValidationError(input) {
      const errorContainer = input.closest('.form-group')?.querySelector('.field-errors');
      if (!errorContainer) return;
      errorContainer.innerHTML = '';
      errorContainer.style.display = 'none';
    }

    $('form').on('submit', function (e) {
      e.preventDefault();
      const form = $(this);
      const submitBtn = form.find('button[type="submit"]');

      // clear old
      form.find('.field-errors').empty().hide();

      let isValid = true;
      let firstErrorField = null;

      // required
      form.find('[required]').each(function () {
        const $input = $(this);
        const val = ($input.val() || '').trim();
        if (!val) {
          const name = $input.attr('name');
          const label = DISPLAY[name] || name;
          showValidationError($input[0], `{{ __('contact.field_required') }}`.replace(':field', label));
          if (!firstErrorField) firstErrorField = $input;
          isValid = false;
        }
      });

      // email format
      const emailInput = form.find('[name="email"]');
      const emailVal = (emailInput.val() || '').trim();
      if (emailVal && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailVal)) {
        showValidationError(emailInput[0], T.invalidEmail);
        if (!firstErrorField) firstErrorField = emailInput;
        isValid = false;
      }

      if (!isValid) {
        Swal.fire({
          icon: 'error',
          title: T.missingDataTitle,
          text:  T.missingDataText,
          confirmButtonText: T.ok
        });
        if (firstErrorField?.length) {
          $('html, body').animate({ scrollTop: firstErrorField.offset().top - 100 }, 500);
        }
        return;
      }

      submitBtn.prop('disabled', true).html(`
        <span class="spinner-border spinner-border-sm" role="status"></span>
        ${T.sending}
      `);

      $.ajax({
        url: form.attr('action'),
        method: form.attr('method'),
        data: form.serialize(),
        dataType: 'json',
        success: function (response) {
          submitBtn.prop('disabled', false).text(@json(__('contact.send')));
          Swal.fire({
            icon: 'success',
            title: T.successTitle,
            text:  response?.message ?? T.successText,
            confirmButtonText: T.ok
          }).then(() => {
            form[0].reset();
            form.find('.field-errors').empty().hide();
          });
        },
        error: function (xhr) {
          submitBtn.prop('disabled', false).text(@json(__('contact.send')));

          if (xhr.status === 422) {
            const errors = xhr.responseJSON.errors || {};
            for (const field in errors) {
              const input = form.find(`[name="${field}"]`);
              if (input.length) {
                const errorContainer = input.closest('.form-group').find('.field-errors');
                errorContainer.empty();
                errors[field].forEach(msg => errorContainer.append(`<li>${msg}</li>`).show());
              }
            }
            Swal.fire({ icon: 'error', title: T.fixErrorsTitle, text: T.fixErrorsText, confirmButtonText: T.ok });
          } else {
            Swal.fire({ icon: 'error', title: T.errorTitle, text: T.errorText, confirmButtonText: T.ok });
          }
        }
      });
    });

    // live clear + live email check
    $('form [required]').on('input blur', function () {
      clearValidationError(this);
      if (this.name === 'email') {
        const v = this.value.trim();
        if (v && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v)) {
          showValidationError(this, T.invalidEmail);
        }
      }
    });
  });
</script>

<style>
  .field-errors{ display:none; color:#dc3545; font-size:.875rem; margin-top:.25rem; }
  .field-errors li{ list-style-type:none; }
</style>
@endsection
