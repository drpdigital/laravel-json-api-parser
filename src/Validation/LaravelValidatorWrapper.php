<?php

namespace Drp\LaravelJsonApiParser\Validation;

use Drp\JsonApiParser\Exceptions\FailedValidationException;
use Drp\JsonApiParser\JsonApiValidator;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\MessageBag;

class LaravelValidatorWrapper implements Validator
{
    /**
     * @var \Drp\JsonApiParser\JsonApiValidator
     */
    protected $validator;

    /**
     * @var array
     */
    private $data = [];

    /**
     * LaravelValidatorWrapper constructor.
     * @param \Drp\JsonApiParser\JsonApiValidator $validator
     */
    public function __construct(JsonApiValidator $validator)
    {
        $this->validator = $validator;
    }

    /**
     * Get the messages for the instance.
     *
     * @return \Illuminate\Contracts\Support\MessageBag
     */
    public function getMessageBag()
    {
        return $this->errors();
    }

    /**
     * Determine if the data passes the validation rules.
     *
     * @return bool
     * @throws \ReflectionException
     */
    public function passes()
    {
        try {
            return $this->validator->validate($this->data);
        } catch (FailedValidationException $exception) {
            return false;
        }
    }

    /**
     * Determine if the data fails the validation rules.
     *
     * @return bool
     * @throws \ReflectionException
     */
    public function fails()
    {
        return $this->passes() === false;
    }

    /**
     * Get the failed validation rules.
     *
     * @return array
     */
    public function failed()
    {
        // TODO: Implement failed() method.
    }

    /**
     * Set the data to be passed to the validator.
     *
     * @param array $data
     * @return $this
     */
    public function with($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Add conditions to a given field based on a Closure.
     *
     * @param  string $attribute
     * @param  string|array $rules
     * @param  callable $callback
     * @return $this
     */
    public function sometimes($attribute, $rules, callable $callback)
    {
        // TODO: Implement sometimes() method.
    }

    /**
     * After an after validation callback.
     *
     * @param  callable|string $callback
     * @return $this
     */
    public function after($callback)
    {
        // TODO: Implement after() method.
    }

    /**
     * Get all of the validation error messages.
     *
     * @return \Illuminate\Support\MessageBag
     */
    public function errors()
    {
        return new MessageBag($this->validator->getErrors());
    }

    /**
     * Call Json Api Validator if the function doesn't exist on the wrapper.
     *
     * @param string $name
     * @param array $arguments
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        return call_user_func_array([$this->validator, $name], $arguments);
    }
}
