<?php

namespace Tests\Fakes;

class FakeModel
{
    public $data;

    public function __construct($data)
    {
        $this->data = $data;
    }
}
