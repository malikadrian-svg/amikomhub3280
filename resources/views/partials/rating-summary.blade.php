{{--
    Partial: Rating Summary Block
    Variables expected:
      $avgRating    — float|null  (e.g. 4.3)
      $reviewCount  — int         (e.g. 142)
      $distribution — array       [5=>n, 4=>n, 3=>n, 2=>n, 1=>n]
--}}

@php
    $total = array_sum($distribution);
@endphp

<div style="background-color: var(--slate-800); border: 2px solid var(--slate-600); padding: var(--space-6); margin-bottom: var(--space-6); box-shadow: var(--shadow-hard-sm);">
    <div style="display: flex; flex-wrap: wrap; gap: var(--space-8); align-items: flex-start;">

        {{-- Big average score --}}
        <div style="text-align: center; flex-shrink: 0;">
            <div style="font-family: 'Space Grotesk', sans-serif; font-size: 56px; font-weight: 700; color: var(--slate-0); line-height: 1;">
                {{ $avgRating ?? '—' }}
            </div>
            {{-- Star display --}}
            <div style="display: flex; justify-content: center; gap: 3px; margin: var(--space-2) 0;">
                @for ($s = 1; $s <= 5; $s++)
                    @if ($avgRating && $s <= round($avgRating))
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="#f59e0b" stroke="#f59e0b" stroke-width="1.5">
                            <path d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.562.562 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"/>
                        </svg>
                    @else
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--slate-600)" stroke-width="1.5">
                            <path d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.562.562 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"/>
                        </svg>
                    @endif
                @endfor
            </div>
            <p class="caption" style="color: var(--slate-400); margin: 0;">
                Berdasarkan <strong style="color: var(--slate-0);">{{ number_format($reviewCount) }}</strong> ulasan
            </p>
        </div>

        {{-- Rating distribution bars --}}
        <div style="flex: 1; min-width: 200px;">
            @foreach ($distribution as $star => $count)
                @php $pct = $total > 0 ? round(($count / $total) * 100) : 0; @endphp
                <div style="display: flex; align-items: center; gap: var(--space-3); margin-bottom: var(--space-2);">
                    {{-- Star label --}}
                    <span style="font-family: 'IBM Plex Mono', monospace; font-size: 12px; font-weight: 700; color: var(--slate-400); white-space: nowrap; min-width: 36px;">
                        {{ $star }} ★
                    </span>
                    {{-- Progress bar --}}
                    <div style="flex: 1; height: 8px; background-color: var(--slate-700); border: 1px solid var(--slate-600);">
                        <div style="width: {{ $pct }}%; height: 100%; background-color: #f59e0b; transition: width 0.4s ease;"></div>
                    </div>
                    {{-- Count --}}
                    <span style="font-family: 'IBM Plex Mono', monospace; font-size: 11px; color: var(--slate-400); min-width: 28px; text-align: right;">
                        {{ $count }}
                    </span>
                </div>
            @endforeach
        </div>

    </div>
</div>
