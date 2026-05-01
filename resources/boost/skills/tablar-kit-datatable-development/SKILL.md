---
name: tablar-kit-datatable-development
description: Build server-side data tables with takielias/tablar-kit — DataTable abstract base class for column schema + data source + export, <x-tabulator> Blade component for rendering, action columns with confirm-modal integration, custom formatters and search, pagination.
---

# Tablar Kit — DataTable / Tabulator

## When to use this skill

- Building a paginated table with search + sort.
- Adding row-level actions (edit, delete with confirm).
- Custom column rendering (HTML, badges, action buttons).
- Exporting visible data to CSV / XLS / PDF.

## Architecture

Two pieces:

- `TakiElias\TablarKit\DataTable\DataTable` — abstract base. Define columns + data source + export types in a subclass constructor.
- `<x-tabulator :table="$table" />` — Blade component that takes a `DataTable` instance and renders the Tabulator-powered HTML table + JS bindings.

## Step 1 — Subclass `DataTable`

```php
namespace App\Tables;

use App\Models\Product;
use TakiElias\TablarKit\DataTable\DataTable;
use TakiElias\TablarKit\Enums\ExportType;

class ProductTable extends DataTable
{
    public function __construct()
    {
        parent::__construct();

        $this->setDataSource(Product::select('id', 'product_name', 'product_code', 'product_price'))
            ->column(name: 'id', title: 'SN')
            ->column(name: 'product_name', title: 'Name', search: true)
            ->column(name: 'product_code', title: 'Code', search: true)
            ->column(name: 'product_price', title: 'Price', search: true)
            ->column(
                name: 'action',
                title: 'Action',
                callback: fn ($item) => view('common.action', ['item' => $item])->render(),
                formatter: 'html'
            )
            ->setExportTypes([ExportType::CSV, ExportType::PDF, ExportType::XLS])
            ->paginate(10);
    }
}
```

`parent::__construct()` is required.

## Step 2 — Render in Blade

```blade
{{-- Controller passes $table = new ProductTable; --}}
<x-tabulator :table="$table" />
```

The component handles the Tabulator initialization, AJAX paging, search, and export buttons.

## DataTable public API (verified `src/DataTable/DataTable.php`)

| Method | Signature | Returns | Use |
|---|---|---|---|
| `setDataSource` | `(Builder\|Collection\|array $source)` | `static` | Eloquent query builder, Collection, or raw array |
| `fromQuery` | `(Builder $query)` | `self` | Alias for query-only source |
| `fromCollection` | `(Collection $data)` | `self` | Alias for collection source |
| `fromArray` | `(array $data)` | `self` | Alias for array source |
| `column` | `(string $name, string $title, $callback = null, string $formatter = 'plaintext', array $formatterParams = [], bool $search = false, ?string $width = '200', bool $download = true, ?BottomCalculationType $bottomCalc = null, bool $hidden = false)` | `self` | Add a column |
| `hideColumns` | `(array $columns)` | `self` | Hide named columns from rendering |
| `setExportTypes` | `(array $types)` | `static` | Pass `ExportType` enum cases |
| `paginate` | `($limit)` | `void` | Set page size — ends the chain (returns void) |
| `render` | `(string $view = 'tablar-kit::table.datatable')` | string | Render shortcut (component does this for you) |
| `getData` | `(Request $request)` | array | Pull paginated/sorted/searched rows for the AJAX response |

## `column()` arguments

- `name` (string, required) — DB field OR a synthetic key for callback-only columns (e.g. `'action'`).
- `title` (string, required) — header label.
- `callback` (callable\|null) — receives the row, returns the cell content. Use for action buttons or computed values.
- `formatter` (string, default `'plaintext'`) — Tabulator formatter: `plaintext`, `html`, `link`, `image`, `tickCross`, `traffic`, `progress`, `color`, `buttonTick`, `buttonCross`, etc. See Tabulator docs.
- `formatterParams` (array) — formatter-specific options.
- `search` (bool, default `false`) — include in column-level search.
- `width` (string\|null, default `'200'`) — column width.
- `download` (bool, default `true`) — include in exports.
- `bottomCalc` (`BottomCalculationType` enum\|null) — sum/avg/count footer for the column.
- `hidden` (bool, default `false`) — track in hidden list (not rendered, still searchable if applicable).

