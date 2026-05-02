---
name: tablar-kit-file-browser-development
description: Configure and operate the takielias/tablar-kit Jodit-powered file browser — POST /jodit/browse + /jodit/upload routes, FileBrowserStorage service, FileDto + Carbon cache safeguard, thumbnail config, mimetype allowlist, troubleshooting common 500/404 errors and folder-navigation behavior.
---

# Tablar Kit — File Browser

## When to use this skill

- Adding a Jodit editor with file browse/upload (`<x-jodit>` or `editor()` field).
- Debugging 500 errors at `/jodit/browse` (Carbon serialization, missing storage symlink).
- 404 on uploaded image URLs (`/storage/filebrowser/...`).
- Folder navigation appearing broken in the browser UI.
- Customizing root directory, allowed mimetypes, cache duration, thumbnails.

## Architecture

Two POST routes registered by tablar-kit (`routes/web.php`):

| Route | Name | Controller |
|---|---|---|
| `POST /jodit/browse` | `jodit.browse` | `JoditEditorController@browse` |
| `POST /jodit/upload` | `jodit.upload` | `JoditEditorController@upload` |

Both wrapped in `config('tablar-kit.middleware', ['web', 'auth'])` — auth required by default.

Backend service: `TakiElias\TablarKit\Services\FileBrowserStorage`. Sits on top of Laravel's default `Storage` disk, scoped to the `config('tablar-kit.root')` subdirectory.

Listing responses cached in the Laravel cache store as `FileDto` value objects keyed by `config('tablar-kit.cache.key')`.

## Step 1 — Pick a disk + symlink storage

The browser uses `Storage::disk()` (default disk). For images to be URL-accessible, the default must be `public`:

```env
FILESYSTEM_DISK=public
```

Symlink `public/storage` → `storage/app/public` once:

```bash
php artisan storage:link
```

**DDEV gotcha:** if `php artisan storage:link` produced an absolute symlink (`/home/taki/...`), the container can't resolve it. Recreate as relative:

```bash
rm public/storage
ln -s ../storage/app/public public/storage
```

## Step 2 — Carbon cache allowlist (Laravel 11+)

`FileDto` carries `Carbon\Carbon` properties. Laravel 11+ rejects deserializing arbitrary classes via `cache.serializable_classes`. Whitelist them:

```php
// config/cache.php
'serializable_classes' => [
    \Carbon\Carbon::class,
    \Carbon\CarbonImmutable::class,
    \Illuminate\Contracts\Database\ModelIdentifier::class,
],
```

Without this, the second `/jodit/browse` request (cache hit) throws:

```
Cannot assign __PHP_Incomplete_Class to property TakiElias\TablarKit\Dto\FileDto::$changed of type ?Carbon\Carbon
```

After setting the allowlist, clear stale cache files explicitly (`cache:clear` may target the wrong store):

```bash
rm -rf storage/framework/cache/data/*
```

## Step 3 — First browse (smoke)

Add an editor field somewhere:

```blade
<x-jodit name="content" />
```

Or via FormBuilder:

```php
$form->editor('content', 'Content')->height(400);
```

Click the file-browser icon in the toolbar. Expect:
- `POST /jodit/browse` → 200 with `{ data: { sources: [...] } }`.
- Folders + files render in the panel.
- Upload via drag/drop hits `POST /jodit/upload`.

## Configuration (verified `config/tablar-kit.php`)

```php
'middleware' => ['web', 'auth'],          // route middleware
'root' => 'filebrowser',                  // sub-path under default disk
'root_name' => 'default',                 // display name in Jodit panel
'file_size_accuracy' => 3,                // decimals for file size
'mimetypes' => [
    'images' => ['jpeg', 'jpg', 'gif', 'png', 'bmp', 'svg'],
],
'cache' => [
    'key' => 'filebrowser',
    'duration' => 3600,                   // seconds
],
'duplicate_file' => true,                 // auto-rename on upload-name collision
'jodit_broken_extension' => explode(',', env('JODIT_BROKEN_EXTENSION', 'vnd,plain,msword')),
'thumb' => [
    'dir_url' => env('APP_URL').'/assets/images/jodit/',
    'mask' => 'thumb-%s.svg',
    'unknown_extension' => 'thumb-unknown.svg',
    'exists' => explode(',', ''),
],
```

