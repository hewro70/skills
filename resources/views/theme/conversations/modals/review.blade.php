{{-- resources/views/theme/conversations/modals/review.blade.php --}}
<div class="modal fade mt-5" id="{{ $modalId ?? 'reviewModal' }}" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form id="{{ $formId ?? 'reviewForm' }}" class="modal-content"
          action="{{ route('conversations.reviews.store', $conversation) }}"
          method="POST">
      @csrf
      <div class="modal-header">
        <h5 class="modal-title">{{ __('modals.review.title') }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('common.close') }}"></button>
      </div>
      <div class="modal-body">
       <div class="mb-3">
  <label class="form-label d-block">{{ __('modals.review.rating') }}</label>

  {{-- hidden input اللي رح نبعته للسيرفر --}}
  <input type="hidden" name="ratings" id="ratingInput" required>

  {{-- نجوم التقييم --}}
  <div id="starRating"
       class="star-rating d-inline-flex align-items-center gap-1"
       role="radiogroup"
       aria-label="{{ __('modals.review.rating') }}">
    @for ($i = 1; $i <= 5; $i++)
      @php $val = $i; @endphp
      <button type="button"
              class="star"
              data-value="{{ $val }}"
              role="radio"
              aria-checked="false"
              aria-label="{{ $val }}">
        ★
      </button>
    @endfor
    <small class="ms-2 text-muted" id="ratingHelp"></small>
  </div>
</div>

        <div class="mb-3">
          <label class="form-label">{{ __('modals.review.comment') }}</label>
          <textarea name="comment" class="form-control" rows="3" placeholder="{{ __('modals.review.comment_placeholder') }}"></textarea>
        </div>
        <div class="alert alert-info small">
          {{ __('modals.review.hint') }}
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-primary" type="submit" id="reviewSendBtn">{{ __('modals.review.submit') }}</button>
      </div>
    </form>
  </div>
</div>
@push('styles')
<style>
  .star-rating .star{
    font-size: 1.75rem;
    line-height: 1;
    border: 0;
    background: transparent;
    color: #d1d5db;          /* رمادي فاتح */
    cursor: pointer;
    transition: transform .12s ease, color .12s ease;
    padding: 0 .1rem;
  }
  .star-rating .star:hover{ transform: scale(1.1); }
  .star-rating .star.filled{ color: #f59e0b; }   /* ذهبي */
  .star-rating .star:focus{ outline: none; box-shadow: 0 0 0 .2rem rgba(245,158,11,.25); border-radius: .25rem; }
</style>
@endpush
@push('scripts')
<script>
(function(){
  const modal   = document.getElementById(@json($modalId ?? 'reviewModal'));
  if(!modal) return;

  const containerSelector = '#starRating';
  modal.addEventListener('shown.bs.modal', () => {
    const wrap   = modal.querySelector(containerSelector);
    const stars  = wrap?.querySelectorAll('.star') ?? [];
    const input  = modal.querySelector('#ratingInput');
    const help   = modal.querySelector('#ratingHelp');
    let current  = Number(input?.value || 0);

    const labels = {
      1: @json(__('modals.review.rating.1', [], null) ?: '1'),
      2: @json(__('modals.review.rating.2', [], null) ?: '2'),
      3: @json(__('modals.review.rating.3', [], null) ?: '3'),
      4: @json(__('modals.review.rating.4', [], null) ?: '4'),
      5: @json(__('modals.review.rating.5', [], null) ?: '5'),
    };

    function paint(val){
      stars.forEach(s=>{
        const v = Number(s.dataset.value);
        s.classList.toggle('filled', v <= val);
        s.setAttribute('aria-checked', String(v === val));
      });
      if(help) help.textContent = val ? labels[val] : '';
    }

    function setValue(val){
      current = val;
      if(input){ input.value = String(val); input.dispatchEvent(new Event('change')); }
      paint(current);
    }

    // Hover يلوّن مؤقتًا
    stars.forEach(s=>{
      s.addEventListener('mouseenter', ()=> paint(Number(s.dataset.value)));
      s.addEventListener('mouseleave', ()=> paint(current));
      s.addEventListener('click',      ()=> setValue(Number(s.dataset.value)));
      s.addEventListener('keydown', (e)=>{
        // مفاتيح: يسار/يمين + أرقام 1..5 + Enter/Space
        const key = e.key;
        if(['Enter',' '].includes(key)){ e.preventDefault(); setValue(Number(s.dataset.value)); }
        if(['ArrowLeft','ArrowRight'].includes(key)){
          e.preventDefault();
          const step = (key === 'ArrowRight') ? 1 : -1;
          const next = Math.min(5, Math.max(1, (current || 0) + step));
          setValue(next);
          // انقل التركيز للنجم الحالي
          const target = Array.from(stars).find(x => Number(x.dataset.value) === next);
          target?.focus();
        }
        if(/^[1-5]$/.test(key)){ e.preventDefault(); setValue(Number(key)); }
      });
    });

    // إعادة التهيئة عند فتح المودال
    paint(current);

    // لو بدك افتراضيًا 5 نجوم عند الفتح، فعّل السطر التالي:
    // if(!current) setValue(5);
  });

  // تنظيف عند إغلاق المودال (اختياري)
  modal.addEventListener('hidden.bs.modal', ()=>{
    const input = modal.querySelector('#ratingInput');
    const help  = modal.querySelector('#ratingHelp');
    const stars = modal.querySelectorAll('.star');
    if(help) help.textContent = '';
    stars.forEach(s=>{ s.classList.remove('filled'); s.setAttribute('aria-checked','false'); });
    // لا نفرّغ القيمة حتى ما نزعّل المستخدم لو سكّر بالغلط
    // لو بدك تفريغ:
    // if(input) input.value = '';
  });
})();
</script>
@endpush
