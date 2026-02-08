@props(['value'])

<label {{ $attributes->merge(['class' => 'form-label fw-semibold text-muted']) }}>
    {{ $value ?? $slot }}
</label>
