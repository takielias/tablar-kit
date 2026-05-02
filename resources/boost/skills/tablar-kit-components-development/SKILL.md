---
name: tablar-kit-components-development
description: Reference for takielias/tablar-kit Blade components — input, select, tom-select, dependent-select, flat-picker, lite-picker, filepond, toggle, checkbox, radio, textarea, password, email, label, error, button, form-button, alert, card (+ header/body/footer/stamp/button/ribbon/status), modal, modal-form, jodit, logout. Constructor props, slots, attribute merging, alias config.
---

# Tablar Kit — Components

## When to use this skill

- Looking up a component prop list or slot.
- Composing UI from tablar-kit components without the FormBuilder.
- Building a custom modal that's NOT a confirm dialog.
- Customizing the alias map / prefix.

## Alias map (verified `config/tablar-kit.php`)

```php
'components' => [
    'alert'            => Alert::class,
    'button'           => Button::class,
    'card'             => Card::class,
    'card-header'      => CardHeader::class,
    'card-body'        => CardBody::class,
    'card-footer'      => CardFooter::class,
    'card-stamp'       => CardStamp::class,
    'card-button'      => CardButton::class,
    'card-ribbon'      => CardRibbon::class,
    'checkbox'         => Checkbox::class,
    'filepond'         => FilePond::class,
    'radio'            => Radio::class,
    'tabulator'        => Tabulator::class,
    'toggle'           => Toggle::class,
    'select'           => Select::class,
    'dependent-select' => DependentSelect::class,
    'tom-select'       => TomSelect::class,
    'email'            => Email::class,
    'modal'            => Modal::class,
    'modal-form'       => ModalForm::class,
    'confirm-modal'    => ConfirmModal::class,
    'confirm'          => Confirm::class,
    'error'            => Error::class,
    'lite-picker'      => LitePicker::class,
    'flat-picker'      => FlatPicker::class,
    'form'             => Form::class,
    'form-button'      => FormButton::class,
    'input'            => Input::class,
    'label'            => Label::class,
    'logout'           => Logout::class,
    'password'         => Password::class,
    'textarea'         => Textarea::class,
    'jodit'            => Jodit::class,
],
'prefix' => '',         // set 'tk-' to write <x-tk-input>
```

## Form fields

### `<x-input>`

```php
public function __construct(string $name, ?string $id = null, string $type = 'text', ?string $value = '')
```

```blade
<x-input name="title" type="text" placeholder="Product title" required class="mb-3" />
```

`old($name, $value)` is auto-applied. `id` defaults to `name`.

### `<x-email>` / `<x-password>` / `<x-textarea>`

Same shape as `<x-input>` but with type pre-set / dedicated subclass.

### `<x-select>`

```php
public function __construct(string $name, ?string $id = null, ?string $value = '', array $options = [], ?string $placeholder = '')
```

```blade
<x-select name="category_id" :options="$categories" placeholder="Choose category" />
```

`options` array: `[id => label]`.

### `<x-tom-select>`

```php
public function __construct(
    string $name,
    ?string $id = null,
    ?string $value = '',
    array $options = [],
    ?string $placeholder = null,
    bool $remoteData = false,
    ?string $itemSearchRoute = null,
    bool $create = false,
)
```

Searchable + taggable select. For AJAX search:

```blade
<x-tom-select
    name="user_id"
    :remoteData="true"
    :itemSearchRoute="route('api.users.search')"
    :create="false"
/>
```

### `<x-dependent-select>`

Cascading select. Parent change triggers AJAX → child repopulates. Use `targetDataRoute` config (read `DependentSelect.php` for full prop list).

### `<x-flat-picker>`

```php
public function __construct(
    string $name,
    ?string $id = null,
    ?string $value = '',
    string $format = 'Y-m-d H:i',
    ?string $placeholder = null,
    array $options = []
)
```

flatpickr-based date/time picker.

### `<x-lite-picker>`

litepicker-based date range picker. Read `LitePicker.php` for props.

### `<x-checkbox>` / `<x-toggle>`

```php
// Checkbox
public function __construct(string $name, ?string $id = null, bool $checked = false, ?string $value = '')

// Toggle (visual switch)
public function __construct(string $name, ?string $id = null, bool $checked = false, ?string $value = '', ?string $label = '')
```

```blade
<x-toggle name="notifications" :checked="$user->wants_notifications" label="Email me" />
```

### `<x-radio>`

Takes options array. Read `Radio.php`.

### `<x-filepond>`

FilePond-based file uploader. Pairs with takielias/laravel-filepond OR a custom server endpoint.

### `<x-form>`

```php
public function __construct(?string $action = null, string $method = 'POST', bool $hasFiles = false)
```

```blade
<x-form :action="route('users.store')" method="POST" :hasFiles="true">
    <x-input name="name" />
    <x-form-button text="Save" />
</x-form>
```

`hasFiles=true` adds `enctype="multipart/form-data"` and CSRF token + method-spoofing handled automatically.

### `<x-label>` / `<x-error>`

`<x-label>` — wraps an input with a Tabler-styled label. `<x-error>` — renders `@error('field')` block in standard styling.

## Cards

### `<x-card>`

```blade
@props(['type' => 'info'])

<x-card type="success">
    <x-slot:stamp>NEW</x-slot:stamp>
    <x-slot:ribbon>...</x-slot:ribbon>
    <x-slot:header>Card Title</x-slot:header>

    <x-card-body>
        Card content here.
    </x-card-body>

    <x-slot:footer>Card Footer</x-slot:footer>
</x-card>
```

