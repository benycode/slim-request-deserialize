<?php

declare(strict_types=1);

namespace BenyCode\Slim\RequestDeserialize;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Symfony\Component\Serializer\Encoder\YamlEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\PropertyInfo\Extractor\PhpDocExtractor;
use Symfony\Component\PropertyInfo\PropertyInfoExtractor;
use Symfony\Component\Serializer\Serializer;

final class RequestDeserializeMiddleware implements MiddlewareInterface
{
    private Serializer $serializer;

    public function __construct(
        private string $entity,
        private string $contentType,
    ) {
        $encoders = [
            new JsonEncoder(),
            new XmlEncoder(),
            new CsvEncoder(),
            new YamlEncoder(),
        ];

        $extractor = new PropertyInfoExtractor([], [
			new PhpDocExtractor(),
			new ReflectionExtractor(),
		]);

		$normalizers = [
			new ArrayDenormalizer(),
			new ObjectNormalizer(
				null,
				new CamelCaseToSnakeCaseNameConverter(),
				null,
				$extractor
			),
		];

		$this->serializer = new Serializer($normalizers, $encoders);
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $requestBodyString = (string)$request
            ->getBody();

        $deserializedData = $this
			->serializer
			->deserialize(
				$requestBodyString,
				$this->entity,
				$this->contentType,
			);

        $request = $request
            ->withAttribute('request-data', $deserializedData);

        return $handler
            ->handle($request);
    }
}
