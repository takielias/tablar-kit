---
name: tablar-kit-forms-development
description: Build forms with takielias/tablar-kit FormBuilder fluent API — full method catalog (input, email, password, number, textarea, select, dependentSelect, tomSelect, flatPicker, litePicker, filepond, editor, repeater, hidden, file, toggle, checkbox, radio), layout helpers (tab, section, row, column, card), AbstractForm class pattern, conditional fields (if/unless/when), array-driven forms via fromArray/fromJson.
---

# Tablar Kit — FormBuilder

## When to use this skill

- Building a form with FormBuilder fluent API.
- Picking the right field method (date pickers, file uploads, repeaters).
- Composing layouts (tabs, sections, rows, columns, cards).
- Building dependent dropdowns + AJAX-fed selects.
- Driving forms from declarative array/JSON config.

## Two construction patterns

### 1. Inline builder (fluent)

```php
use TakiElias\TablarKit\Builder\FormBuilder;

$form = FormBuilder::create()
    ->action(route('products.store'))
    ->method('POST')
    ->multipart()
    ->input('title', 'Product title')->required()
    ->select('category_id', $categories, 'Category')->required()
    ->editor('description', 'Description')->height(400)
    ->filepond('image', 'Image')->acceptedFileTypes(['image/*'])->maxFileSize('5MB');

echo $form->render();
```

### 2. AbstractForm class (recommended for reuse)

```php
namespace App\Forms;

use TakiElias\TablarKit\Builder\AbstractForm;
use TakiElias\TablarKit\Builder\FormBuilder;

class ProductForm extends AbstractForm
{
    protected function configure(FormBuilder $form): void
    {
        $form->action(route('products.store'))->method('POST')->multipart();
    }

    protected function fields(): array
    {
        return [
            $this->form->input('title', 'Title')->required(),
            $this->form->select('category_id', $this->data['categories'] ?? [], 'Category')->required(),
            $this->form->editor('description', 'Description')->height(400),
            $this->form->filepond('image', 'Image')->acceptedFileTypes(['image/*']),
        ];
    }
}

// Controller
$form = (new ProductForm)->setData(['categories' => Category::pluck('name', 'id')->toArray()]);
return view('products.create', compact('form'));
```

## Field method catalog

Every field method on `FormBuilder` (verified against `src/Builder/FormBuilder.php`):

### Text inputs

| Method | Signature | Returns | Notes |
|---|---|---|---|
| `input` | `(string $name, string $label = '', array $config = [])` | `InputField` | Generic text input. Use `->type('text'\|'email'\|...)` to override. |
| `email` | same | `InputField` | type=email |
| `password` | same | `PasswordField` | |
| `number` | same | `InputField` | type=number; pair with `->min()->max()->step()` |
| `url` | same | `InputField` | type=url |
| `tel` | same | `InputField` | type=tel |
| `date` | same | `InputField` | Native HTML date; for richer UX use `flatPicker` |
| `time` | same | `InputField` | |
| `datetime` | same | `InputField` | |
| `color` | same | `InputField` | |
| `range` | same | `InputField` | |
| `textarea` | `(string $name, string $label = '', array $config = [])` | `TextareaField` | Pair with `->rows()->cols()` |
| `hidden` | `(string $name, $value = '', array $config = [])` | `HiddenField` | |

### Selects

| Method | Signature | Returns |
|---|---|---|
| `select` | `(string $name, array $options = [], string $label = '', array $config = [])` | `SelectField` |
| `tomSelect` | `(string $name, string $label = '', array $config = [])` | `TomSelectField` |
| `dependentSelect` | `(string $name, string $targetDropdown = '', array $config = [])` | `DependentSelectField` |

`tomSelect` enables search + tagging. For AJAX search, set options via `->itemSearchRoute(route('api.users.search'))`.

`dependentSelect` chains to a parent: parent change triggers AJAX → child repopulates. Parent named via `targetDropdown` arg; AJAX endpoint via `->targetDataRoute(route('api.cities', ['state' => ':state_id']))`.

### Date / time pickers

