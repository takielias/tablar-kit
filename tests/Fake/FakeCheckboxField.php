<?php

namespace TakiElias\TablarKit\Tests\Fake;

use Illuminate\Contracts\View\View;
use TakiElias\TablarKit\Components\Forms\Inputs\Checkbox;
use TakiElias\TablarKit\Fields\CheckboxField;

class FakeCheckboxField extends CheckboxField
{
    protected function buildComponent(): Checkbox
    {
        $mockComponent = \Mockery::mock(Checkbox::class);

        $mockView = \Mockery::mock(View::class);
        $mockView->shouldReceive('name')->andReturn('fake-view');
        $mockView->shouldReceive('with')->andReturnSelf();
        $mockView->shouldReceive('render')->andReturn('<input type="checkbox">');

        $mockComponent->shouldReceive('render')->andReturn($mockView);
        $mockComponent->shouldReceive('data')->andReturn([]);

        return $mockComponent;
    }
}

