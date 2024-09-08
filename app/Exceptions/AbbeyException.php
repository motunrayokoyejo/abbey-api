<?php

namespace App\Exceptions;

class AbbeyException extends \Exception
{
    protected int $statusCode;

    /**
     * Abbey Exception constructor.
     *
     * @param string $message
     * @param int $statusCode
     */
    public function __construct(string $message, int $statusCode = 400)
    {
        parent::__construct($message);
        $this->statusCode = $statusCode;
    }

    /**
     * Get the HTTP status code.
     *
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
}