| Method | Returns | Notes |
|---|---|---|
| `flatPicker` | `FlatPickerField` | flatpickr — `->enableTime()->dateFormat('Y-m-d H:i')->singleMode()` |
| `litePicker` | `LitePickerField` | litepicker, range-friendly — `->singleMode(false)` for range |

### File uploads

| Method | Returns |
|---|---|
| `file` | `FileField` (native input type=file) |
| `filepond` | `FilepondField` (FilePond JS — `->allowMultiple()->maxFileSize('10MB')->acceptedFileTypes(['image/*'])->imageEditor()`) |

### Editor

| Method | Returns |
|---|---|
| `editor` | `EditorField` (Jodit by default — `->height(400)->toolbar(['bold','italic'])`) |

### Choice fields

| Method | Returns |
|---|---|
| `checkbox` | `CheckboxField` |
| `toggle` | `ToggleField` |
| `radio` | `RadioField` (takes `array $options` second arg) |

### Buttons

| Method | Returns |
|---|---|
| `button` | `ButtonField` (plain button) |
| `formButton` | `FormButtonField` (submit/reset) |

### Repeater

```php
$form->repeater('items', function (FormBuilder $row) {
    $row->input('name', 'Name')->required();
    $row->number('qty', 'Quantity')->min(1);
});
```

Returns `RepeaterField`. The callback receives a fresh `FormBuilder` instance representing the row schema. Repeater UI lets users add/remove rows client-side.

## Layout helpers (return `self` — chain freely)

| Method | Signature | Use |
|---|---|---|
| `tab` | `(string $title, callable $callback, array $config = [])` | Tabbed sections; multiple tabs render as Bootstrap nav-tabs |
| `section` | `(string $title, callable $callback, array $config = [])` | Collapsible / titled section |
| `row` | `(callable $callback, array $config = [])` | Bootstrap row |
| `column` | `(int $width, callable $callback, array $config = [])` | Bootstrap col-`{$width}` |
| `card` | `(string $title = '', array $config = [])` | Returns `CardBuilder`; chain `->fields(callable)` to populate |

Each callback receives the `FormBuilder` so you keep chaining inside.

```php
$form
    ->tab('Profile', function (FormBuilder $f) {
        $f->row(function (FormBuilder $r) {
            $r->column(6, fn(FormBuilder $c) => $c->input('first_name'));
            $r->column(6, fn(FormBuilder $c) => $c->input('last_name'));
        });
    })
    ->tab('Settings', function (FormBuilder $f) {
        $f->toggle('notifications', 'Email notifications');
    });
```

## Conditional rendering

| Method | Signature | Behaviour |
|---|---|---|
| `if` | `(bool $condition, callable $callback)` | Run callback only if true |
| `unless` | `(bool $condition, callable $callback)` | Run callback only if false |
| `when` | `(string $field, $value, callable $callback)` | Show wrapped fields client-side when `field === value` |

```php
$form->if(auth()->user()?->is_admin, function (FormBuilder $f) {
    $f->select('role', Role::pluck('name', 'id')->toArray(), 'Role');
});

$form->when('type', 'subscription', function (FormBuilder $f) {
    $f->number('billing_cycle', 'Billing cycle (months)');
});
```

## Array / JSON declarative

```php
$form->fromArray([
    'action' => '/products',
    'method' => 'POST',
    'fields' => [
        ['type' => 'input', 'name' => 'title', 'label' => 'Title', 'required' => true],
        ['type' => 'select', 'name' => 'category_id', 'options' => $categories, 'label' => 'Category'],
        ['type' => 'editor', 'name' => 'description', 'height' => 400],
    ],
]);
```

`fromJson(string $json)` accepts the same shape as JSON. Useful for storing form schemas in DB.

## Builder configuration setters

Chainable on `FormBuilder` — set form-level state:

- `action(string)` — form action URL.
- `method(string)` — `GET|POST|PUT|PATCH|DELETE`.
- `multipart(bool $multipart = true)` — enctype=multipart/form-data (required for file uploads).
- `attributes(array)` — extra HTML attrs.
- `data(array)` — model data for value population.
- `theme(string)` — alternate Blade theme.
- `config(array)` — package config overrides.
- `enableCard()` — wrap form in `<x-card>`.
- `rules(array)` — Laravel validation rules.

