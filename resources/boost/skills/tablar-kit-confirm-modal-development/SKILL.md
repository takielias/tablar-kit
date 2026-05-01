---
name: tablar-kit-confirm-modal-development
description: Drop-in confirmation dialog for destructive AJAX actions in takielias/tablar-kit — singleton <x-confirm-modal /> + <x-confirm> trigger Blade component + window.tablarConfirm() Promise API + lifecycle events + toast configuration. Replaces window.confirm() with a styled Bootstrap modal that posts to a server endpoint and routes the response (onSuccess/redirect/event/reload).
---

# Tablar Kit — Confirm Modal

## When to use this skill

- Replacing `confirm()` JS popups with a styled Bootstrap modal.
- Confirming destructive actions (delete, deactivate) before AJAX POST/DELETE.
- Promise-style confirmation in Livewire/Alpine/vanilla JS.
- Wiring success toasts that survive a page reload.

## Architecture

A SINGLE shared modal lives in your master layout. Every trigger reuses it — per-call config is driven by `data-confirm-*` attributes (Blade trigger) or an options object (`window.tablarConfirm`). On confirm the plugin sends `fetch()` to the configured URL, dispatches lifecycle events, then runs ONE outcome (priority: `onSuccess > redirect > event > reload`).

## Step 1 — Mount the singleton

Drop in your master layout, ONCE, before `</body>`:

```blade
@if (class_exists(\TakiElias\TablarKit\Components\Modals\ConfirmModal::class))
    <x-confirm-modal />
@endif
```

Modal mounts with id `tablar-kit-confirm-modal`. The `class_exists` guard makes the layout safe even if tablar-kit is later removed.

The modal also emits `data-toast-enabled="0|1"` driven by `config('tablar-kit.confirm.toast', true)`.

## Step 2 — Trigger via `<x-confirm>` Blade component

```blade
<x-confirm
    :url="route('users.destroy', $user)"
    method="DELETE"
    title="Delete user?"
    :message="'Permanently delete '.$user->name.'?'"
    button="Delete"
    status="danger"
    class="btn btn-sm btn-danger">
    <i class="ti ti-trash me-1"></i> Remove
</x-confirm>
```

### Constructor props (verified `Confirm.php`)

| Prop | Type | Default | Effect |
|---|---|---|---|
| `url` | string (required) | — | Endpoint to fetch on confirm. |
| `method` | string | `'POST'` | HTTP verb. Auto-uppercased. |
| `title` | string | `'Are you sure?'` | Modal heading. |
| `message` | string | `''` | Modal body text. |
| `button` | string | `'Confirm'` | Confirm button label inside modal. |
| `status` | string | `'danger'` | Header color band — `danger\|warning\|success\|info`. |
| `event` | ?string | `null` | DOM event name to dispatch on success instead of redirect/reload. |
| `redirect` | ?string | `null` | URL to navigate to on success. |
| `reload` | bool | `true` | Reload page on success when no event/redirect/onSuccess. |
| `confirmClass` | string | `'btn btn-danger'` | Extra classes on the confirm button inside the modal. |

The Blade view emits the trigger as `<button data-confirm data-confirm-* ...>{{ $slot }}</button>`. Slot becomes the visible label of the trigger button.

## Step 3 — Programmatic API

```js
window.tablarConfirm({
    url: '/users/5',
    method: 'DELETE',
    title: 'Delete user?',
    message: 'Are you sure?',
    button: 'Delete',
    status: 'danger',
    onSuccess: (json) => { /* run on 2xx */ },
});
```

Same option keys as the Blade props. Plus `onSuccess(json)` — overrides every other outcome.

## Outcome priority on success

```
onSuccess > redirect > event > reload
```

The plugin runs the FIRST one set:

1. `onSuccess` callback — invoked with parsed JSON.
2. `redirect` URL — `window.location.assign(url)`. Persists `success` message via `sessionStorage` flash so destination page can show toast.
3. `event` — dispatches `CustomEvent` on `document` with `detail: json`.
4. `reload` (default true) — `window.location.reload()`. Persists flash for next page.

If `reload=false` AND no other outcome → only the toast fires (if `message` returned + toast enabled).

## Lifecycle events (always fire on `document`)

| Event | When | `event.detail` |
|---|---|---|
| `tablar-kit:confirm:open` | Modal opens | The opts object |
| `tablar-kit:confirm:success` | After AJAX 2xx | The parsed JSON |
| `tablar-kit:confirm:error` | AJAX 4xx/5xx or fetch rejection | Error object / response JSON |

Listen via:

