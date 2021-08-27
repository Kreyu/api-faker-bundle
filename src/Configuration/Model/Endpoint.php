<?php

declare(strict_types=1);

namespace Kreyu\Bundle\ApiFakerBundle\Configuration\Model;

class Endpoint
{
    private $path;
    private $method;
    private $response;

    public function __construct(string $path, string $method, Response $response)
    {
        $this->path = $path;
        $this->method = $method;
        $this->response = $response;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getResponse(): Response
    {
        return $this->response;
    }

    public function getPathSlug(): string
    {
        return strtolower(trim(preg_replace('/[^A-Za-z0-9]+/', '_', $this->getPath())));
    }
}
