<?php

declare(strict_types=1);

namespace Calculator\Domain;

abstract class AbstractOperation implements OperationInterface
{
    public function validate(float $firstArgument, float $secondArgument): void
    {
    }
}
