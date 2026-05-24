@props(['variant' => 'primary', 'type' => 'button'])

<button type="{{ $type }}"
        @class([
            'px-6 py-3 rounded-lg font-semibold transition-smooth hover-lift',
            'gradient-gold text-white shadow-lg-custom' => $variant === 'primary',
            'bg-slate-200 text-slate-800 hover:bg-slate-300' => $variant === 'secondary',
            'bg-red-500 text-white hover:bg-red-600' => $variant === 'danger',
        ])
        {{ $attributes }}>
    {{ $slot }}
</button>