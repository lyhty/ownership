<?php

namespace Lyhty\Ownership;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class OwnershipServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Validator::extend('owns', function ($attribute, $value, $parameters) {
            return (new OwnershipRule(...$parameters))->passes($attribute, $value);
        });
    }
}