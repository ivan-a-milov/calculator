<?php

declare(strict_types=1);

namespace Calculator\Domain;

interface OperationInterface
{
    public function sign(): string;

    public function validate(float $firstArgument, float $secondArgument): void;

    public function apply(float $firstArgument, float $secondArgument): float;
}
