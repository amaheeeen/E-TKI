<button 
    {{ $attributes->merge(['type' => 'button']) }}
    x-data="{ isPressed: false, isHovered: false }"
    @mousedown="isPressed = true"
    @mouseup="isPressed = false"
    @mouseleave="isPressed = false; isHovered = false"
    @mouseenter="isHovered = true"
    :class="{ 'scale-95': isPressed, 'scale-105 brightness-110': isHovered && !isPressed }"
    class="relative inline-flex items-center justify-center overflow-hidden font-medium transition-all duration-300 rounded-xl px-4 py-2 shadow-lg group backdrop-blur-md border border-white/40
    @if(($variant ?? 'default') === 'default') bg-indigo-500/80 text-white hover:bg-indigo-600/90 shadow-indigo-500/20
    @elseif(($variant ?? 'default') === 'success') bg-success-500/80 text-white hover:bg-success-600/90 shadow-success-500/20
    @elseif(($variant ?? 'default') === 'destructive') bg-danger-500/80 text-white hover:bg-danger-600/90 shadow-danger-500/20
    @endif"
>
    <!-- Liquid/Glass SVG Filter overlay -->
    <div class="absolute inset-0 z-0 opacity-20 pointer-events-none mix-blend-overlay">
        <svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%">
            <filter id="glass-distortion-{{ uniqid() }}">
                <feTurbulence type="fractalNoise" baseFrequency="0.04" numOctaves="2" result="noise" />
                <feDisplacementMap in="SourceGraphic" in2="noise" scale="5" xChannelSelector="R" yChannelSelector="G" />
            </filter>
            <rect width="100%" height="100%" filter="url(#glass-distortion-{{ uniqid() }})" />
        </svg>
    </div>

    <!-- Inner Highlight -->
    <div class="absolute inset-0 rounded-xl border border-white/30 pointer-events-none mix-blend-overlay"></div>

    <span class="relative z-10 flex items-center justify-center gap-2 drop-shadow-md">
        {{ $slot }}
    </span>
</button>
