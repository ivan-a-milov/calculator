<?php

declare(strict_types=1);

namespace Calculator\Domain;

class Addition extends AbstractOperation
{
    public function sign(): string
    {
        return '+';
    }

    public function apply(float $firstArgument, float $secondArgument): float
    {
        return $firstArgument + $secondArgument;
    }
}
