<?php

namespace TakiElias\TablarKit\Tests\Feature;

use Illuminate\Http\Request;
use Orchestra\Testbench\TestCase;
use TakiElias\TablarKit\DataTable\DataTable;
use TakiElias\TablarKit\TablarKitServiceProvider;

class DataTablePageCountTest extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [TablarKitServiceProvider::class];
    }

    private function pagesFor(int $rows, int $perPage): int
    {
        $table = new class($rows) extends DataTable
        {
            public function __construct(int $rows)
            {
                $this->setDataSource(collect(range(1, $rows))->map(fn ($n) => ['id' => $n]))
                    ->column(name: 'id', title: 'ID');
            }
        };

        $response = $table->getData(new Request(['limit' => $perPage]));

        return $response['total_rows'];
    }

    public function test_a_partial_last_page_is_still_counted(): void
    {
        $this->assertSame(2, $this->pagesFor(11, 10), '11 rows at 10 per page is 2 pages.');
        $this->assertSame(1, $this->pagesFor(3, 10), '3 rows at 10 per page is 1 page.');
    }

    public function test_exact_multiples_are_unchanged(): void
    {
        $this->assertSame(2, $this->pagesFor(20, 10));
        $this->assertSame(1, $this->pagesFor(10, 10));
    }
}
