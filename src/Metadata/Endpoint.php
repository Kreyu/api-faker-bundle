<?php

declare(strict_types=1);

namespace Kreyu\Bundle\ApiFakerBundle\Metadata;

class Endpoint implements EndpointInterface
{
    private $method;
    private $path;
    private $statusCode;
    private $content;
    private $contentFormat;
    private $headers;

    public function __construct(string $method, string $path, int $statusCode, $content, ?string $contentFormat, array $headers)
    {
        $this->method = $method;
        $this->path = $path;
        $this->statusCode = $statusCode;
        $this->content = $content;
        $this->contentFormat = $contentFormat;
        $this->headers = $headers;
    }

    public function getId(): string
    {
        return md5($this->getMethod() . '_' . $this->getRouteName());
    }

    public function getRouteName(): string
    {
        return strtolower(trim(preg_replace('/[^A-Za-z0-9]+/', '_', $this->path)));
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function getContentFormat(): ?string
    {
        return $this->contentFormat;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }
}
