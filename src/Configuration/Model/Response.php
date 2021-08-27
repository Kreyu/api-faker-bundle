<?php

declare(strict_types=1);

namespace Kreyu\Bundle\ApiFakerBundle\Configuration\Model;

use Symfony\Component\HttpFoundation\Response as HttpResponse;

class Response
{
    private $status;
    private $body;

    public function __construct(?int $status = null, $body = null)
    {
        $this->status = $status;
        $this->body = $body;
    }

    public function getStatus(): int
    {
        if (null === $this->status) {
            return null !== $this->body
                ? HttpResponse::HTTP_OK
                : HttpResponse::HTTP_NO_CONTENT;
        }

        return $this->status;
    }

    public function getBody(): ?string
    {
        if (is_string($this->body) && is_file($this->body)) {
            return file_get_contents($this->body);
        }

        if (is_array($this->body)) {
            return json_encode($this->body);
        }

        json_decode($this->body);

        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new \InvalidArgumentException(sprintf(
                '%s. Response body has invalid json. If you provided path to file, then the file does not exist.',
                json_last_error_msg()
            ));
        }

        return $this->body;
    }
}
