<?php

declare(strict_types=1);

namespace Kreyu\Bundle\ApiFakerBundle\Configuration\Model;

class Application
{
    private $name;
    private $prefix;

    /**
     * @var array<Endpoint>
     */
    private $endpoints;

    public function __construct(string $name, ?string $prefix = null, array $endpoints = [])
    {
        $this->name = $name;
        $this->prefix = $prefix;
        $this->endpoints = $endpoints;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPrefix(): ?string
    {
        return trim($this->prefix, '/');
    }

    public function getEndpoints(): array
    {
        return $this->endpoints;
    }
}
