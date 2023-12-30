<input
    name="{{ $name }}"
    type="email"
    id="{{ $id }}"
    @if($value)value="{{ $value }}"@endif
    {{ $attributes->merge(['class' => config('tablar-kit.default-class')]) }}
/>
