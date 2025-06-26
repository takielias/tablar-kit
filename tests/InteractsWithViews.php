<?php

namespace TakiElias\TablarKit\Tests;


use Illuminate\Support\Facades\View as ViewFacade;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Str;
use Illuminate\Support\ViewErrorBag;
use Illuminate\View\View;

trait InteractsWithViews
{

    protected function view(string $view, $data = [])
    {
        return new TestView(view($view, $data));
    }

    protected function blade(string $template, $data = [])
    {
        $tempDirectory = sys_get_temp_dir();

        if (!in_array($tempDirectory, ViewFacade::getFinder()->getPaths())) {
            ViewFacade::addLocation(sys_get_temp_dir());
        }

        $tempFile = tempnam($tempDirectory, 'laravel-blade') . '.blade.php';

        file_put_contents($tempFile, $template);

        return new TestView(view(Str::before(basename($tempFile), '.blade.php'), $data));
    }

    protected function component(string $componentClass, $data = [])
    {
        $component = $this->app->make($componentClass, $data);

        $view = $component->resolveView();

        return $view instanceof View
            ? new TestView($view->with($component->data()))
            : new TestView(view($view, $component->data()));
    }

    protected function withViewErrors(array $errors, $key = 'default')
    {
        ViewFacade::share('errors', (new ViewErrorBag())->put($key, new MessageBag($errors)));
    }
}
