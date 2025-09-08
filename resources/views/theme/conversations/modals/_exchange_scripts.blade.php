{{-- resources/views/theme/conversations/modals/_exchange_scripts.blade.php --}}
@once
  @push('scripts')
    <script>
      (function(){
        const modalId  = @json($modalId ?? 'exchangeModal');
        const formId   = @json($formId ?? 'exchangeForm');
        const tabBtnId = @json($tabExchangesBtn ?? null);

        const form = document.getElementById(formId);
        if (!form) return;
        const btn  = form.querySelector('#exchangeSendBtn');

        form.addEventListener('submit', async function(e){
          e.preventDefault();

          if (btn) {
            btn.disabled = true;
            var old = btn.innerHTML;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> ' + (window.T?.actions?.sending || 'Sending…');
          }

          try {
            const r = await fetch(form.action, {
              method: 'POST',
              headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': form.querySelector('input[name="_token"]').value,
                'Accept': 'application/json'
              },
              body: new FormData(form)
            });

            const data = await (async()=>{ try { return await r.json(); } catch { return {}; } })();

            if (!r.ok || !data.success) {
              const msg = (data && (data.error || data.message)) || '';

              // === الكشف عن بلوغ الحدّ ===
              const limitHit =
                data?.code === 'LEARN_LIMIT_REACHED' ||
                /الحدّ?\s*الأقصى|limit|reached|maximum/i.test(msg);

              if (limitHit) {
                const exEl   = document.getElementById(modalId);
                const exInst = exEl ? (bootstrap.Modal.getInstance(exEl) || new bootstrap.Modal(exEl)) : null;

                const prEl   = document.getElementById('premiumModal');
                const prInst = prEl ? (bootstrap.Modal.getInstance(prEl) || new bootstrap.Modal(prEl)) : null;

                if (exEl && prInst) {
                  const handler = () => {
                    exEl.removeEventListener('hidden.bs.modal', handler);
                    prInst.show();
                  };
                  // ✅ اسمع على المودال نفسه، مش الفورم
                  exEl.addEventListener('hidden.bs.modal', handler);
                  exInst && exInst.hide();
                } else {
                  // في حال ما فيه مودال Premium، اعرض تنبيه فقط
                  window.Swal && Swal.fire({
                    icon:'error', title:(window.T?.swal?.error||'Error'),
                    text: msg || (window.T?.conversations?.errors?.operation_failed || 'Operation failed')
                  });
                }

                throw new Error('PREMIUM_UPSELL');
              }

              // أخطاء أخرى
              throw (msg || (window.T?.conversations?.errors?.operation_failed || 'Failed'));
            }

            // نجاح
            const exEl   = document.getElementById(modalId);
            const exInst = exEl ? (bootstrap.Modal.getInstance(exEl) || new bootstrap.Modal(exEl)) : null;
            exInst && exInst.hide();

            if (tabBtnId) document.getElementById(tabBtnId)?.click();
            form.reset();
            window.Swal && Swal.fire({ icon:'success', title:(window.T?.swal?.success||'Done'), timer:1800, showConfirmButton:false });

          } catch (err) {
            if (String(err?.message) !== 'PREMIUM_UPSELL') {
              window.Swal && Swal.fire({
                icon:'error', title:(window.T?.swal?.error||'Error'),
                text: String(err || (window.T?.conversations?.errors?.operation_failed || 'Failed'))
              });
            }
          } finally {
            if (btn) { btn.disabled = false; btn.innerHTML = old; }
          }
        });
      })();
    </script>
  @endpush
@endonce
