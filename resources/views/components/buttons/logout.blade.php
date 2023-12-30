<form method="POST" action="{{ $action }}">
    @csrf

    <button type="submit" {{ $attributes->merge(['class' => 'btn btn-primary']) }}>
        {{ $slot->isEmpty() ? __('Log out') : $slot }}
    </button>
</form>
