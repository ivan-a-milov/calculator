<?php

declare(strict_types=1);

namespace Calculator\UI\Form;

use Calculator\Exception\InvalidArgumentsException;
use Calculator\Infrastructure\OperationFactory;
use Calculator\UI\Form\Dto\CalculatorDto;

class CalculatorDtoFactory
{
    public function __construct(
        readonly private OperationFactory $operationFactory,
    )
    {}

    public function createDto(string $firstArgument, string $operation, string $secondArgument): CalculatorDto
    {
        $invalidArguments = [];
        $firstArgument = filter_var($firstArgument, FILTER_VALIDATE_FLOAT);
        if ($firstArgument === false) {
            $invalidArguments[] = 'firstArgument';
        }

        $operation = $this->operationFactory->createOperation($operation);

        $secondArgument = filter_var($secondArgument, FILTER_VALIDATE_FLOAT);
        if ($secondArgument === false) {
            $invalidArguments[] = 'secondArgument';
        }

        if (count($invalidArguments)) {
            throw new InvalidArgumentsException($invalidArguments);
        }

        $operation->validate($firstArgument, $secondArgument);

        return new CalculatorDto($firstArgument, $operation, $secondArgument);
    }
}
