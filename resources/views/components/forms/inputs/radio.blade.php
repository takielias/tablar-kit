<input
    name="{{ $name }}"
    type="radio"
    id="{{ $id }}"
    @if($value)value="{{ $value }}"@endif
    {{ $checked ? 'checked' : '' }}
    {{ $attributes->merge(['class' => 'form-check-input']) }}
/>
