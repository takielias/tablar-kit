<div class="repeater-field" data-min-items="{{ $minItems }}" data-max-items="{{ $maxItems }}">
    <div class="repeater-items" @if($sortable) data-sortable="true" @endif>
        @forelse($items as $index => $item)
            <div class="repeater-item border rounded p-3 mb-3" data-index="{{ $index }}">
                @if($sortable)
                    <div class="repeater-handle mb-2">
                        <i class="fas fa-grip-vertical text-muted"></i>
                    </div>
                @endif

                <div class="repeater-content">
                    @php
                        $fields = $callback($index, $item);
                    @endphp

                    @foreach($fields as $field)
                        <div class="mb-3">
                            @if($field->getLabel())
                                <label for="{{ $field->getId() }}" class="form-label">
                                    {{ $field->getLabel() }}
                                    @if($field->isRequired())
                                        <span class="text-danger">*</span>
                                    @endif
                                </label>
                            @endif
                            {!! $field->render($item[$field->getName()] ?? null, $globalConfig) !!}
                            @if($field->getHelp())
                                <div class="form-text">{{ $field->getHelp() }}</div>
                            @endif
                        </div>
                    @endforeach
                </div>

                @if($index >= $minItems || count($items) > $minItems)
                    <button type="button" class="btn btn-sm btn-outline-danger repeater-remove">
                        {{ $removeButtonText }}
                    </button>
                @endif
            </div>
        @empty
            @for($i = 0; $i < $minItems; $i++)
                <div class="repeater-item border rounded p-3 mb-3" data-index="{{ $i }}">
                    <div class="repeater-content">
                        @php
                            $fields = $callback($i, []);
                        @endphp

                        @foreach($fields as $field)
                            <div class="mb-3">
                                @if($field->getLabel())
                                    <label for="{{ $field->getId() }}" class="form-label">
                                        {{ $field->getLabel() }}
                                        @if($field->isRequired())
                                            <span class="text-danger">*</span>
                                        @endif
                                    </label>
                                @endif
                                {!! $field->render(null, $globalConfig) !!}
                                @if($field->getHelp())
                                    <div class="form-text">{{ $field->getHelp() }}</div>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    @if($i >= $minItems)
                        <button type="button" class="btn btn-sm btn-outline-danger repeater-remove">
                            {{ $removeButtonText }}
                        </button>
                    @endif
                </div>
            @endfor
        @endforelse
    </div>

    @if(!$maxItems || count($items ?? []) < $maxItems)
        <button type="button" class="btn btn-outline-primary repeater-add">
            {{ $addButtonText }}
        </button>
    @endif
</div>

@push('js')
    @once
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const repeaters = document.querySelectorAll('.repeater-field');

                repeaters.forEach(function (repeater) {
                    const addBtn = repeater.querySelector('.repeater-add');
                    const itemsContainer = repeater.querySelector('.repeater-items');
                    const minItems = parseInt(repeater.dataset.minItems) || 0;
                    const maxItems = parseInt(repeater.dataset.maxItems) || null;

                    // Create a template from the first item
                    const createTemplate = () => {
                        const firstItem = itemsContainer.querySelector('.repeater-item');
                        if (!firstItem) return null;

                        const template = firstItem.cloneNode(true);

                        // Clear all values in the template
                        template.querySelectorAll('input, select, textarea').forEach(input => {
                            if (input.type !== 'checkbox' && input.type !== 'radio') {
                                input.value = '';
                            } else if (input.type === 'checkbox' || input.type === 'radio') {
                                input.checked = false;
                            }
                        });

                        // Remove any existing remove button from template
                        const removeBtn = template.querySelector('.repeater-remove');
                        if (removeBtn) {
                            removeBtn.remove();
                        }

                        return template;
                    };

                    // Add item functionality
                    if (addBtn) {
                        addBtn.addEventListener('click', function () {
                            const currentItems = itemsContainer.querySelectorAll('.repeater-item').length;

                            if (maxItems && currentItems >= maxItems) {
                                return;
                            }

                            const newIndex = currentItems;
                            const template = createTemplate();

                            if (template) {
                                const newItem = template.cloneNode(true);
                                newItem.dataset.index = newIndex;

                                // Update field names and IDs
                                const inputs = newItem.querySelectorAll('input, select, textarea');
                                inputs.forEach(function (input) {
                                    const name = input.name;
                                    if (name) {
                                        input.name = name.replace(/\[\d+\]/, `[${newIndex}]`);
                                        if (input.type !== 'checkbox' && input.type !== 'radio') {
                                            input.value = '';
                                        }
                                    }

                                    if (input.id) {
                                        input.id = input.id.replace(/_\d+/, `_${newIndex}`);
                                    }
                                });

                                // Update labels
                                const labels = newItem.querySelectorAll('label');
                                labels.forEach(function (label) {
                                    if (label.getAttribute('for')) {
                                        label.setAttribute('for', label.getAttribute('for').replace(/_\d+/, `_${newIndex}`));
                                    }
                                });

                                // Add remove button if needed
                                if (newIndex >= minItems) {
                                    const removeBtn = document.createElement('button');
                                    removeBtn.type = 'button';
                                    removeBtn.className = 'btn btn-sm btn-outline-danger repeater-remove';
                                    removeBtn.innerHTML = (repeater.dataset.removeText || 'Remove');
                                    newItem.appendChild(removeBtn);
                                }

                                itemsContainer.appendChild(newItem);

                                // Hide add button if max items reached
                                if (maxItems && newIndex + 1 >= maxItems) {
                                    addBtn.style.display = 'none';
                                }
                            }
                        });
                    }

                    // Remove item functionality
                    itemsContainer.addEventListener('click', function (e) {
                        if (e.target.classList.contains('repeater-remove')) {
                            const item = e.target.closest('.repeater-item');
                            const currentItems = itemsContainer.querySelectorAll('.repeater-item').length;

                            if (currentItems > minItems) {
                                item.remove();
                                reindexItems();

                                // Show add button if it was hidden
                                if (addBtn && addBtn.style.display === 'none') {
                                    addBtn.style.display = 'inline-block';
                                }
                            }
                        }
                    });

                    function reindexItems() {
                        const items = itemsContainer.querySelectorAll('.repeater-item');
                        items.forEach(function (item, index) {
                            item.dataset.index = index;

                            const inputs = item.querySelectorAll('input, select, textarea');
                            inputs.forEach(function (input) {
                                const name = input.name;
                                if (name) {
                                    input.name = name.replace(/\[\d+\]/, `[${index}]`);
                                }

                                if (input.id) {
                                    input.id = input.id.replace(/_\d+/, `_${index}`);
                                }
                            });

                            const labels = item.querySelectorAll('label');
                            labels.forEach(function (label) {
                                if (label.getAttribute('for')) {
                                    label.setAttribute('for', label.getAttribute('for').replace(/_\d+/, `_${index}`));
                                }
                            });

                            // Update remove buttons visibility
                            const removeBtn = item.querySelector('.repeater-remove');
                            if (removeBtn) {
                                removeBtn.style.display = index < minItems ? 'none' : 'inline-block';
                            }
                        });
                    }

                    // Initialize sortable if enabled
                    if (repeater.querySelector('[data-sortable="true"]') && typeof Sortable !== 'undefined') {
                        new Sortable(itemsContainer, {
                            handle: '.repeater-handle',
                            animation: 150,
                            onEnd: function () {
                                reindexItems();
                            }
                        });
                    }
                });
            });
        </script>
    @endonce
@endpush
