<?php

namespace TakiElias\TablarKit\Tests\Feature;

use Illuminate\Support\Facades\Blade;
use Orchestra\Testbench\TestCase;
use TakiElias\TablarKit\TablarKitServiceProvider;

/**
 * Locks the singleton ConfirmModal mount: stable element ids that
 * confirm-modal.js targets, default `danger` status bar, and the
 * accessible Cancel + Confirm footer buttons.
 */
class ConfirmModalComponentTest extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [TablarKitServiceProvider::class];
    }

    private function render(): string
    {
        return Blade::render('<x-confirm-modal />');
    }

    public function test_renders_modal_root_with_stable_id(): void
    {
        $this->assertStringContainsString('id="tablar-kit-confirm-modal"', $this->render());
    }

    public function test_renders_modal_blur_and_centered_dialog(): void
    {
        $html = $this->render();

        $this->assertStringContainsString('modal modal-blur fade', $html);
        $this->assertStringContainsString('modal-dialog modal-sm modal-dialog-centered', $html);
    }

    public function test_exposes_targeted_inner_ids_for_js(): void
    {
        $html = $this->render();

        foreach ([
            'tablar-kit-confirm-modal-status',
            'tablar-kit-confirm-modal-title',
            'tablar-kit-confirm-modal-message',
            'tablar-kit-confirm-modal-button',
            'tablar-kit-confirm-modal-alert',
        ] as $id) {
            $this->assertStringContainsString('id="'.$id.'"', $html, "Missing #{$id} inside confirm modal.");
        }
    }

    public function test_default_status_bar_is_danger(): void
    {
        $this->assertStringContainsString('modal-status bg-danger', $this->render());
    }

    public function test_alert_starts_hidden(): void
    {
        $this->assertMatchesRegularExpression(
            '/<div class="alert alert-danger d-none[^"]*"\s+id="tablar-kit-confirm-modal-alert"/',
            $this->render(),
        );
    }

    public function test_cancel_button_dismisses_via_bootstrap(): void
    {
        $html = $this->render();

        $this->assertMatchesRegularExpression(
            '/<button[^>]*data-bs-dismiss="modal"[^>]*>\s*[^<]*Cancel/',
            $html,
        );
    }

    public function test_confirm_button_starts_as_btn_danger(): void
    {
        $this->assertMatchesRegularExpression(
            '/<button[^>]*class="btn btn-danger"[^>]*id="tablar-kit-confirm-modal-button"/',
            $this->render(),
        );
    }
}
