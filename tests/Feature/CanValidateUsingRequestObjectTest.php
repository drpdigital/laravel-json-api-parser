<?php

namespace Tests\Feature;

use Drp\LaravelJsonApiParser\Concerns\ValidatesJsonApi;
use Drp\LaravelJsonApiParser\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;
use Illuminate\Routing\Router;
use Tests\Fakes\FakeValidator;
use Tests\TestCase;

class CanValidateUsingRequestObjectTest extends TestCase
{
    /** @test */
    public function can_validate_using_request_object()
    {
        $router = $this->app->make(Router::class);

        $router->post('/api/test', function (TestRequest $request) {
            return [
                'success' => false
            ];
        });

        $this
            ->json('post', 'api/test', [
                'data' => [
                    'type' => 'test',
                    'id' => 3,
                    'attributes' => [
                        'age' => 14
                    ],
                    'relationships' => [
                        'bar' => [
                            'data' => [
                                'id' => 2,
                                'type' => 'bar'
                            ]
                        ],
                        'foo' => [
                            'data' => [
                                'id' => 4,
                                'type' => 'foo'
                            ]
                        ]
                    ]
                ]
            ])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertExactJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'bar_2' => [
                        'test' => [
                            'The test field is required.'
                        ]
                    ],
                    'foo_4' => [
                        'test' => [
                            'Testing Message'
                        ]
                    ],
                    'test_3' => [
                        'name' => [
                            'The name field is required.'
                        ],
                        'age' => [
                            'You must be 18+'
                        ]
                    ],
                ]
            ]);
    }

    /** @test */
    public function request_will_run_presence_checkers()
    {
        $router = $this->app->make(Router::class);

        $router->post('/api/test', function (TestRequestWithPresenceChecker $request) {
            return [
                'success' => false
            ];
        });

        $this
            ->json('post', 'api/test', [
                'data' => [
                    'type' => 'test',
                    'id' => 3,
                    'attributes' => [
                        'name' => 'Bob',
                        'age' => 20,
                    ]
                ]
            ])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertExactJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'bar' => [
                        'Missing api resource from the request.'
                    ],
                    'foo' => [
                        'Missing api resource from the request.'
                    ],
                ]
            ]);
    }
}

class TestRequest extends FormRequest
{
    use ValidatesJsonApi;

    public function rules()
    {
        return [
            'test' => [
                'name' => 'required',
                'age' => 'integer|min:18'
            ],
            'bar' => Validator::make([
                'test' => 'required'
            ]),
            'foo' => FakeValidator::make()
        ];
    }

    public function messages()
    {
        return [
            'test' => [
                'age.min' => 'You must be 18+'
            ]
        ];
    }

    public function authorize()
    {
        return true;
    }
}

class TestRequestWithPresenceChecker extends TestRequest
{
    public function presenceCheckers()
    {
        return [
            'foo' => function () {
                return true;
            },
            'bar'
        ];
    }
}
