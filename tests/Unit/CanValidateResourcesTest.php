<?php

namespace Tests\Unit;

use Drp\JsonApiParser\Exceptions\FailedValidationException;
use Drp\JsonApiParser\JsonApiValidator;
use Drp\LaravelJsonApiParser\Validation\Validator;
use Tests\Fakes\FakeValidator;
use Tests\TestCase;

class CanValidateResourcesTest extends TestCase
{
    /** @test */
    public function can_validate_resource_with_validator_factory()
    {
        $validator = $this->app->make(JsonApiValidator::class);

        $validator->validator('resource', Validator::make([
            'test' => 'required'
        ]));

        $this->assertInstanceOf(Validator::class, $validator->resource);

        try {
            $validator->validate([
                'data' => [
                    'id' => 1,
                    'type' => 'resource'
                ]
            ]);
        } catch (FailedValidationException $exception) {
            $this->assertEquals(['resource_1'], array_keys($exception->getMessages()));

            return;
        }

        $this->fail('Should have failed validation.');
    }

    /** @test */
    public function can_validate_with_validator_class()
    {
        $validator = $this->app->make(JsonApiValidator::class);

        $validator->validator('resource', new FakeValidator());

        try {
            $validator->validate([
                'data' => [
                    'id' => 1,
                    'type' => 'resource'
                ]
            ]);
        } catch (FailedValidationException $exception) {
            $this->assertEquals(['resource_1'], array_keys($exception->getMessages()));
            $this->assertEquals(['test' => ['Testing Message']], $exception->getMessages()['resource_1']);

            return;
        }

        $this->fail('Should have failed validation.');
    }

    /** @test */
    public function can_validate_with_given_validator()
    {
        $validator = $this->app->make(JsonApiValidator::class);

        $validator->validator('resource', new Validator(\Validator::make([], [
            'test' => 'required'
        ], [])));

        try {
            $validator->validate([
                'data' => [
                    'id' => 1,
                    'type' => 'resource'
                ]
            ]);
        } catch (FailedValidationException $exception) {
            $this->assertEquals(['resource_1'], array_keys($exception->getMessages()));

            return;
        }

        $this->fail('Should have failed validation.');
    }
}
