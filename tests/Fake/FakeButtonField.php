<?php

namespace TakiElias\TablarKit\Tests\Fake;

use Illuminate\Contracts\View\View;
use Takielias\TablarKit\Components\Buttons\Button;
use Takielias\TablarKit\Fields\ButtonField;

class FakeButtonField extends ButtonField
{
    protected function buildComponent(): Button
    {
        $mockComponent = \Mockery::mock(Button::class);

        $mockView = \Mockery::mock(View::class);
        $mockView->shouldReceive('name')->andReturn('fake-view');
        $mockView->shouldReceive('with')->andReturnSelf();
        $mockView->shouldReceive('render')->andReturn('<button type="submit">Save</button>');

        $mockComponent->shouldReceive('render')->andReturn($mockView);
        $mockComponent->shouldReceive('data')->andReturn([]);

        return $mockComponent;
    }
}

