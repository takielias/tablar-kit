@php
    // Generate a unique ID if one isn't provided
    $id = $id ?? 'select-' . uniqid();
@endphp

<select name="{{ $name }}"
        {{ $attributes->merge(['class' => 'form-select']) }}
        id="{{ $id }}"
        @if($targetDropdown!=null)
            data-child="{{$targetDropdown}}"
    @endif
>
    @if($placeholder)
        <option value="" disabled selected>{{$placeholder}}</option>
    @endif
    @php
        $optionsList = null;
        if (function_exists('options') || (isset($options) && is_callable($options))) {
            $optionsList = $options();
        } elseif (isset($options)) {
            $optionsList = $options;
        }
    @endphp

    @foreach($optionsList as $key => $option)
        <option value="{{$key}}" @selected($value == $key) >{{$option}}</option>
    @endforeach
</select>
<div class="invalid-feedback"></div>

@push('css')
    <style>
        /* Fix for Tom Select dropdown visibility in Tabler */
        .ts-dropdown {
            /* Use Tabler's surface background color for the dropdown */
            background-color: var(--tblr-bg-surface);
            /* Add a border to match Tabler's style */
            border: 1px solid var(--tblr-border-color);
            /* Add a shadow for depth */
            box-shadow: var(--tblr-box-shadow);
            /* Ensure the dropdown appears above other page content */
            z-index: 1050;
        }
        .ts-dropdown .dropdown-content,
        .ts-dropdown .ts-dropdown-content {
            /* Ensure the dropdown content itself is not transparent */
            background-color: var(--tblr-bg-surface);
        }
        .ts-dropdown .option {
            /* Set text color for options */
            color: var(--tblr-body-color);
        }
        .ts-dropdown .option.active {
            /* Style for the active/hovered option */
            background-color: var(--tblr-primary-bg-subtle);
            color: var(--tblr-primary);
        }
    </style>
@endpush

@push('js')
    <script type="module">
        document.addEventListener('DOMContentLoaded', () => {
            // Check if TomSelect is available
            if (!window.TomSelect) return;

            // Initialize Tom Select with Tabler-compatible settings
            const mainSelect = new TomSelect('#{{ $id }}', {
                copyClassesToDropdown: false,
                dropdownParent: 'body',
                controlInput: '<input>',
                create: false,
                sortField: {
                    field: "text",
                    direction: "asc"
                },
                render: {
                    item: function (data, escape) {
                        if (data.customProperties) {
                            return '<div><span class="dropdown-item-indicator">' + data.customProperties + "</span>" + escape(data.text) + "</div>";
                        }
                        return "<div>" + escape(data.text) + "</div>";
                    },
                    option: function (data, escape) {
                        if (data.customProperties) {
                            return '<div><span class="dropdown-item-indicator">' + data.customProperties + "</span>" + escape(data.text) + "</div>";
                        }
                        return "<div>" + escape(data.text) + "</div>";
                    },
                }
            });

            @if($targetDropdown!=null)
            const targetSelect = new TomSelect('#{{ $targetDropdown }}', {
                copyClassesToDropdown: false,
                dropdownParent: 'body',
                controlInput: '<input>',
                create: false,
                sortField: {
                    field: "text",
                    direction: "asc"
                },
                render: {
                    item: function (data, escape) {
                        if (data.customProperties) {
                            return '<div><span class="dropdown-item-indicator">' + data.customProperties + "</span>" + escape(data.text) + "</div>";
                        }
                        return "<div>" + escape(data.text) + "</div>";
                    },
                    option: function (data, escape) {
                        if (data.customProperties) {
                            return '<div><span class="dropdown-item-indicator">' + data.customProperties + "</span>" + escape(data.text) + "</div>";
                        }
                        return "<div>" + escape(data.text) + "</div>";
                    },
                }
            });

            mainSelect.on('change', () => {
                @if($targetDataRoute!=null)
                // Clear existing options in Tom Select
                targetSelect.clear();
                targetSelect.clearOptions();

                // Trigger optionsRemoved event for any child dropdowns
                targetSelect.trigger('optionsRemoved');
                @endif

                axios.get(`{{ route($targetDataRoute) }}`, {
                    params: {id: mainSelect.getValue()}
                })
                    .then(response => {
                        const products = response.data;

                        Object.entries(products).forEach(([id, value]) => {
                            targetSelect.addOption({
                                value: id,
                                text: value
                            });
                        });

                        targetSelect.refreshOptions();
                    })
                    .catch(error => {
                        console.error('Error fetching data:', error);
                    });
            });

            targetSelect.on('optionsRemoved', () => {
                const childId = targetSelect.input.dataset.child;
                if (childId) {
                    const childSelect = document.getElementById(childId).tomselect;
                    if (childSelect) {
                        childSelect.clear();
                        childSelect.clearOptions();
                        childSelect.trigger('optionsRemoved');
                    }
                }
            });
            @endif
        });
    </script>
@endpush