```js
document.addEventListener('tablar-kit:confirm:success', (e) => {
    console.log('confirmed', e.detail);
});
```

Custom `event` opt is ALSO dispatched (in addition to `*:success`) when set.

## Server JSON contract

The server responds with JSON. The plugin reads `message`:

```json
{ "message": "User deleted." }
```

2xx → success path; non-2xx → error path. The plugin shows the message via toast (if enabled) or as in-modal alert on error.

## Toast configuration

- `config('tablar-kit.confirm.toast')` — bool, default `true`.
- Emitted to the modal as `data-toast-enabled="0|1"`.
- Toasts render in a Bootstrap toast container at id `tablar-kit-toast-container`, position bottom-end.
- For redirect/reload paths the message is persisted via `sessionStorage` flash and shown after navigation.

## Recipes

### 1. Row delete in a DataTable / list

```blade
<x-confirm
    :url="route('products.destroy', $product)"
    method="DELETE"
    title="Delete product?"
    :message="'Delete '.$product->name.'?'"
    button="Delete"
    class="btn btn-sm btn-danger">
    <i class="ti ti-trash"></i>
</x-confirm>
```

### 2. Bulk action via `window.tablarConfirm`

```js
document.getElementById('bulk-delete').addEventListener('click', () => {
    const ids = [...document.querySelectorAll('[name="ids[]"]:checked')].map(el => el.value);
    if (ids.length === 0) return;

    window.tablarConfirm({
        url: '/products/bulk-delete',
        method: 'POST',
        title: 'Delete selected?',
        message: `Delete ${ids.length} products?`,
        onSuccess: (json) => {
            location.reload();  // OR: refresh DataTable in-place
        },
    });
});
```

(Pass `ids` via the request body — extend `confirm-modal.js` or send via custom fetch wrapper. The built-in plugin sends an empty body.)

### 3. Confirm with redirect to index

```blade
<x-confirm
    :url="route('users.destroy', $user)"
    method="DELETE"
    title="Delete user?"
    :redirect="route('users.index')"
    button="Delete"
    class="btn btn-danger">
    Delete user
</x-confirm>
```

### 4. Confirm dispatches Livewire event

```blade
<x-confirm
    :url="route('orders.cancel', $order)"
    method="POST"
    title="Cancel order?"
    event="order-cancelled"
    button="Cancel order"
    class="btn btn-warning">
    Cancel
</x-confirm>
```

```js
document.addEventListener('order-cancelled', (e) => {
    Livewire.dispatch('orderCancelled', { order: e.detail });
});
```

## Common pitfalls

- **Forgetting to mount `<x-confirm-modal />`** — triggers fail silently. Plugin logs `[tablar-kit] #tablar-kit-confirm-modal not in DOM — drop <x-confirm-modal /> in your layout.` to console.
- **Importing the FULL Bootstrap bundle** elsewhere in your Vite entry (`import { Modal, Toast } from 'bootstrap'`) — double-binds DataAPI and breaks dropdowns project-wide. Use individual class files: `bootstrap/js/dist/modal`, `bootstrap/js/dist/toast`.
- **CSRF token meta missing** — non-GET methods 419. Add `<meta name="csrf-token" content="{{ csrf_token() }}">` to layout `<head>`.
- **`method="DELETE"` form submission** — the plugin uses `fetch()` so CSRF + method are sent directly; you do NOT need `@method('DELETE')` on the trigger.
- **Server returns plain text instead of JSON** — toast shows nothing. Always return `response()->json(['message' => '...'])`.
- **`reload=true` + onSuccess** — onSuccess wins, no reload. Set `reload=false` explicitly only if you need to skip the fallback when no outcome runs.

## Configuration reference

```php
// config/tablar-kit.php
'components' => [
    'confirm-modal' => \TakiElias\TablarKit\Components\Modals\ConfirmModal::class,
    'confirm'       => \TakiElias\TablarKit\Components\Modals\Confirm::class,
],
'confirm' => [
    'toast' => true,   // false → modal still works, no success toast
],
```

## Related

- `tablar-kit-components-development` — `<x-modal>`, `<x-modal-form>` for non-confirm modals.
- `tablar-kit-datatable-development` — wiring confirm into row actions.
- Slash command `/laravel-boost:install-tablar-kit` — automates mount + JS wiring.
- Source: `src/Components/Modals/Confirm.php`, `src/Components/Modals/ConfirmModal.php`, `resources/views/components/modals/confirm.blade.php`, `resources/views/components/modals/confirm-modal.blade.php`, `resources/js/plugins/confirm-modal.js`.
