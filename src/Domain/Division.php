<?php

declare(strict_types=1);

namespace Calculator\Domain;

use Calculator\Exception\DivisionByZeroException;

class Division extends AbstractOperation
{
    public function sign(): string
    {
        return '/';
    }

    public function apply(float $firstArgument, float $secondArgument): float
    {
        return $firstArgument / $secondArgument;
    }

    public function validate(float $firstArgument, float $secondArgument): void
    {
        if ($secondArgument == 0) {
            throw new DivisionByZeroException();
        }
    }
}
