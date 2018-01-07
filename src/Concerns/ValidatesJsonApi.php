<?php

namespace Drp\LaravelJsonApiParser\Concerns;

use Drp\LaravelJsonApiParser\Validation\LaravelValidatorWrapper;
use Drp\LaravelJsonApiParser\Validation\Validator;

trait ValidatesJsonApi
{
    /**
     * Create a JSON API validator compatible with the Validator contract.
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function validator()
    {
        $validator = app(LaravelValidatorWrapper::class, [json_api_validator()])
            ->with($this->validationData());

        $validator = $this->addRulesToValidator($validator);
        $validator = $this->addPresenceCheckersToValidator($validator);

        return $validator;
    }

    /**
     * @param array|\Drp\LaravelJsonApiParser\Validation\Validator $rules
     * @param array $messages
     * @return \Drp\LaravelJsonApiParser\Validation\Validator
     */
    protected function buildJsonApiValidator($rules, array $messages = [])
    {
        if ($rules instanceof Validator) {
            return $rules;
        }

        return Validator::make($rules, $messages);
    }

    /**
     * Adds the rules and messages to the validator.
     *
     * @param \Drp\LaravelJsonApiParser\Validation\LaravelValidatorWrapper $validator
     * @return \Drp\LaravelJsonApiParser\Validation\LaravelValidatorWrapper
     */
    protected function addRulesToValidator($validator)
    {
        if (method_exists($this, 'rules')) {
            $messages = $this->messages();
            foreach ($this->rules() as $type => $rules) {
                $validator->validator(
                    $type,
                    $this->buildJsonApiValidator($rules, array_get($messages, $type, []))
                );
            }
        }

        return $validator;
    }

    /**
     * Adds the presence checkers to the validator.
     *
     * @param \Drp\LaravelJsonApiParser\Validation\LaravelValidatorWrapper $validator
     * @return \Drp\LaravelJsonApiParser\Validation\LaravelValidatorWrapper
     */
    protected function addPresenceCheckersToValidator($validator)
    {
        if (method_exists($this, 'presenceCheckers')) {
            foreach ($this->presenceCheckers() as $type => $presenceChecker) {
                if (is_string($presenceChecker)) {
                    $validator->presenceChecker($presenceChecker);
                    continue;
                }

                $validator->presenceChecker($type, $presenceChecker);
            }
        }

        return $validator;
    }
}
