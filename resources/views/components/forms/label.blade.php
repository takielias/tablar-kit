<label for="{{ $for }}"
    {{ $attributes->merge(['class' => 'form-label']) }}
>
    {{ $slot->isNotEmpty() ? $slot : $fallback() }}
</label>
