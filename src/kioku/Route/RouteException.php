<?php

namespace Kioku\Route;

use Throwable;

class RouteException extends \Exception
{
    /**
     * RouteException constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct(
            $message,
            $code,
            $previous
        );
    }
}