<?php

namespace TakiElias\TablarKit\Tests\Feature;

use Orchestra\Testbench\TestCase;
use TakiElias\TablarKit\Enums\ExportType;
use TakiElias\TablarKit\TablarKitServiceProvider;

class ExportDepsTest extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [TablarKitServiceProvider::class];
    }

    public function test_install_command_requires_no_unpatchable_export_libraries(): void
    {
        $source = file_get_contents(dirname(__DIR__, 2).'/src/Commands/InstallTablarKit.php');

        $this->assertStringNotContainsString('jspdf', $source, 'jspdf carries a critical advisory.');
        $this->assertStringNotContainsString('xlsx', $source, 'npm xlsx is frozen at 0.18.5 and its advisories are only fixed on the sheetjs cdn.');
    }

    public function test_export_types_are_csv_and_html_only(): void
    {
        $values = array_map(fn (ExportType $t): string => $t->value, ExportType::cases());

        $this->assertSame(['csv', 'html'], $values);
    }
}
