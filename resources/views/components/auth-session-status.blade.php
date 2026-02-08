@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'alert alert-success mb-0']) }}>
        {{ $status }}
    </div>
@endif
