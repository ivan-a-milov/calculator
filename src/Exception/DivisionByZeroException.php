<?php

declare(strict_types=1);

namespace Calculator\Exception;

class DivisionByZeroException extends InvalidOperationException
{
    public function __construct(int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct('Division by zero', $code, $previous);
    }
}
