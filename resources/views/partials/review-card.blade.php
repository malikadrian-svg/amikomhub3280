{{--
    Partial: Single Review Card
    Variables expected:
      $review  — Review model (with user relationship loaded)
--}}

<div style="border: 2px solid var(--slate-600); background-color: var(--slate-800); padding: var(--space-5); margin-bottom: var(--space-4); position: relative;">

    {{-- Reviewer info + star rating --}}
    <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: var(--space-3); margin-bottom: var(--space-3);">
        <div style="display: flex; align-items: center; gap: var(--space-3);">
            {{-- Avatar --}}
            @if($review->user->avatar)
                <img src="{{ $review->user->avatar }}" alt="{{ $review->user->name }}"
                     style="width: 40px; height: 40px; border-radius: 50%; border: 2px solid var(--purple-500); object-fit: cover; flex-shrink: 0;">
            @else
                <div style="width: 40px; height: 40px; border-radius: 50%; background-color: var(--purple-500); color: #ffffff; display: flex; align-items: center; justify-content: center; font-family: 'Space Grotesk', sans-serif; font-weight: 700; font-size: 16px; flex-shrink: 0; border: 2px solid var(--purple-700);">
                    {{ strtoupper(substr($review->user->name, 0, 1)) }}
                </div>
            @endif
            <div>
                <p style="margin: 0; font-family: 'Space Grotesk', sans-serif; font-weight: 700; font-size: 14px; color: var(--slate-0);">
                    {{ $review->user->name }}
                </p>
                <p class="caption" style="margin: 0; color: var(--slate-400);">{{ $review->timeAgo() }}</p>
            </div>
        </div>

        {{-- Star rating --}}
        <div style="display: flex; gap: 2px; align-items: center; flex-shrink: 0;">
            @for ($s = 1; $s <= 5; $s++)
                <svg width="16" height="16" viewBox="0 0 24 24"
                     fill="{{ $s <= $review->rating ? '#f59e0b' : 'none' }}"
                     stroke="{{ $s <= $review->rating ? '#f59e0b' : 'var(--slate-600)' }}"
                     stroke-width="1.5">
                    <path d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.562.562 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"/>
                </svg>
            @endfor
            <span style="font-family: 'IBM Plex Mono', monospace; font-size: 12px; font-weight: 700; color: var(--slate-400); margin-left: 4px;">{{ $review->rating }}/5</span>
        </div>
    </div>

    {{-- Review title --}}
    @if($review->title)
        <p style="margin: 0 0 var(--space-2) 0; font-family: 'Space Grotesk', sans-serif; font-weight: 700; font-size: 15px; color: var(--slate-0);">
            {{ $review->title }}
        </p>
    @endif

    {{-- Review body --}}
    <p class="body" style="margin: 0; color: var(--slate-200); line-height: 1.7; white-space: pre-line;">{{ $review->body }}</p>

    {{-- Author controls (edit / delete) --}}
    @auth
        @if(Auth::id() === $review->user_id)
            <div style="display: flex; gap: var(--space-3); margin-top: var(--space-4); padding-top: var(--space-3); border-top: 1px solid var(--slate-700);">
                {{-- Trigger edit modal --}}
                <button
                    onclick="openEditModal({{ $review->id }}, {{ $review->rating }}, '{{ addslashes($review->title ?? '') }}', {{ json_encode($review->body) }})"
                    style="font-family: 'IBM Plex Mono', monospace; font-size: 11px; font-weight: 700; color: var(--purple-500); background: none; border: 1.5px solid var(--purple-500); padding: 4px 12px; cursor: pointer; letter-spacing: 0.04em; transition: all 0.15s;"
                    onmouseover="this.style.backgroundColor='var(--purple-500)'; this.style.color='#fff';"
                    onmouseout="this.style.backgroundColor='transparent'; this.style.color='var(--purple-500)';">
                    ✏ EDIT
                </button>
                {{-- Delete form --}}
                <form method="POST" action="{{ route('reviews.destroy', $review) }}"
                      onsubmit="return confirm('Hapus ulasan ini secara permanen?')" style="margin:0;">
                    @csrf @method('DELETE')
                    <button type="submit"
                        style="font-family: 'IBM Plex Mono', monospace; font-size: 11px; font-weight: 700; color: var(--feedback-error); background: none; border: 1.5px solid var(--feedback-error); padding: 4px 12px; cursor: pointer; letter-spacing: 0.04em; transition: all 0.15s;"
                        onmouseover="this.style.backgroundColor='var(--feedback-error)'; this.style.color='#fff';"
                        onmouseout="this.style.backgroundColor='transparent'; this.style.color='var(--feedback-error)';">
                        ✕ HAPUS
                    </button>
                </form>
            </div>
        @endif
    @endauth

</div>
