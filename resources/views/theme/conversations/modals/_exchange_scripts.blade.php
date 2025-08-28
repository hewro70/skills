{{-- resources/views/theme/conversations/modals/_exchange_scripts.blade.php --}}
@once
  @push('scripts')
    <script>
      (function(){
        const modalId = @json($modalId ?? 'exchangeModal');
        const formId  = @json($formId ?? 'exchangeForm');
        const tabBtnId= @json($tabExchangesBtn ?? null);

        const form = document.getElementById(formId);
        if(!form) return;
        const btn  = form.querySelector('#exchangeSendBtn');

        form.addEventListener('submit', async function(e){
          e.preventDefault();
          if(btn){ btn.disabled = true; var old = btn.innerHTML; btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> ' + (window.T?.actions?.sending || 'Sending…'); }
          try{
            const r = await fetch(form.action, { method:'POST', headers:{'X-Requested-With':'XMLHttpRequest','X-CSRF-TOKEN': form.querySelector('input[name="_token"]').value, 'Accept':'application/json' }, body: new FormData(form) });
            const data = await (async()=>{ try{return await r.json()}catch{return {}} })();
            if(!r.ok || !data.success){
              const msg = (data && (data.error || data.message)) || '';
              const t1  = window.T?.premium?.upsell_trigger_text_1 || '';
              const t2  = window.T?.premium?.upsell_trigger_2   || '';
              if (t1 && msg.includes(t1) || (t2 && msg.includes(t2))) {
                const exInst = bootstrap.Modal.getInstance(document.getElementById(modalId)) || new bootstrap.Modal('#'+modalId);
                const prEl   = document.getElementById('premiumModal');
                const prInst = prEl ? (bootstrap.Modal.getInstance(prEl) || new bootstrap.Modal(prEl)) : null;
                const handler = ()=>{ form.removeEventListener('hidden.bs.modal', handler); prInst && prInst.show(); };
                form.addEventListener && form.addEventListener('hidden.bs.modal', handler);
                exInst.hide();
                throw new Error('PREMIUM_UPSELL');
              }
              throw (msg || 'Failed');
            }
            (bootstrap.Modal.getInstance(document.getElementById(modalId)) || new bootstrap.Modal('#'+modalId)).hide();
            if (tabBtnId) document.getElementById(tabBtnId)?.click();
            form.reset();
            if(window.Swal) Swal.fire({icon:'success', title:(window.T?.swal?.success||'Done'), timer:1800, showConfirmButton:false});
          }catch(err){
            if (String(err?.message) !== 'PREMIUM_UPSELL') {
              if(window.Swal) Swal.fire({icon:'error', title:(window.T?.swal?.error||'Error'), text: String(err||'Failed')});
            }
          }finally{
            if(btn){ btn.disabled = false; btn.innerHTML = old; }
          }
        });
      })();
    </script>
  @endpush
@endonce
