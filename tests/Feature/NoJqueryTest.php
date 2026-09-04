<?php

namespace TakiElias\TablarKit\Tests\Feature;

use Orchestra\Testbench\TestCase;
use TakiElias\TablarKit\TablarKitServiceProvider;

class NoJqueryTest extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [TablarKitServiceProvider::class];
    }

    public function test_components_and_js_do_not_use_jquery(): void
    {
        $root = dirname(__DIR__, 2);
        $offenders = [];

        foreach (['/resources/views', '/resources/js'] as $dir) {
            $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($root.$dir));

            foreach ($files as $file) {
                if (! $file->isFile() || ! in_array($file->getExtension(), ['php', 'js'], true)) {
                    continue;
                }

                if (preg_match('/(?<![\w$])\$\(|jQuery/', file_get_contents($file->getPathname()))) {
                    $offenders[] = str_replace($root.'/', '', $file->getPathname());
                }
            }
        }

        $this->assertSame([], $offenders, 'tablar-kit must not depend on jQuery: '.implode(', ', $offenders));
    }
}
