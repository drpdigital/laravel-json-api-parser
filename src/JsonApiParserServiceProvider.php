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
            /** @var \Drp\JsonApiParser\ResourceResolver $resolver */
            $resolver = $app[ResourceResolver::class];
            $resolver->addCustomParameterResolver(function (\ReflectionParameter $parameter, $id, $type) {
                $resolved = app('request')->route($type);

                if (is_string($resolved) === false && $parameter->getClass()->isInstance($resolved)) {
                    return $resolved;
                }

                return null;
            });

            return new JsonApiParser($resolver);
        });

        $this->app->bind(JsonApiValidator::class, function ($app) {
            return new JsonApiValidator($app[ResourceResolver::class]);
        });
    }
}
