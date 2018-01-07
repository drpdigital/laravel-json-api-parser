<?php

namespace Tests\Feature;

use Illuminate\Http\Request;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Routing\Router;
use Tests\Fakes\FakeModel;
use Tests\TestCase;

class CanUseRouteResolverAsCustomerParameterResolverTest extends TestCase
{
    /** @test */
    public function it_can_resolve_using_route_model_binding()
    {
        $router = $this->app->make(Router::class);
        $router->bind('test', function ($id) {
            return new FakeModel([
                'id' => $id
            ]);
        });

        $router->group([
            'middleware' => SubstituteBindings::class
        ], function () use ($router) {
            $router->patch('/api/test/{test}', function (Request $request) {
                $collection = json_api()
                    ->resolver('test', function (FakeModel $fakeModel1) {
                        $fakeModel1->data['new'] = 'test';

                        return $fakeModel1;
                    })
                    ->parse($request->json()->all());

                return $collection->get('test')->data;
            });
        });

        $response = $this
            ->json('PATCH', 'api/test/3', [
                'data' => [
                    'type' => 'test',
                    'id' => 3,
                ]
            ]);

        $this->assertNull(
            $response->exception,
            'Request should not have thrown an exception: ' .
                ($response->exception ? $response->exception->getMessage() : '')
        );
        $response->assertJson([
            'id' => 3,
            'new' => 'test',
        ]);
    }
}
