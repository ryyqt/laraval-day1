{{--
    Shared Flash Messages Partial
    Usage: @include('partials.flash')
    Listens for: success | error | warning | info session keys
--}}

@php
    $flashTypes = [
        'success' => [
            'bg'     => 'rgba(16,185,129,0.10)',
            'border' => 'rgba(16,185,129,0.30)',
            'color'  => '#10b981',
            'icon'   => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>',
        ],
        'error' => [
            'bg'     => 'rgba(244,63,94,0.10)',
            'border' => 'rgba(244,63,94,0.30)',
            'color'  => '#f43f5e',
            'icon'   => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z"/>',
        ],
        'warning' => [
            'bg'     => 'rgba(245,158,11,0.10)',
            'border' => 'rgba(245,158,11,0.30)',
            'color'  => '#f59e0b',
            'icon'   => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z"/>',
        ],
        'info' => [
            'bg'     => 'rgba(99,102,241,0.10)',
            'border' => 'rgba(99,102,241,0.25)',
            'color'  => '#6366f1',
            'icon'   => '<path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z"/>',
        ],
    ];
@endphp

<style>
    .flash-wrap { display: flex; flex-direction: column; gap: 10px; margin-bottom: 22px; }
    .flash {
        display: flex; align-items: center; gap: 12px;
        padding: 13px 16px;
        border-radius: var(--radius-sm);
        font-size: 0.875rem; font-weight: 500;
        border: 1px solid;
        animation: flash-in 0.3s ease;
        position: relative;
    }
    @keyframes flash-in {
        from { opacity: 0; transform: translateY(-8px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .flash svg.flash-icon { width: 18px; height: 18px; flex-shrink: 0; }
    .flash-msg { flex: 1; }
    .flash-close {
        background: none; border: none; cursor: pointer;
        opacity: 0.5; transition: opacity 0.2s;
        display: flex; align-items: center; justify-content: center;
        padding: 2px;
    }
    .flash-close:hover { opacity: 1; }
    .flash-close svg { width: 14px; height: 14px; }
</style>

@php $hasFlash = false; @endphp
@foreach($flashTypes as $type => $cfg)
    @if(session($type))
        @php $hasFlash = true; @endphp
    @endif
@endforeach

@if($hasFlash)
<div class="flash-wrap" id="flash-container">
    @foreach($flashTypes as $type => $cfg)
        @if(session($type))
        <div class="flash flash-{{ $type }}"
             id="flash-{{ $type }}"
             role="alert"
             style="background:{{ $cfg['bg'] }};border-color:{{ $cfg['border'] }};color:{{ $cfg['color'] }}">
            <svg class="flash-icon" xmlns="http://www.w3.org/2000/svg" fill="none"
                 viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                {!! $cfg['icon'] !!}
            </svg>
            <span class="flash-msg" style="color:var(--text-primary)">{{ session($type) }}</span>
            <button class="flash-close" aria-label="Dismiss" onclick="this.closest('.flash').remove()">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                     stroke-width="2.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        @endif
    @endforeach
</div>
<script>
    // Auto-dismiss flash messages after 5 s
    (function () {
        const container = document.getElementById('flash-container');
        if (!container) return;
        setTimeout(function () {
            container.querySelectorAll('.flash').forEach(function (el) {
                el.style.transition = 'opacity 0.4s ease, transform 0.4s ease';
                el.style.opacity = '0';
                el.style.transform = 'translateY(-6px)';
                setTimeout(function () { el.remove(); }, 420);
            });
        }, 5000);
    })();
</script>
@endif
