## Tablar Kit

@verbatim
takielias/tablar-kit ships Blade UI components, a FormBuilder fluent API, a Jodit-powered file browser, and table/datatable helpers. Designed to layer on top of takielias/tablar but every component works standalone in any Bootstrap 5 + Tabler-styled Laravel app.
@endverbatim

### Install

@verbatim
<code-snippet name="install" lang="bash">
composer require takielias/tablar-kit
php artisan vendor:publish --tag=tablar-kit-config
npm install && npm run build
</code-snippet>
@endverbatim

For a guided install with confirm-modal mount + JS init wiring, type `/laravel-boost:install-tablar-kit` in Claude Code (skill ships in takielias/tablar).

### Conventions

- Components register via `config('tablar-kit.components')` alias map. Aliases include `<x-input>`, `<x-select>`, `<x-tom-select>`, `<x-dependent-select>`, `<x-flat-picker>`, `<x-lite-picker>`, `<x-filepond>`, `<x-toggle>`, `<x-checkbox>`, `<x-radio>`, `<x-card>` (+ `card-header`, `card-body`, `card-footer`, `card-stamp`, `card-button`, `card-ribbon`), `<x-modal>`, `<x-modal-form>`, `<x-confirm>`, `<x-confirm-modal>`, `<x-button>`, `<x-form>`, `<x-form-button>`, `<x-tabulator>`, `<x-jodit>`. Full map in `config/tablar-kit.php`.
- Component prefix is `'prefix' => ''` by default — bare aliases. Override in published config to namespace your aliases (e.g. `'tk-'` to write `<x-tk-input>`).
- Default form-control class is `'default-class' => 'form-control'` — applied to every Input, Select, etc. unless overridden per-component.
- `<x-confirm-modal />` is a SINGLETON — drop ONCE in your master layout. Triggers (`<x-confirm>` buttons OR `window.tablarConfirm({...})`) reuse it.
- File browser root: `config('tablar-kit.root')` defaults to `'filebrowser'` (a subdir under the default Laravel disk). The browser uses Laravel's default `Storage` disk — set `FILESYSTEM_DISK=public` in `.env` to expose uploaded files via `/storage/...`.
- File browser cache: `config('tablar-kit.cache.duration')` (default 3600s) caches dir listings as `FileDto` objects.
- Confirm-modal toast: `config('tablar-kit.confirm.toast')` (bool, default true) — controls success-toast on AJAX confirm response.

### Common pitfalls

- Importing the FULL Bootstrap bundle in your Vite entry (`import { Modal, Toast } from 'bootstrap'`) double-binds the Bootstrap DataAPI and breaks every dropdown / collapse on the page. Use individual files: `bootstrap/js/dist/modal`, `bootstrap/js/dist/toast`.
- `<x-confirm>` triggers fail silently if `<x-confirm-modal />` is not mounted in the layout. Always mount once.
- `FileDto` caches `Carbon` objects. Laravel 11+ rejects deserializing arbitrary classes via the `cache.serializable_classes` allowlist — add `Carbon\Carbon`, `Carbon\CarbonImmutable`, `Illuminate\Contracts\Database\ModelIdentifier` to the allowlist OR clear the file browser cache when bumping Carbon majors.
- Uploads land at `storage/app/{root}` per the default disk. If `FILESYSTEM_DISK=local`, files are not URL-accessible — switch to `public` and run `php artisan storage:link` (relative symlink for DDEV).

### See also

- `tablar-kit-forms-development` — FormBuilder fluent API.
- `tablar-kit-confirm-modal-development` — confirm + toast usage.
- `tablar-kit-file-browser-development` — Jodit + disk + cache.
- `tablar-kit-datatable-development` — DataTable + Tabulator components.
- `tablar-kit-components-development` — full component reference (input/select/card/modal etc.).
