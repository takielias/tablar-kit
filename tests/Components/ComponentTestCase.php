<?php

declare(strict_types=1);

namespace TakiElias\TablarKit\Tests\Components;

use Gajus\Dindent\Exception\InvalidArgumentException;
use Gajus\Dindent\Exception\RuntimeException;
use Gajus\Dindent\Indenter;
use Orchestra\Testbench\TestCase;
use Takielias\TablarKit\TablarKitServiceProvider;
use TakiElias\TablarKit\Tests\InteractsWithViews;

abstract class ComponentTestCase extends TestCase
{
    use InteractsWithViews;

    protected function setUp(): void
    {
        parent::setUp();

        $this->artisan('view:clear');
    }

    protected function flashOld(array $input): void
    {
        session()->flashInput($input);
        $sessionStore = session()->driver();
        request()->setLaravelSession($sessionStore);
    }


    protected function getPackageProviders($app): array
    {
        return [TablarKitServiceProvider::class];
    }

    /**
     * @throws RuntimeException
     * @throws InvalidArgumentException
     */
    public function assertComponentRenders(string $expected, string $template, array $data = []): void
    {
        $indenter = new Indenter();
        $indenter->setElementType('h1', Indenter::ELEMENT_TYPE_INLINE);
        $indenter->setElementType('del', Indenter::ELEMENT_TYPE_INLINE);

        $blade = (string)$this->blade($template, $data);
        $indented = $indenter->indent($blade);
        $cleaned = str_replace(
            [' >', "\n/>", ' </div>', '> ', "\n>"],
            ['>', ' />', "\n</div>", ">\n    ", '>'],
            $indented,
        );

        $this->assertSame($expected, $cleaned);
    }
}