Slot-driven. `type` colors the card (info/success/warning/danger).

Sub-components: `<x-card-header>`, `<x-card-body>`, `<x-card-footer>`, `<x-card-stamp>`, `<x-card-button>`, `<x-card-ribbon>`. Each accepts `$attributes` merge.

## Modals

### `<x-modal>` (generic)

Props (from view `@props`):

| Prop | Default | Use |
|---|---|---|
| `id` | `'modal-default'` | DOM id |
| `size` | `''` | `sm`, `lg`, `full-width` |
| `status` | `''` | Color band (`success`, `danger`, etc.) |
| `title` | `''` | Heading text |
| `scrollable` | `false` | `modal-dialog-scrollable` class |
| `centered` | `true` | Vertically centered modal |

Slots: `header` (overrides `title`), default body, `footer`.

```blade
<x-modal id="user-detail" size="lg" title="User detail" :scrollable="true">
    <p>Body content here.</p>

    <x-slot:footer>
        <button class="btn btn-link" data-bs-dismiss="modal">Close</button>
    </x-slot:footer>
</x-modal>
```

Open via Bootstrap data attrs:

```blade
<button data-bs-toggle="modal" data-bs-target="#user-detail">Open</button>
```

### `<x-modal-form>` (form-in-modal)

```php
public function __construct($id = 'modal-form', $action = '', $method = 'POST', $title = '')
```

```blade
<x-modal-form id="create-user" :action="route('users.store')" method="POST" title="New user">
    <x-input name="name" />
    <x-input name="email" type="email" />
</x-modal-form>
```

CSRF token + method spoofing wired automatically.

### `<x-confirm-modal />` + `<x-confirm>`

See `tablar-kit-confirm-modal-development` skill for the singleton confirm dialog.

## Buttons

### `<x-button>`

```php
public function __construct(?string $id = null, string $type = 'submit', ?string $value = '')
```

```blade
<x-button type="button" value="Click me" class="btn btn-primary" />
```

### `<x-form-button>`

Submit / reset button styled consistently with `<x-form>`. Read `FormButton.php` for full props.

### `<x-logout />`

Pre-built logout form-button. Renders a POST form to the logout route.

## Alerts

### `<x-alert>`

```php
public function __construct(string $type = 'alert')
```

`type`: `success`, `info`, `warning`, `danger`, etc. Slot-driven body.

```blade
<x-alert type="success">
    Saved successfully.
</x-alert>
```

For dynamic flash messages, render inside Lab's `@alert` directive (see `laravel-ajax-builder-development`).

## Editors

### `<x-jodit>`

```php
public function __construct(string $name, ?string $id = null, array $options = [])
```

```blade
<x-jodit name="content" :options="['height' => 400, 'toolbarSticky' => true]" />
```

Wires the Jodit editor with the file browser at `/jodit/browse`. See `tablar-kit-file-browser-development`.

## DataTable

### `<x-tabulator>`

See `tablar-kit-datatable-development` skill — DataTable subclass + `<x-tabulator :table="$table" />`.

## Recipes

### 1. Card wrapping a form

```blade
<x-card>
    <x-slot:header>Create user</x-slot:header>

    <x-form :action="route('users.store')" method="POST">
        <x-input name="name" placeholder="Name" required class="mb-3" />
        <x-input name="email" type="email" placeholder="Email" required class="mb-3" />
        <x-form-button text="Save" />
    </x-form>
</x-card>
```

### 2. Custom modal with form via `<x-modal-form>`

```blade
<button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#create-user">
    New user
</button>

<x-modal-form id="create-user" :action="route('users.store')" method="POST" title="New user">
    <x-input name="name" required />
    <x-input name="email" type="email" required />
</x-modal-form>
```

### 3. Tabler-styled alert + page header

```blade
<div class="page-header">
    <h2 class="page-title">Users</h2>
</div>

@if (session('status'))
    <x-alert type="success">{{ session('status') }}</x-alert>
@endif
```

(`page-header` is plain Tabler markup, not a tablar-kit component.)

## Pitfalls

- **Aliases come from config — re-publish if outdated** — if a consumer has a stale published `config/tablar-kit.php` from an older version, new aliases (e.g. `confirm-modal`, `confirm`) may be missing. Re-publish: `php artisan vendor:publish --tag=tablar-kit-config --force`.
- **Components require Vite-built JS** — `<x-tom-select>`, `<x-flat-picker>`, `<x-lite-picker>`, `<x-jodit>`, `<x-filepond>`, `<x-confirm>` all need `npm run build`. Without it, plain HTML renders without enhancement.
- **`<x-modal>` on its own does NOT auto-open** — pair with a `data-bs-toggle="modal"` trigger OR `bootstrap.Modal.getOrCreateInstance(el).show()` in JS.
- **Attribute merging** — every form component supports `$attributes->merge(['class' => '...'])`. Use `class="..."` in the tag and tablar-kit appends to its defaults.
- **`old()` value population** — Input/Select/Toggle/Checkbox apply `old($name, $value)` in the constructor. After validation redirect, the user's input is restored without extra wiring.

## Configuration reference

```php
'components' => [...],          // alias map (above)
'default-class' => 'form-control',  // applied to every form Input/Select etc.
'prefix' => '',                 // set to 'tk-' to namespace aliases
```

## Related

- All other tablar-kit skills (forms, confirm-modal, file-browser, datatable).
- Source: `src/Components/`, `resources/views/components/`, `config/tablar-kit.php`.
