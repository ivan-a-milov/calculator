<?php

declare(strict_types=1);

namespace Calculator\UI\Form\Dto;

use Calculator\Domain\OperationInterface;

class CalculatorDto
{
    public function __construct(
        private float $firstArgument,
        private OperationInterface $operation,
        private float $secondArgument,
    )
    {}

    public function getFirstArgument(): float
    {
        return $this->firstArgument;
    }

    public function setFirstArgument(float $firstArgument): self
    {
        $this->firstArgument = $firstArgument;

        return $this;
    }

    public function getOperation(): OperationInterface
    {
        return $this->operation;
    }

    public function setOperation(OperationInterface $operation): self
    {
        $this->operation = $operation;

        return $this;
    }

    public function getSecondArgument(): float
    {
        return $this->secondArgument;
    }

    public function setSecondArgument(float $secondArgument): self
    {
        $this->secondArgument = $secondArgument;

        return $this;
    }
}
