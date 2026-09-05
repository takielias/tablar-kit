<?php

namespace TakiElias\TablarKit\Tests\Feature;

use Illuminate\Support\Facades\File;
use Orchestra\Testbench\TestCase;
use TakiElias\TablarKit\TablarKitServiceProvider;

class MakeTableStubTest extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [TablarKitServiceProvider::class];
    }

    protected function tearDown(): void
    {
        File::deleteDirectory(app_path('Tables'));

        parent::tearDown();
    }

    private function generate(string $name): string
    {
        $this->artisan('make:table', ['name' => $name])->assertSuccessful();

        return File::get(app_path("Tables/{$name}.php"));
    }

    public function test_a_singular_name_does_not_collide_with_the_model_import(): void
    {
        $source = $this->generate('Product');

        $this->assertMatchesRegularExpression('/use [\\\\A-Za-z]+\\\\Product as ProductModel;/', $source);
        $this->assertStringContainsString('ProductModel::query()', $source);
        $this->assertDoesNotMatchRegularExpression('/use [\\\\A-Za-z]+\\\\Product;/', $source);
    }

    public function test_a_plural_name_imports_the_model_directly(): void
    {
        $source = $this->generate('Products');

        $this->assertMatchesRegularExpression('/use [\\\\A-Za-z]+\\\\Product;/', $source);
        $this->assertStringContainsString('Product::query()', $source);
    }

    public function test_the_generated_class_is_valid_php(): void
    {
        foreach (['Product', 'Products'] as $name) {
            $this->generate($name);

            $path = app_path("Tables/{$name}.php");
            exec('php -l '.escapeshellarg($path).' 2>&1', $output, $exit);

            $this->assertSame(0, $exit, "Generated {$name}.php does not parse: ".implode("\n", $output));
        }
    }
}
