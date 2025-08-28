{{-- resources/views/theme/conversations/modals/_review_scripts.blade.php --}}
@once
  @push('scripts')
    <script>
      (function(){
        const form = document.getElementById(@json($formId ?? 'reviewForm'));
        if(!form) return;
        const modal = document.getElementById(@json($modalId ?? 'reviewModal'));
        const btn   = form.querySelector('button[type="submit"]');

        form.addEventListener('submit', async function(e){
          e.preventDefault();
          if(btn){ btn.disabled = true; var old = btn.innerHTML; btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> ' + (window.T?.actions?.sending || 'Sending…'); }
          try{
            const r = await fetch(form.action, { method:'POST', headers:{'X-Requested-With':'XMLHttpRequest','Accept':'application/json','X-CSRF-TOKEN': form.querySelector('input[name="_token"]').value}, body: new FormData(form) });
            const data = await (async()=>{ try{return await r.json()}catch{return {}} })();
            if(!r.ok || !data.success) throw (data.error || data.message || (data.errors && Object.values(data.errors).flat().join('\n')) || 'Failed');
            (bootstrap.Modal.getInstance(modal) || new bootstrap.Modal(modal)).hide();
            form.reset();
            if(window.Swal) Swal.fire({icon:'success', title:(window.T?.swal?.success||'Done'), timer:1800, showConfirmButton:false});
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
