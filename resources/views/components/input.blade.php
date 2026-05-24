@props(['name', 'label' => null, 'type' => 'text', 'value' => null])

<div class="mb-4">
    @if($label)
        <label for="{{ $name }}" class="block text-sm font-semibold text-navy mb-2">
            {{ $label }}
        </label>
    @endif
    <input
        type="{{ $type }}"
        name="{{ $name }}"
        id="{{ $name }}"
        value="{{ $value ?? old($name) }}"
        @class([
            'w-full px-4 py-3 rounded-lg border-2 transition-smooth',
            'border-slate-200 focus:border-royal focus:outline-none focus:ring-2 focus:ring-royal/20',
            'bg-white',
            'border-red-500' => $errors->has($name),
        ])
        {{ $attributes }}
    />
    @error($name)
        <span class="text-xs text-red-600 mt-1 block">{{ $message }}</span>
    @enderror
</div>