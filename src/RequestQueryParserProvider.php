<?php

namespace LumenApiQueryParser;

use Illuminate\Support\ServiceProvider;

class RequestQueryParserProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(RequestQueryParserInterface::class, function () {
            return new RequestQueryParser();
        });
    }
}
