<?php

namespace TakiElias\TablarKit\Tests\Feature;

use Orchestra\Testbench\TestCase;
use TakiElias\TablarKit\Enums\ExportType;
use TakiElias\TablarKit\TablarKitServiceProvider;

class NoJspdfTest extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [TablarKitServiceProvider::class];
    }

    public function test_install_command_does_not_require_jspdf(): void
    {
        $source = file_get_contents(dirname(__DIR__, 2).'/src/Commands/InstallTablarKit.php');

        $this->assertStringNotContainsString('jspdf', $source, 'jspdf carries a critical advisory and no component needs it.');
    }

    public function test_export_types_do_not_offer_pdf(): void
    {
        $values = array_map(fn (ExportType $t): string => $t->value, ExportType::cases());

        $this->assertNotContains('pdf', $values);
        $this->assertSame(['csv', 'xls', 'html'], $values);
    }
}
