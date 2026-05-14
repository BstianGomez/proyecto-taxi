@php
    $toastMessages = collect();

    if (session('success')) {
        $toastMessages->push(['type' => 'success', 'text' => session('success')]);
    }

    if (session('error')) {
        $toastMessages->push(['type' => 'error', 'text' => session('error')]);
    }

    if (session('warning')) {
        $toastMessages->push(['type' => 'warning', 'text' => session('warning')]);
    }

    if ($errors->any()) {
        foreach ($errors->all() as $error) {
            $toastMessages->push(['type' => 'error', 'text' => $error]);
        }
    }
@endphp

@if($toastMessages->isNotEmpty())
    <style>
        .global-toast-stack {
            position: fixed;
            top: 18px;
            right: 18px;
            z-index: 20000;
            display: flex;
            flex-direction: column;
            gap: 10px;
            max-width: min(440px, calc(100vw - 24px));
        }

        .global-toast {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            border-radius: 12px;
            padding: 12px 14px;
            border: 1px solid transparent;
            box-shadow: 0 14px 30px rgba(15, 23, 42, 0.18);
            animation: globalToastIn 180ms ease-out;
            backdrop-filter: blur(4px);
        }

        .global-toast-icon {
            width: 22px;
            height: 22px;
            flex-shrink: 0;
            margin-top: 1px;
        }

        .global-toast-text {
            font-size: 14px;
            line-height: 1.45;
            font-weight: 600;
        }

        .global-toast-close {
            margin-left: auto;
            background: transparent;
            border: none;
            cursor: pointer;
            font-size: 16px;
            line-height: 1;
            opacity: .65;
            padding: 2px;
        }

        .global-toast-close:hover { opacity: 1; }

        .global-toast-success {
            background: rgba(236, 253, 245, 0.98);
            border-color: #86efac;
            color: #14532d;
        }

        .global-toast-error {
            background: rgba(254, 242, 242, 0.98);
            border-color: #fca5a5;
            color: #7f1d1d;
        }

        .global-toast-warning {
            background: rgba(255, 251, 235, 0.98);
            border-color: #fcd34d;
            color: #78350f;
        }

        @keyframes globalToastIn {
            from {
                opacity: 0;
                transform: translateY(-8px) scale(0.98);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        @media (max-width: 640px) {
            .global-toast-stack {
                left: 10px;
                right: 10px;
                top: 12px;
                max-width: none;
            }
        }
    </style>

    <div class="global-toast-stack" aria-live="polite" aria-atomic="true">
        @foreach($toastMessages as $msg)
            <div class="global-toast global-toast-{{ $msg['type'] }}" data-global-toast>
                @if($msg['type'] === 'success')
                    <svg class="global-toast-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                @elseif($msg['type'] === 'warning')
                    <svg class="global-toast-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                @else
                    <svg class="global-toast-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                @endif

                <div class="global-toast-text">{{ $msg['text'] }}</div>
                <button type="button" class="global-toast-close" aria-label="Cerrar" onclick="this.closest('[data-global-toast]').remove()">×</button>
            </div>
        @endforeach
    </div>

    <script>
        (function() {
            const toasts = document.querySelectorAll('[data-global-toast]');
            toasts.forEach((toast, index) => {
                const delay = 5200 + (index * 250);
                window.setTimeout(() => {
                    if (!toast || !toast.parentNode) return;
                    toast.style.transition = 'opacity .2s ease, transform .2s ease';
                    toast.style.opacity = '0';
                    toast.style.transform = 'translateY(-6px)';
                    window.setTimeout(() => toast.remove(), 210);
                }, delay);
            });
        })();
    </script>
@endif
