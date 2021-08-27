<?php

declare(strict_types=1);

namespace Kreyu\Bundle\ApiFakerBundle\Configuration\Model;

use Symfony\Component\HttpFoundation\Response as HttpResponse;

class Response
{
    private $status;
    private $body;

    public function __construct(int $status, $body = null)
    {
        $this->status = $status;
        $this->body = $body;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function getBody(): ?string
    {
        if (is_string($this->body)) {
            if (is_file($this->body)) {
                return file_get_contents($this->body);
            }

            json_decode($this->body);

            if (JSON_ERROR_NONE !== json_last_error()) {
                throw new \InvalidArgumentException(sprintf(
                    '%s. Response body has invalid json. If you provided path to file, then the file does not exist.',
                    json_last_error_msg()
                ));
            }
        }

        if (is_array($this->body)) {
            return json_encode($this->body);
        }

        return $this->body;
    }
}
