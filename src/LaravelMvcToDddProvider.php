<?php

namespace Cbaconnier\LaravelMvcToDdd;

use Illuminate\Support\ServiceProvider;


class LaravelMvcToDddProvider extends ServiceProvider
{

    public function boot()
    {
        if ($this->app->runningInConsole()) {
                $this->commands([
                    InstallCommand::class
                ]);
        }

    }

    public function register()
    {

    }

}