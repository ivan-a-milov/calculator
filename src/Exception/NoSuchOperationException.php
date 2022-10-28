<?php

declare(strict_types=1);

namespace Calculator\Exception;

class NoSuchOperationException extends AbstractApplicationException
{
    public function __construct(string $sign, int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct("No such operation: $sign", $code, $previous);
    }
}
