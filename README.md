# JSON API parser for Laravel
This is a Laravel framework integration for the [JSON API parser](https://github.com/drpdigital/json-api-parser).

The JSON API parser allows you to read and validate requests that are structured with the [jsonapi.org](https://jsonapi.org) specification.

## Version compatibility
| JSON API Parser version | Laravel Version  | PHP Version |
| ----------------------- | -----------------| ----------- |
| 1.X                     | 5.1 - 5.5        | >= 5.6       |

## Installation

You install the package by using composer:

```bash
composer require drpdigital/laravel-json-api-parser
```

If you are using `Laravel 5.5` onwards the package will automatically register itself. 

If you are on `Laravel 5.4` or lower then you will need to register the service provider in your `config/app.php`
```php
'providers' => [
    ...
    \Drp\LaravelJsonApiParser\JsonApiParserServiceProvider::class,
    ...
]
``` 

## Documentation

### How to validate your resources
When wanting to validate a resource within your payload, you need to give the `JsonApiValidator` a `ValidatorExecutor`.
This can be done in a few ways specified below. With all of these the first parameter is a string of the type of resource it needs to validate.

So for example if you had a request like:

```json
{
  "data": {
    "id": 1,
    "type": "user",
    "attributes": {
      "name": "Bob"
    }
  }
}
```

Then your first parameter would be `'user'`.

#### Using `::make`

```php
$jsonApiValidator = app(JsonApiValidator::class);
$jsonApiValidator->addValidator(
    'user', 
    \Drp\LaravelJsonApiParser\Validation\Validator::make(
        ['name' => 'required'],
        ['name.required' => 'You must provide a name']
    )
);
```

The rules and messages you provide are whatever Laravel can support as our validator is just a decorated for Laravel's.

#### Using custom class
When using a custom validator class you will need to extend our validator class `\Drp\LaravelJsonApiParser\Validation\Validator`. 
You then specify a `rules` and `messages` function inside the class and return an array of rules and messages in their respective functions.

```php
<?php

namespace App\Validators;

use Drp\LaravelJsonApiParser\Validation\Validator;

class UserValidator extends Validator
{
    public function rules()
    {
        return [
            'name' => 'required',
        ];
    }

    public function messages()
    {
        return [
           'name.required' => 'Testing Message',
        ];
    }
}
```

```php
$jsonApiValidator = app(JsonApiValidator::class);
$jsonApiValidator->addValidator(
    'user', 
    new UserValidator()
);
```

### How to resolve your resources into Models

For more documentation on how to use the JSON API parser please visit the [base package's repository](https://github.com/drpdigital/json-api-parser). 

## Contributing
Raise any [issues](https://github.com/drpdigital/laravel-json-api-parser/issues) or [feature requests](https://github.com/drpdigital/laravel-json-api-parser/pulls) within GitHub and please follow our guidelines when contributing. 

If you have found a security vulnerbility with the package please email Chris directly at [chris.normansell@drpgroup.com](mailto:chris.normansell@drpgroup.com)

## License
The Laravel JSON API Parser integration and it's base package are both realted under the [MIT License]. 
