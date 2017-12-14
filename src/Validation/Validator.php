<?php

namespace Drp\LaravelJsonApiParser\Validation;

use Drp\JsonApiParser\Contracts\ValidatorExecutor;
use Illuminate\Contracts\Validation\Validator as ValidatorContract;
use Validator as ValidatorFactory;

class Validator implements ValidatorExecutor
{
    /**
     * @var \Illuminate\Validation\Validator
     */
    protected $validator;

    /**
     * Validator constructor.
     *
     * @param array|null $rules
     * @param array|null $messages
     */
    public function __construct($rules = null, $messages = null)
    {
        if ($rules instanceof ValidatorContract) {
            $this->validator = $rules;

            return;
        }

        if ($rules === null && method_exists($this, 'rules')) {
            $rules = $this->rules();
        }

        if ($messages === null && method_exists($this, 'messages')) {
            $messages = $this->messages();
        }

        $this->validator = ValidatorFactory::make([], (array) $rules, (array) $messages);
    }

    /**
     * Data to be validated.
     *
     * @param array $data
     * @return void
     */
    public function with(array $data)
    {
        $this->validator->setData($data);
    }

    /**
     * Run the validation using the given data from with()
     * If the validation doesn't pass then return false.
     *
     * @return boolean
     */
    public function passes()
    {
        dd($this->validator);
        return $this->validator->passes();
    }

    /**
     * @return mixed
     */
    public function errors()
    {
        return $this->validator->errors()->toArray();
    }

    /**
     * Make a validator instance with optional rules and messages. If rules or messages is null
     * then it will try to use the rules and messages function on this class.
     *
     * @param array|null $rules
     * @param array|null $messages
     * @return self
     */
    public static function make($rules = null, $messages = null)
    {
        return new static($rules, $messages);
    }

    public function __call($name, $arguments)
    {
        return app()->call([$this->validator, $name], $arguments);
    }

    public function __get($name)
    {
        return $this->validator->$name;
    }

    public function __set($name, $value)
    {
        $this->validator->$name = $value;
    }

    public function __isset($name)
    {
        return isset($this->validator->$name);
    }
}
