{{--
    Partial: Review Form
    Variables expected:
      $event        — Event model
      $userReview   — Review|null  (existing review for editing, or null for new)
      $formAction   — string (route URL)
      $method       — string ('POST' or 'PUT')
      $formId       — string (unique form ID for JS targeting)
--}}

<form id="{{ $formId }}" method="POST" action="{{ $formAction }}" style="margin: 0;">
    @csrf
    @if($method === 'PUT')
        @method('PUT')
    @endif

    {{-- ── Interactive Star Selector ─────────────────────────────────────── --}}
    <div style="margin-bottom: var(--space-4);">
        <label class="label" style="display: block; margin-bottom: var(--space-2);">
            RATING <span style="color: var(--feedback-error);">*</span>
        </label>
        <div class="star-selector" id="stars-{{ $formId }}" style="display: flex; gap: var(--space-2);">
            @for ($s = 1; $s <= 5; $s++)
                <button type="button"
                    data-value="{{ $s }}"
                    data-form="{{ $formId }}"
                    onclick="setRating({{ $s }}, '{{ $formId }}')"
                    onmouseover="hoverRating({{ $s }}, '{{ $formId }}')"
                    onmouseout="resetHover('{{ $formId }}')"
                    style="background: none; border: none; cursor: pointer; padding: 0; line-height: 1; transition: transform 0.1s ease;"
                    onmouseover="hoverRating({{ $s }}, '{{ $formId }}'); this.style.transform='scale(1.2)';"
                    onmouseout="resetHover('{{ $formId }}'); this.style.transform='scale(1)';"
                    aria-label="{{ $s }} bintang">
                    <svg class="star-icon-{{ $formId }}" data-star="{{ $s }}" width="36" height="36" viewBox="0 0 24 24"
                         fill="{{ ($userReview && $s <= $userReview->rating) ? '#f59e0b' : 'none' }}"
                         stroke="{{ ($userReview && $s <= $userReview->rating) ? '#f59e0b' : 'var(--slate-600)' }}"
                         stroke-width="1.5" style="transition: fill 0.15s, stroke 0.15s;">
                        <path d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.562.562 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"/>
                    </svg>
                </button>
            @endfor
        </div>
        {{-- Hidden input that holds the selected rating value --}}
        <input type="hidden" name="rating" id="rating-{{ $formId }}"
               value="{{ $userReview?->rating ?? '' }}" required>
        <p id="rating-label-{{ $formId }}" class="caption" style="color: var(--slate-400); margin: var(--space-1) 0 0 0; min-height: 18px;">
            @if($userReview)
                {{ ['', 'Buruk', 'Cukup', 'Baik', 'Sangat Baik', 'Luar Biasa'][$userReview->rating] }}
            @endif
        </p>
    </div>

    {{-- ── Review Title ──────────────────────────────────────────────────── --}}
    <div style="margin-bottom: var(--space-4);">
        <label class="label" style="display: block; margin-bottom: var(--space-2);">
            JUDUL ULASAN <span class="caption" style="color: var(--slate-400);">(Opsional)</span>
        </label>
        <input type="text"
               name="title"
               value="{{ old('title', $userReview?->title) }}"
               placeholder="Ringkasan singkat pengalaman Anda..."
               maxlength="100"
               class="input"
               style="width: 100%; box-sizing: border-box;">
        @error('title')
            <p class="caption" style="color: var(--feedback-error); margin: 4px 0 0 0;">{{ $message }}</p>
        @enderror
    </div>

    {{-- ── Review Body ───────────────────────────────────────────────────── --}}
    <div style="margin-bottom: var(--space-4);">
        <label class="label" style="display: block; margin-bottom: var(--space-2);">
            ULASAN <span style="color: var(--feedback-error);">*</span>
        </label>
        <textarea name="body"
                  rows="4"
                  minlength="10"
                  maxlength="1000"
                  placeholder="Bagikan pengalaman Anda menghadiri event ini... (min. 10 karakter)"
                  class="input"
                  id="body-{{ $formId }}"
                  oninput="updateCharCount(this, 'charcount-{{ $formId }}')"
                  style="width: 100%; box-sizing: border-box; resize: vertical; min-height: 100px;">{{ old('body', $userReview?->body) }}</textarea>
        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 4px;">
            @error('body')
                <p class="caption" style="color: var(--feedback-error); margin: 0;">{{ $message }}</p>
            @else
                <span></span>
            @enderror
            <span id="charcount-{{ $formId }}" class="caption" style="color: var(--slate-400);">
                {{ strlen($userReview?->body ?? old('body', '')) }}/1000
            </span>
        </div>
    </div>

    {{-- ── Submit ────────────────────────────────────────────────────────── --}}
    <div style="display: flex; gap: var(--space-3); align-items: center; flex-wrap: wrap;">
        <button type="submit" class="btn btn-primary" style="padding: var(--space-3) var(--space-6);">
            {{ $userReview ? 'PERBARUI ULASAN' : 'KIRIM ULASAN' }}
        </button>
        <span class="caption" style="color: var(--slate-400);">Ulasan akan langsung ditampilkan setelah dikirim.</span>
    </div>
</form>

<script>
    // ── Star rating interactive logic ────────────────────────────────────────
    const ratingLabels = ['', 'Buruk', 'Cukup', 'Baik', 'Sangat Baik', 'Luar Biasa'];

    function setRating(value, formId) {
        document.getElementById('rating-' + formId).value = value;
        paintStars(value, formId);
        document.getElementById('rating-label-' + formId).textContent = ratingLabels[value];
    }

    function hoverRating(value, formId) {
        paintStars(value, formId, true);
    }

    function resetHover(formId) {
        const selected = document.getElementById('rating-' + formId).value;
        paintStars(selected ? parseInt(selected) : 0, formId);
    }

    function paintStars(upTo, formId, isHover) {
        document.querySelectorAll('.star-icon-' + formId).forEach(svg => {
            const star = parseInt(svg.dataset.star);
            const filled = star <= upTo;
            svg.setAttribute('fill', filled ? '#f59e0b' : 'none');
            svg.setAttribute('stroke', filled ? '#f59e0b' : (isHover ? 'var(--slate-500)' : 'var(--slate-600))'));
        });
    }

    function updateCharCount(textarea, countId) {
        document.getElementById(countId).textContent = textarea.value.length + '/1000';
    }

    // ── Edit modal logic (called from review-card partial) ───────────────────
    function openEditModal(reviewId, rating, title, body) {
        const modal = document.getElementById('edit-review-modal');
        if (!modal) return;
        // Populate form
        document.getElementById('edit-review-form').action =
            '{{ url("/reviews") }}/' + reviewId;
        document.getElementById('edit-title').value = title;
        document.getElementById('edit-body').value = body;
        updateCharCount(document.getElementById('edit-body'), 'charcount-edit-form');
        setRating(rating, 'edit-form');
        modal.style.display = 'flex';
    }

    function closeEditModal() {
        const modal = document.getElementById('edit-review-modal');
        if (modal) modal.style.display = 'none';
    }

    // Close on backdrop click
    document.addEventListener('click', function(e) {
        const modal = document.getElementById('edit-review-modal');
        if (modal && e.target === modal) closeEditModal();
    });
</script>
