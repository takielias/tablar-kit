<input
    name="{{ $name }}"
    type="password"
    id="{{ $id }}"
    {{ $attributes->merge(['class' => config('tablar-kit.default-class')]) }}
/>
