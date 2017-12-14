<?php

namespace Drp\LaravelJsonApiParser;

use Drp\JsonApiParser\JsonApiParser;
use Drp\JsonApiParser\JsonApiValidator;
use Drp\JsonApiParser\ResourceResolver;
use Illuminate\Support\ServiceProvider;
use Psr\Container\ContainerInterface;

class JsonApiParserServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(ResourceResolver::class, function ($app) {
            if ($app instanceof ContainerInterface) {
                return new ResourceResolver($app);
            }

            return new ResourceResolver(new ContainerWrapper($app));
        });

        $this->app->bind(JsonApiParser::class, function ($app) {
            return new JsonApiParser($app[ResourceResolver::class]);
        });

        $this->app->bind(JsonApiValidator::class, function ($app) {
            return new JsonApiValidator($app[ResourceResolver::class]);
        });
    }
}
