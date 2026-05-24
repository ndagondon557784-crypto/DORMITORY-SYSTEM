@props(['name', 'label' => null, 'options' => [], 'value' => null])

<div class="mb-4">
    @if($label)
        <label for="{{ $name }}" class="block text-sm font-semibold text-navy mb-2">
            {{ $label }}
        </label>
    @endif
    <select
        name="{{ $name }}"
        id="{{ $name }}"
        @class([
            'w-full px-4 py-3 rounded-lg border-2 transition-smooth',
            'border-slate-200 focus:border-royal focus:outline-none focus:ring-2 focus:ring-royal/20',
            'bg-white',
            'border-red-500' => $errors->has($name),
        ])
        {{ $attributes }}
    >
        <option value="">{{ __('Select an option') }}</option>
        @foreach($options as $optionValue => $optionLabel)
            <option value="{{ $optionValue }}" @selected(old($name) ?? $value === $optionValue)>
                {{ $optionLabel }}
            </option>
        @endforeach
    </select>
    @error($name)
        <span class="text-xs text-red-600 mt-1 block">{{ $message }}</span>
    @enderror
</div>