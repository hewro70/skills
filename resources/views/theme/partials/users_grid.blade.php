@forelse ($users as $user)
    <div class="talent-card">
        <div class="talent-header">
            <img src="{{ $user->getImageUrlAttribute() }}" alt="Talent" class="talent-avatar">
            <div class="talent-info">
                <h4>{{ $user->fullName() }}</h4>
                <p>
                    @foreach ($user->languages as $language)
                        {{ $language->name . ' ' . $language->pivot->level . ' | ' }}
                    @endforeach
                </p>
                <span class="talent-location">{{ $user->country->name ?? 'غير محدد' }}</span>
            </div>
        </div>
        <div class="talent-stats">
            <span><i class="bi bi-star-fill"></i> 4.9 (128 تقييم)</span>
            <span><i class="bi bi-check-circle-fill"></i> 95% معدل استجابة</span>
        </div>
        <div class="talent-description">
            <p>{{ $user->about_me ?? 'غير محدد' }}</p>
        </div>
        <div class="talent-skills">
            @foreach ($user->skills as $skill)
                <span class="skill-tag">{{ $skill->name }}</span>
            @endforeach
        </div>

        <div class="talent-actions">
            <a href="{{ route('theme.profile.show', $user) }}" class="btn btn-primary">عرض الملف الشخصي</a>
            @if (auth()->check() && auth()->id() !== $user->id)
                <form action="{{ route('invitations.send') }}" method="POST" class="d-inline invitation-form"
                    data-user-id="{{ $user->id }}" data-user-name="{{ $user->fullName() }}"
                    id="invitation-form-{{ $user->id }}">
                    @csrf
                    <input type="hidden" name="destination_user_id" value="{{ $user->id }}">
                    <button type="button" class="btn btn-outline-primary send-invitation-btn">دعوة</button>
                </form>
            @endif
        </div>

    </div>
@empty
    <div class="col-12 text-center py-5">
        <h4>لا توجد نتائج مطابقة للبحث</h4>
    </div>
@endforelse
