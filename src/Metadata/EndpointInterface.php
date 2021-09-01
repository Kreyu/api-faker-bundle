<?php

declare(strict_types=1);

namespace Kreyu\Bundle\ApiFakerBundle\Metadata;

interface EndpointInterface
{
    public function getId(): string;

    public function getMethod(): string;

    public function getPath(): string;

    public function getStatusCode(): int;

    public function getContent();

    public function getContentFormat(): ?string;

    public function getHeaders(): array;

    public function getRouteName(): string;
}
