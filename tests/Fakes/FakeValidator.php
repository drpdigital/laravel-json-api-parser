<?php

namespace Tests\Fakes;

use Drp\LaravelJsonApiParser\Validation\Validator;

class FakeValidator extends Validator
{
    public function rules()
    {
        return [
            'test' => 'required',
        ];
    }

    public function messages()
    {
        return [
           'test.required' => 'Testing Message',
        ];
    }
}
