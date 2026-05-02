<?php

namespace TakiElias\TablarKit\Tests\Feature;

use Orchestra\Testbench\TestCase;
use TakiElias\TablarKit\TablarKitServiceProvider;

class ComposerJsonContractTest extends TestCase
{
    private const COMPOSER_PATH = __DIR__.'/../../composer.json';

    protected function getPackageProviders($app): array
    {
        return [TablarKitServiceProvider::class];
    }

    private function composer(): array
    {
        return json_decode(file_get_contents(self::COMPOSER_PATH), true, flags: JSON_THROW_ON_ERROR);
    }

    public function test_php_constraint_pins_8_3_minimum(): void
    {
        $php = $this->composer()['require']['php'] ?? null;

        $this->assertNotNull($php, 'require.php must be set.');
        $this->assertStringContainsString('^8.3', $php);
    }

    public function test_illuminate_packages_support_l11_l12_l13(): void
    {
        $require = $this->composer()['require'] ?? [];

        foreach (['illuminate/filesystem', 'illuminate/support', 'illuminate/view'] as $pkg) {
            $constraint = $require[$pkg] ?? '';
            foreach (['^11.0', '^12.0', '^13.0'] as $required) {
                $this->assertStringContainsString(
                    $required,
                    $constraint,
                    "{$pkg} must support {$required}; got '{$constraint}'."
                );
            }
        }
    }

    public function test_carbon_pinned_to_v3(): void
    {
        $constraint = $this->composer()['require']['nesbot/carbon'] ?? '';

        $this->assertStringContainsString('^3.0', $constraint, 'Carbon should target v3 (drop legacy ^2 union).');
        $this->assertStringNotContainsString('^2', $constraint, 'Drop Carbon 2 union — L11+ ships Carbon 3.');
    }

    public function test_no_loose_ge_constraints(): void
    {
        $composer = $this->composer();
        $haystack = json_encode(array_merge($composer['require'] ?? [], $composer['require-dev'] ?? []));

        $this->assertDoesNotMatchRegularExpression('/>=\s*\d/', $haystack);
    }

    public function test_phpunit_pinned_to_modern_majors(): void
    {
        $constraint = $this->composer()['require-dev']['phpunit/phpunit'] ?? '';

        $this->assertStringContainsString('^11.0', $constraint);
    }

    public function test_testbench_supports_l11_l12_l13(): void
    {
        $constraint = $this->composer()['require-dev']['orchestra/testbench'] ?? '';

        foreach (['^9.0', '^10.0', '^11.0'] as $required) {
            $this->assertStringContainsString(
                $required,
                $constraint,
                "orchestra/testbench must support {$required}; got '{$constraint}'."
            );
        }
    }

    public function test_composer_validate_succeeds(): void
    {
        // `--strict` would fail on the takielias/tablar + takielias/lab "*" sibling
        // constraints, which are deliberate for path-repo local linking.
        $output = [];
        $exit = 0;
        exec('cd '.escapeshellarg(realpath(__DIR__.'/../..')).' && composer validate 2>&1', $output, $exit);

        $this->assertSame(0, $exit, "composer validate failed:\n".implode("\n", $output));
    }
}
