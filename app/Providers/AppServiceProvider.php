<?php

namespace App\Providers;

use Illuminate\Support\Str;
use Illuminate\Support\Stringable;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Str::macro('markdownWithLineBreaks', function ($value) {
            return Str::of($value)
                ->replaceMatches('/\*\*(\s*)(.*?)(\s*)\*\*/', '$1**$2**$3')
                ->replaceMatches('/_(\s*)(.*?)(\s*)_/', '$1_$2_$3')
                ->replaceMatches('/~~(\s*)(.*?)(\s*)~~/', '$1~~$2~~$3')
                ->markdown([
                    'renderer' => [
                        'soft_break' => '<br />',
                    ],
                ])
                ->remove('**');
        });

        Stringable::macro('markdownWithLineBreaks', function () {
            return Str::markdownWithLineBreaks($this->value);
        });
    }
}
