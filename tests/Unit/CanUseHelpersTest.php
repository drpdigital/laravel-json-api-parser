<?php

namespace Tests\Unit;

use Drp\JsonApiParser\Exceptions\FailedValidationException;
use Drp\JsonApiParser\JsonApiParser;
use Drp\JsonApiParser\JsonApiValidator;
use Drp\LaravelJsonApiParser\Validation\Validator;
use Tests\TestCase;

class CanUseHelpersTest extends TestCase
{
    /** @test */
    public function parser_is_resolved_from_helper()
    {
        $this->assertInstanceOf(JsonApiParser::class, json_api());
    }

    /** @test */
    public function validator_is_resolved_from_helper()
    {
        $this->assertInstanceOf(JsonApiValidator::class, json_api_validator());
    }

    /** @test */
    public function can_chain_from_parser_helper()
    {
        $collection = json_api()
            ->resolver('test', function () {
                return 3;
            })
            ->parse([
                'data' => [
                    'id' => 3,
                    'type' => 'test'
                ]
            ]);

        $this->assertEquals(3, $collection->get('test'));
    }

    /** @test */
    public function can_chain_from_validator_helper()
    {
        try {
            json_api_validator()
                ->validator('test', Validator::make([
                    'foo' => 'required'
                ]))
                ->validate([
                    'data' => [
                        'id' => 3,
                        'type' => 'test'
                    ]
                ]);
        } catch (FailedValidationException $exception) {
            $this->assertEquals([
                'test_3' => [
                    'foo' => [
                        'The foo field is required.'
                    ]
                ]
            ], $exception->getMessages());

            return;
        }

        $this->fail('Should have failed validation and thrown an exception.');
    }
}
