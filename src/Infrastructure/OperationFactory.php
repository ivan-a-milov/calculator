<?php

declare(strict_types=1);

namespace Calculator\Infrastructure;

use Calculator\Domain\OperationInterface;
use Calculator\Exception\NoSuchOperationException;

class OperationFactory
{
    /**
     * @var OperationInterface[]
     */
    private array $operations = [];

    public function __construct(iterable $operations)
    {
        /** @var OperationInterface $operation */
        foreach ($operations as $operation) {
            if (! $operation instanceof OperationInterface) {
                throw new \Exception('$operation must implement OperationInterface');
            }
            if (array_key_exists($operation->sign(), $this->operations)) {
                throw new \Exception('Two operations with same sign ' . $operation->sign());
            }
            $this->operations[$operation->sign()] = $operation;
        }
    }

    /**
     * @return string[]
     */
    public function getAllSigns(): array
    {
        return array_keys($this->operations);
    }

    /**
     * @throws NoSuchOperationException
     */
    public function createOperation(string $sign): OperationInterface
    {
        if (!array_key_exists($sign, $this->operations)) {
            throw new NoSuchOperationException($sign);
        }

        return $this->operations[$sign];
    }
}