## Field-level setters (chainable on Field returned by each method)

`required`, `placeholder`, `addClass`, `id`, `value`, `help`, `min`, `max`, `step`, `rows`, `cols`, `height`, `toolbar`, `acceptedFileTypes`, `imageEditor`, `maxFileSize`, `allowMultiple`, `options`, `format`, `dateFormat`, `enableTime`, `singleMode`, `checked`, `confirmation`, `remoteData`, `itemSearchRoute`, `targetDataRoute`, `type`.

## Recipes

### 1. Contact form (text + email + textarea)

```php
$form = FormBuilder::create()
    ->action('/contact')->method('POST')
    ->input('name', 'Your name')->required()
    ->email('email', 'Email')->required()
    ->textarea('message', 'Message')->required()->rows(5)
    ->formButton('Send', 'submit');
```

### 2. Date range with litepicker

```php
$form->litePicker('window', 'Booking window')->singleMode(false)->dateFormat('Y-m-d');
```

### 3. Country → State → City cascade

```php
$form
    ->select('country_id', $countries, 'Country')
    ->dependentSelect('state_id', 'country_id')->targetDataRoute(route('api.states'))
    ->dependentSelect('city_id', 'state_id')->targetDataRoute(route('api.cities'));
```

API endpoints return JSON `{ data: [{ id, name }, ...] }`.

### 4. FilePond image upload + edit

```php
$form->filepond('avatar', 'Avatar')
    ->acceptedFileTypes(['image/*'])
    ->maxFileSize('5MB')
    ->imageEditor();
```

Server side requires takielias/laravel-filepond OR a custom upload route compatible with FilePond's chunk protocol.

### 5. Tabbed form (Profile / Settings)

```php
$form
    ->tab('Profile', function (FormBuilder $f) {
        $f->input('name', 'Name')->required();
        $f->email('email', 'Email')->required();
    })
    ->tab('Password', function (FormBuilder $f) {
        $f->password('current_password', 'Current password');
        $f->password('password', 'New password');
        $f->password('password_confirmation', 'Confirm');
    });
```

### 6. AbstractForm with model data binding

```php
class ProductEditForm extends AbstractForm
{
    protected function configure(FormBuilder $form): void
    {
        $form->action(route('products.update', $this->data['product']))->method('PUT')->multipart();
    }
    protected function fields(): array
    {
        return [
            $this->form->input('title')->value($this->data['product']->title)->required(),
            $this->form->editor('description')->value($this->data['product']->description),
        ];
    }
}
```

## Pitfalls

- **`select(name, options, label, config)` arg order** — `options` is SECOND, `label` THIRD. Easy to swap.
- **`dependentSelect`** requires a server endpoint returning `{data: [...]}`; without it the child stays empty.
- **`filepond` chunk uploads** need a server endpoint that handles FilePond's POST + DELETE protocol. Use takielias/laravel-filepond OR write one.
- **`fromArray` schema** — undocumented keys are silently ignored. Misnamed `'type'` (e.g. `'text'` instead of `'input'`) yields blank field.
- **Repeater rows after submit** — server receives flat `items[0][name]`, `items[0][qty]`, etc. Validate with `'items.*.name' => 'required'`.
- **Editor (Jodit) uses the file browser** — Jodit's "Upload from server" button hits `/jodit/browse`. Configure `tablar-kit.root` + storage disk per `tablar-kit-file-browser-development` skill.
- **`->multipart()` required for file fields** — without it, `$_FILES` will be empty server-side.

## Configuration

`config('tablar-kit.default-class')` — applied to every field's `class` attr (default `'form-control'`).
`config('tablar-kit.prefix')` — prefix for component aliases.
Field-specific options (filepond, jodit) live under their own keys; check published `config/tablar-kit.php` after install.

## Related

- `tablar-kit-components-development` — `<x-input>`, `<x-select>` standalone components when you don't need the full builder.
- `tablar-kit-file-browser-development` — Jodit + uploads.
- `tablar-kit-confirm-modal-development` — confirm dialog on submit / delete.
- Source: `src/Builder/FormBuilder.php`, `src/Builder/AbstractForm.php`, `src/Fields/`.