To restrict to admins only:

```php
'middleware' => ['web', 'auth', 'can:manage-files'],
```

## FileBrowserStorage service

If you need server-side file ops outside Jodit, use the service:

```php
use TakiElias\TablarKit\Services\FileBrowserStorage;

$store = new FileBrowserStorage;

$store->files('subfolder');               // string[] of file paths
$store->directories('subfolder');         // string[] of dir paths
$store->put('docs/report.pdf', $contents);
$store->copy('a.pdf', 'b.pdf');
$store->moveFile('old.pdf', 'new.pdf');
$store->makeDirectory('reports/2026');
$store->removeFile('old.pdf');
$store->removeDirectory('archived');
$store->getUrl('avatar.png');             // public URL via Storage::disk()->url()
$store->size('big.zip');                  // bytes
$store->lastModified('big.zip');          // unix ts
```

All paths are relative to the `tablar-kit.root` subdirectory.

## Recipes

### 1. Per-user subfolder

In a service provider, override config at runtime:

```php
config([
    'tablar-kit.root' => 'filebrowser/u'.auth()->id(),
    'tablar-kit.root_name' => 'My uploads',
]);
```

Caveat: cache key inherits from `config('tablar-kit.cache.key')` — append a per-user suffix to prevent cross-user listings:

```php
config(['tablar-kit.cache.key' => 'filebrowser:u'.auth()->id()]);
```

### 2. Restrict to image-only uploads

Already enforced by `mimetypes.images`. To add PDF:

```php
'mimetypes' => [
    'images' => ['jpeg', 'jpg', 'gif', 'png', 'bmp', 'svg'],
    'documents' => ['pdf'],
],
```

(Verify the controller reads multiple groups before relying on this; older versions only honor `images`.)

### 3. Custom thumbnails for unknown types

Drop SVGs at `public/assets/images/jodit/thumb-{ext}.svg`. The browser auto-resolves `thumb.dir_url` + `thumb.mask`.

### 4. Background uploads via FilePond instead of Jodit

Use the `<x-filepond>` component (handled by `FilePondController` at `/filepond` — separate from Jodit routes). See `tablar-kit-forms-development` skill for FilePond field config.

## Common pitfalls

- **`/jodit/browse` returns 500 with Carbon error** — cache allowlist missing. Apply Step 2.
- **`/storage/filebrowser/{file}` returns 404 right after upload** — `FILESYSTEM_DISK=local` writes to `storage/app/`, NOT `storage/app/public/`. Switch disk to `public` and re-upload.
- **`/storage/...` returns 404 inside DDEV but works on host** — `public/storage` symlink is absolute (`/home/...`). DDEV container resolves `/var/www/html`. Rebuild as relative (Step 1 DDEV gotcha).
- **Folder click does nothing** — Jodit FileBrowser navigates on **double-click**, not single click. Single click selects only.
- **Stale listing after upload** — cache TTL is 3600s. Either reduce `cache.duration` OR call `Cache::forget(config('tablar-kit.cache.key'))` after server-side mutation.
- **`renameIfExistsRaw` crash on extensionless folder name** — fixed in `b107734` (PHP `??` operator precedence trap). If you see this on an older commit, upgrade tablar-kit.
- **403 / 419 on `/jodit/browse`** — middleware needs `web` group for CSRF + `auth` for guard. If using API token auth, add the appropriate middleware to the array.

## Related

- Skill `tablar-kit-forms-development` — `editor()` field method that triggers the browser.
- Slash command `/laravel-boost:install-tablar-kit` — automates initial setup.
- Source: `src/Services/FileBrowserStorage.php`, `src/Http/Controllers/JoditEditorController.php`, `src/Dto/FileDto.php`, `routes/web.php`, `config/tablar-kit.php`.
