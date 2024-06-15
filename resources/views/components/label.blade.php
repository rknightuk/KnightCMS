@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-bold text-sm mt-3 uppercase tracking-widest']) }}>
    {{ $value ?? $slot }}
</label>
