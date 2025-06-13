<div class="mb-3 text-end">
    @if($action)
        <a href="{{ $action }}"
           {!! $attributes !!}
           class="btn {{ $config['class'] ?? 'btn-primary' }}">
            {{ $text }}
        </a>
    @else
        <button type="{{ $config['type'] ?? 'submit' }}"
                {!! $attributes !!}
                class="btn {{ $config['class'] ?? 'btn-primary' }}">
            {{ $text }}
        </button>
    @endif
</div>
