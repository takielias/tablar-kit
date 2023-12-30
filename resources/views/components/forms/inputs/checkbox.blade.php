<input
    name="{{ $name }}"
    type="checkbox"
    id="{{ $id }}"
    @if($value)value="{{ $value }}"@endif
    {{ $checked ? 'checked' : '' }}
    {{ $attributes->merge(['class' => 'form-check-input']) }}
/>
