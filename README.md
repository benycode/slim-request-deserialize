# Slim 4 request middleware deserialize

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

create entity class:

```php
declare(strict_types=1);

namespace App\Domain\Client\Data;

final class LoginResult
{
    public string $username;

    public string $loginId;

    public string $publicKey;

    public function setUsername(string $username): self
    {
        $this->username = $username;
	return $this;
    }

    public function getUsername(): ?string 
    {
        return $this
	    ->username
	;
    }

    public function setLoginId(string $loginId): self
    {
        $this->loginId = $loginId;
	return $this;
    }

    public function getLoginId(): ?string
    {
	return $this
	    ->loginId
        ;
    }

    public function setPublicKey(string $publicKey): self
    {
	$this->publicKey = $publicKey;
	return $this;
    }

    public function getPublicKey(): ?string
    {
	return $this
	    ->publicKey
        ;
    }
}
```

add a Middlewares to route:

```php
use BenyCode\Slim\RequestDeserialize\RequestDeserializeMiddleware;
use Symfony\Component\Serializer\Encoder\JsonEncoder as DeserializeEncoder;
use App\RequestData;

$app = new \Slim\App();

$app->post('/api/any_end_point',function ($req, $res, $args) {
 
})
->add(new RequestDeserializeMiddleware([
    RequestData::class,
    DeserializeEncoder::FORMAT, // json encoder, but you can choice xml, yaml, csv
]))	
;

$app->run();
```

use your deserialized data:

```php
...
public function __invoke(
    ServerRequestInterface $request,
    ResponseInterface $response
): ResponseInterface {
    $requestData = $request
        ->getAttribute('request_data')
    ;
    
    $requestData->getUsername(); // get the username
    $requestData->getLoginId(); // get the login id
    ...
}
...
```

