<?php

namespace TakiElias\TablarKit\Tests\Feature;

use Illuminate\Support\Facades\Blade;
use Orchestra\Testbench\TestCase;
use TakiElias\TablarKit\TablarKitServiceProvider;

/**
 * Locks the Confirm trigger button: data-confirm-* attribute names
 * (the contract confirm-modal.js reads from), prop defaults, and
 * attribute merging behaviour.
 */
class ConfirmTriggerComponentTest extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [TablarKitServiceProvider::class];
    }

    public function test_emits_button_with_url_and_default_method(): void
    {
        $html = Blade::render('<x-confirm url="/products/1">Delete</x-confirm>');

        $this->assertStringContainsString('data-confirm', $html);
        $this->assertStringContainsString('data-confirm-url="/products/1"', $html);
        $this->assertStringContainsString('data-confirm-method="POST"', $html);
        $this->assertMatchesRegularExpression('/>\s*Delete\s*<\/button>/', $html);
    }

    public function test_uppercases_method(): void
    {
        $html = Blade::render('<x-confirm url="/x" method="delete">Delete</x-confirm>');

        $this->assertStringContainsString('data-confirm-method="DELETE"', $html);
    }

    public function test_default_title_button_status(): void
    {
        $html = Blade::render('<x-confirm url="/x">Go</x-confirm>');

        $this->assertStringContainsString('data-confirm-title="Are you sure?"', $html);
        $this->assertStringContainsString('data-confirm-button="Confirm"', $html);
        $this->assertStringContainsString('data-confirm-status="danger"', $html);
    }

    public function test_message_html_is_escaped(): void
    {
        $html = Blade::render('<x-confirm url="/x" message="<script>alert(1)</script>">Go</x-confirm>');

        $this->assertStringNotContainsString('<script>alert(1)</script>', $html);
        $this->assertStringContainsString('data-confirm-message="&lt;script&gt;alert(1)&lt;/script&gt;"', $html);
    }

    public function test_event_attribute_emitted_only_when_set(): void
    {
        $without = Blade::render('<x-confirm url="/x">Go</x-confirm>');
        $this->assertStringNotContainsString('data-confirm-event', $without);

        $with = Blade::render('<x-confirm url="/x" event="users:reload">Go</x-confirm>');
        $this->assertStringContainsString('data-confirm-event="users:reload"', $with);
    }

    public function test_redirect_attribute_emitted_only_when_set(): void
    {
        $without = Blade::render('<x-confirm url="/x">Go</x-confirm>');
        $this->assertStringNotContainsString('data-confirm-redirect', $without);

        $with = Blade::render('<x-confirm url="/x" redirect="/users">Go</x-confirm>');
        $this->assertStringContainsString('data-confirm-redirect="/users"', $with);
    }

    public function test_no_reload_attribute_only_when_reload_disabled(): void
    {
        $with = Blade::render('<x-confirm url="/x" :reload="false">Go</x-confirm>');
        $this->assertStringContainsString('data-confirm-no-reload', $with);

        $without = Blade::render('<x-confirm url="/x">Go</x-confirm>');
        $this->assertStringNotContainsString('data-confirm-no-reload', $without);
    }

    public function test_default_button_class_falls_through(): void
    {
        $html = Blade::render('<x-confirm url="/x">Go</x-confirm>');

        $this->assertMatchesRegularExpression('/<button[^>]*class="[^"]*\bbtn btn-danger\b/', $html);
    }

    public function test_consumer_class_merges_with_default(): void
    {
        $html = Blade::render('<x-confirm url="/x" class="btn btn-sm btn-warning">Go</x-confirm>');

        $this->assertMatchesRegularExpression('/<button[^>]*class="[^"]*btn btn-sm btn-warning/', $html);
    }

    public function test_confirm_class_propagates_to_data_attr(): void
    {
        $html = Blade::render('<x-confirm url="/x" confirm-class="btn btn-warning">Go</x-confirm>');

        $this->assertStringContainsString('data-confirm-class="btn btn-warning"', $html);
    }

    public function test_slot_renders_inside_button(): void
    {
        $html = Blade::render('<x-confirm url="/x"><i class="ti ti-trash"></i> Remove</x-confirm>');

        $this->assertStringContainsString('<i class="ti ti-trash"></i> Remove', $html);
    }
}
