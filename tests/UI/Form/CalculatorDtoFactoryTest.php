<?php

namespace Calculator\Tests\UI\Form;

use Calculator\Domain\AbstractOperation;
use Calculator\Domain\OperationInterface;
use Calculator\Exception\InvalidArgumentsException;
use Calculator\Infrastructure\OperationFactory;
use Calculator\UI\Form\CalculatorDtoFactory;
use PHPUnit\Framework\TestCase;

class CalculatorDtoFactoryTest extends TestCase
{
    public function testFirstArgumentInvalid()
    {
        $this->expectException(InvalidArgumentsException::class);
        $factory = $this->cleateFactoryMock();
        $factory->createDto('foobar', '-', '23.2');
    }

    public function testSecondArgumentInvalid()
    {
        $this->expectException(InvalidArgumentsException::class);
        $factory = $this->cleateFactoryMock();
        $factory->createDto('321', '-', '====');
    }

    public function testBothArgumentsInvalidFields()
    {
        $factory = $this->cleateFactoryMock();
        try {
            $factory->createDto('+', '-', '?');
            // TODO убрать бы этот костыль
            $this->assertTrue(false, "InvalidArgumentsException should be thrown");
        } catch (InvalidArgumentsException $exception) {
            $this->assertEquals(['firstArgument', 'secondArgument'], $exception->getFields());
        }

    }

    public function testCreateDto()
    {
        $operation = new class extends AbstractOperation {
            public function apply(float $firstArgument, float $secondArgument): float
            {
                return 0;
            }
            public function sign(): string
            {
                return '^^';
            }
        };

        $operationFactory = $this->createMock(OperationFactory::class);
        $operationFactory->method('createOperation')
            ->willReturn($operation);
        $factory = new CalculatorDtoFactory($operationFactory);

        $dto = $factory->createDto('98', '*', '34');
        $this->assertEquals(98, $dto->getFirstArgument());
        $this->assertEquals($operation, $dto->getOperation());
        $this->assertEquals(34, $dto->getSecondArgument());
    }

    private function cleateFactoryMock(): CalculatorDtoFactory
    {
        $operation = $this->createMock(OperationInterface::class);
        $operationFactory = $this->createMock(OperationFactory::class);
        $operationFactory->method('createOperation')
            ->willReturn($operation);

        return new CalculatorDtoFactory($operationFactory);
    }
}