## Export

```php
use TakiElias\TablarKit\Enums\ExportType;

$this->setExportTypes([ExportType::CSV, ExportType::XLS, ExportType::PDF, ExportType::HTML]);
```

`ExportType` enum cases: `CSV`, `XLS`, `PDF`, `HTML`.

Component renders an export dropdown automatically. Each format pulls from current visible rows (respects search + pagination).

## Recipes

### 1. Static query-driven table

```php
class UserTable extends DataTable
{
    public function __construct()
    {
        parent::__construct();

        $this->setDataSource(User::query())
            ->column('id', 'ID')
            ->column('name', 'Name', search: true)
            ->column('email', 'Email', search: true)
            ->paginate(15);
    }
}
```

### 2. Action column with `<x-confirm>` delete

`resources/views/common/action.blade.php`:

```blade
<a href="{{ route('users.edit', $item) }}" class="btn btn-sm btn-primary">
    <i class="ti ti-edit"></i>
</a>

<x-confirm
    :url="route('users.destroy', $item)"
    method="DELETE"
    :title="'Delete user?'"
    :message="'Permanently delete '.$item->name.'?'"
    button="Delete"
    class="btn btn-sm btn-danger">
    <i class="ti ti-trash"></i>
</x-confirm>
```

In the table:

```php
->column(
    name: 'action',
    title: 'Actions',
    callback: fn ($item) => view('common.action', ['item' => $item])->render(),
    formatter: 'html',
    download: false,
    width: '120',
)
```

`formatter: 'html'` is REQUIRED — without it, Tabulator escapes the HTML and renders raw markup.

`download: false` excludes the action column from CSV/XLS/PDF exports.

The confirm-modal singleton (`<x-confirm-modal />`) must already be mounted in the layout — see `tablar-kit-confirm-modal-development`.

### 3. Computed column

```php
->column(
    name: 'total',
    title: 'Total',
    callback: fn ($order) => '$'.number_format($order->amount * $order->qty, 2),
)
```

### 4. Bottom calculation (footer sum)

```php
use TakiElias\TablarKit\Enums\BottomCalculationType;

->column('product_price', 'Price', bottomCalc: BottomCalculationType::SUM)
```

(Verify enum exists with desired cases — read `src/Enums/BottomCalculationType.php`.)

### 5. Hide internal columns from render but keep in data

```php
->column('id', 'ID')
->hideColumns(['id'])
```

OR per-column:

```php
->column('internal_uuid', 'UUID', hidden: true)
```

## Pitfalls

- **`paginate()` returns void** — must be the LAST call in the chain. Splitting `->paginate(10)->setExportTypes(...)` won't compile.
- **Action column without `formatter: 'html'`** — buttons render as escaped text.
- **N+1 in callback** — `callback: fn ($item) => $item->user->name` triggers a query per row. Use `setDataSource(Order::with('user'))` to eager-load.
- **`<x-confirm-modal />` mount missing** — confirm triggers in row actions silently fail. Mount once in master layout.
- **`getData()` is called by the component automatically** — don't invoke it manually in the controller.
- **Custom Blade view via `render($view)`** — bypass the component when you need full HTML control. The component is the recommended path.

## Configuration

No tablar-kit config keys directly target the DataTable. Tabulator JS options are baked into the component view (`resources/views/components/table/tabulator.blade.php`).

## Related

- Skill `tablar-kit-confirm-modal-development` — wire row delete confirmations.
- Skill `tablar-kit-forms-development` — FormBuilder for create/edit screens.
- Source: `src/DataTable/DataTable.php`, `src/Components/Table/Tabulator.php`, `src/Enums/ExportType.php`, `resources/views/components/table/tabulator.blade.php`.
