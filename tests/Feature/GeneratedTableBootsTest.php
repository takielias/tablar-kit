<?php

namespace TakiElias\TablarKit\Tests\Feature;

use Illuminate\Http\Request;
use Orchestra\Testbench\TestCase;
use TakiElias\TablarKit\DataTable\DataTable;
use TakiElias\TablarKit\TablarKitServiceProvider;

class GeneratedTableBootsTest extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [TablarKitServiceProvider::class];
    }

    public function test_a_table_without_columns_still_returns_data(): void
    {
        $table = new class extends DataTable
        {
            public function __construct()
            {
                $this->setDataSource(collect([['id' => 1], ['id' => 2]]));
            }
        };

        $response = $table->getData(new Request);

        $this->assertSame(2, count($response['data']));
    }

    public function test_the_stub_calls_the_parent_constructor(): void
    {
        $stub = file_get_contents(__DIR__.'/../../src/Commands/stubs/tablar.table.stub');

        $this->assertStringContainsString('parent::__construct();', $stub);
    }
}
