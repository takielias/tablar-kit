<input
    name="{{ $name }}"
    type="{{ $type }}"
    id="{{ $id }}"
    @if($value)value="{{ $value }}"@endif
    {{ $attributes->merge(['class' => config('tablar-kit.default-class')]) }}
/>
