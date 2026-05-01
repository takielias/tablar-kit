## Tablar Kit

@verbatim
takielias/tablar-kit — Blade UI components, FormBuilder fluent API, Jodit file browser, DataTable. Layers on takielias/tablar; works standalone in any Bootstrap 5 + Tabler app.
@endverbatim

### Install

@verbatim
<code-snippet name="install" lang="bash">
composer require takielias/tablar-kit
php artisan vendor:publish --tag=tablar-kit-config
npm install && npm run build
</code-snippet>
@endverbatim

Guided install: `/laravel-boost:install-tablar-kit`.

### Conventions

- Components alias map at `config('tablar-kit.components')`. Includes `<x-input>`, `<x-select>`, `<x-tom-select>`, `<x-card>` family, `<x-modal>`, `<x-modal-form>`, `<x-confirm>`, `<x-confirm-modal>`, `<x-tabulator>`, `<x-jodit>`, plus form-control fields. Override prefix via `'prefix'` key.
- `<x-confirm-modal />` is a SINGLETON — drop ONCE in master layout.
- File browser uses Laravel's default `Storage` disk under `config('tablar-kit.root')` ('filebrowser').

### See also

- `tablar-kit-forms-development`, `tablar-kit-confirm-modal-development`, `tablar-kit-file-browser-development`, `tablar-kit-datatable-development`, `tablar-kit-components-development`.
