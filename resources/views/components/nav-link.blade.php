@props(['href' => '#', 'active' => false])

<a href="{{ $href }}"
   class="flex items-center gap-3 px-4 py-3 rounded-lg font-medium transition-smooth {{ $active ? 'bg-gradient-gold text-white' : 'text-slate-700 hover:bg-slate-100' }}">
    {{ $slot }}
</a>