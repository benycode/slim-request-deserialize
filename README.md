# Slim

Deserialize Slim 4 requests with the Symfony Serializer

## Table of contents

- [Install](#install)
- [Usage](#usage)

## Install

Via Composer

``` bash
$ composer require benycode/slim-request-deserialize
```

Requires Slim 4.

## Usage

add a Middlewares to route:

```php
use BenyCode\Slim\RequestDeserialize\RequestDeserializeMiddleware;
use App\RequestData;

$app = new \Slim\App();

$app->post('/api/any_end_point',function ($req, $res, $args) {
 
})
->add(new RequestDeserializeMiddleware([
	RequestData::class,
]))	
;

$app->run();
```
