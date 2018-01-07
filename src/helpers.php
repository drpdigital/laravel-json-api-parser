<?php

use Drp\JsonApiParser\JsonApiParser;
use Drp\JsonApiParser\JsonApiValidator;

if (!function_exists('json_api')) {
    /**
     * Returns an instance of JsonApiParser.
     *
     * @return \Drp\JsonApiParser\JsonApiParser
     */
    function json_api()
    {
        return app(JsonApiParser::class);
    }
}

if (!function_exists('json_api_validator')) {
    /**
     * Returns an instance of JsonApiValidator.
     *
     * @return \Drp\JsonApiParser\JsonApiValidator
     */
    function json_api_validator()
    {
        return app(JsonApiValidator::class);
    }
}
