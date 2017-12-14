<?php

namespace Tests\Unit;

use Drp\JsonApiParser\JsonApiParser;
use Tests\TestCase;

class ContainerDependenciesTest extends TestCase
{
    /** @test */
    public function check_all_the_classes_are_bound_to_the_container()
    {
        $parser = $this->app->make(JsonApiParser::class);

        $this->assertInstanceOf(JsonApiParser::class, $parser);
    }
}
