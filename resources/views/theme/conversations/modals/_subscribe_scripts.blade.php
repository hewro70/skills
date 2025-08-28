{{-- resources/views/theme/conversations/modals/_subscribe_scripts.blade.php --}}
@once
  @push('scripts')
    <script>
      (function(){
        const modalId = @json($modalId ?? 'subscribeModal');
        const formId  = @json($formId ?? 'subscribeForm');
        const action  = @json($action ?? "{{ route('premium.requests.store') }}");

        const form = document.getElementById(formId);
        if(!form) return;
        form.setAttribute('action', action);

        function $(id){ return document.getElementById(id); }

        form.addEventListener('change', function(e){
          const r = e.target.closest('input[name="where"]');
          if(!r) return;
          const isJordan = r.value === 'jordan';
          $('receivingJordan')?.classList.toggle('d-none', !isJordan);
          $('fieldWallet')?.classList.toggle('d-none', !isJordan);
          $('receivingIntl')?.classList.toggle('d-none', isJordan);
          $('fieldPaypal')?.classList.toggle('d-none', isJordan);
          $('provider').value = isJordan ? 'click' : 'paypal';
          const w = form.querySelector('[name="sender_wallet_name"]');
          const p = form.querySelector('[name="sender_paypal_email"]');
          if (w) w.required = isJordan;
          if (p) p.required = !isJordan;
        });

        form.addEventListener('submit', async function(e){
          e.preventDefault();
          const acct = $('siteAccountName')?.value?.trim() || '';
          $('reference').value = `premium - ${acct}`;

          const btn = $('subscribeSendBtn');
          const old = btn?.innerHTML;
          if(btn){ btn.disabled = true; btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> ' + (window.T?.actions?.sending || 'Processing…'); }

          try{
            const res = await fetch(action, {
              method: 'POST',
              headers: { 'X-Requested-With':'XMLHttpRequest', 'X-CSRF-TOKEN': form.querySelector('input[name="_token"]').value, 'Accept':'application/json' },
              body: new FormData(form)
            });
            let data = {}; try { data = await res.json(); } catch {}
            if(!res.ok || !data.success) throw data?.error || data?.message || (data?.errors && Object.values(data.errors).flat().join('\n')) || 'Failed';
            if(window.Swal) Swal.fire({icon:'success', title:(window.T?.toasts?.request_received||'Request received'), text:(window.T?.toasts?.premium_activation||''), timer:2200, showConfirmButton:false});
            (bootstrap.Modal.getInstance($(modalId)) || new bootstrap.Modal('#'+modalId)).hide();
            form.reset();
          }catch(err){
            if(window.Swal) Swal.fire({icon:'error', title:(window.T?.swal?.error||'Error'), text: String(err||'Failed')});
          }finally{
            if(btn){ btn.disabled = false; btn.innerHTML = old; }
          }
        });
      })();
    </script>
  @endpush
@endonce
