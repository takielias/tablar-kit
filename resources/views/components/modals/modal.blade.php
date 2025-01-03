@props([
    'id' => 'modal-default',
    'size' => '',  // sm, lg, full-width
    'status' => '', // success, danger
    'title' => '',
    'scrollable' => false,
    'centered' => true,
])

<div {{ $attributes->merge(['class' => 'modal modal-blur fade']) }}
     id="{{ $id }}"
     tabindex="-1"
     role="dialog"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-{{ $centered ? 'centered' : '' }}
                {{ $scrollable ? 'modal-dialog-scrollable' : '' }}
                {{ $size ? 'modal-'.$size : '' }}"
         role="document">
        <div class="modal-content">
            @if($status)
                <div class="modal-status bg-{{ $status }}"></div>
            @endif

            @if($title || isset($header))
                <div class="modal-header">
                    @if(isset($header))
                        {{ $header }}
                    @else
                        <h5 class="modal-title">{{ $title }}</h5>
                    @endif
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            @endif

            <div class="modal-body" id="modal-container">
                {{ $slot }}
            </div>

            @isset($footer)
                <div class="modal-footer">
                    {{ $footer }}
                </div>
            @endisset
        </div>
    </div>
</div>
