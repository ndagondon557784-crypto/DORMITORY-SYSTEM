<div @class([
    'glass-effect rounded-xl p-6 shadow-lg-custom hover-lift',
    $attributes->get('class'),
])>
    {{ $slot }}
</div>