<button type="button"
        data-confirm
        data-confirm-url="{{ $url }}"
        data-confirm-method="{{ $method }}"
        data-confirm-title="{{ $title }}"
        data-confirm-message="{{ $message }}"
        data-confirm-button="{{ $button }}"
        data-confirm-status="{{ $status }}"
        @if ($event) data-confirm-event="{{ $event }}" @endif
        @if ($redirect) data-confirm-redirect="{{ $redirect }}" @endif
        @unless ($reload) data-confirm-no-reload @endunless
        data-confirm-class="{{ $confirmClass }}"
        {{ $attributes->merge(['class' => 'btn btn-danger']) }}>
    {{ $slot }}
</button>
