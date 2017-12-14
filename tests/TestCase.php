<?php

namespace Tests;

use Drp\LaravelJsonApiParser\JsonApiParserServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    protected function getPackageProviders($app)
    {
        return [JsonApiParserServiceProvider::class];
    }
}
