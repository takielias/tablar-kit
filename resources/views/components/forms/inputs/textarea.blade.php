<textarea
    name="{{ $name }}"
    id="{{ $id }}"
    rows="{{ $rows }}"
    {{ $attributes->merge(['class' => 'form-control']) }}
>{{ old($name, $slot) }}</textarea>
