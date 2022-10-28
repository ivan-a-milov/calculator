<?php

namespace Calculator\Tests\Infrastructure;

use Calculator\Domain\AbstractOperation;
use Calculator\Domain\OperationInterface;
use Calculator\Exception\NoSuchOperationException;
use Calculator\Infrastructure\OperationFactory;
use PHPUnit\Framework\TestCase;

class OperationFactoryTest extends TestCase
{
    public function testNotAnOperation()
    {
        $this->expectException(\Exception::class);
        new OperationFactory([new class {
            public function sign() {
                return '-';
            }
        }]);
    }

    public function testSignDuplication()
    {
        $this->expectException(\Exception::class);
        $this->mockTwoOpsFactory(';', ';');
    }

    public function testAllSigns()
    {
        $factory = $this->mockTwoOpsFactory('+', '-');
        $this->assertEquals(['+', '-'], $factory->getAllSigns());
    }

    public function testNoSuchOperation()
    {
        $factory = $this->mockTwoOpsFactory('+', '-');
        $this->expectException(NoSuchOperationException::class);
        $factory->createOperation('!');
    }

    public function testCreateOperation()
    {
        $operation =  $this->mockOperation('-');
        $factory = new OperationFactory([$operation]);
        $this->assertEquals($operation, $factory->createOperation('-'));
    }

    private function mockTwoOpsFactory(string $sign1, string $sign2): OperationFactory
    {
        return new OperationFactory([
            $this->mockOperation($sign1),
            $this->mockOperation($sign2),
        ]);
    }

    private function mockOperation(string $sign): OperationInterface
    {
        return new class ($sign) extends AbstractOperation {
            public function __construct(private string $sign)
            {
            }
            public function apply(float $firstArgument, float $secondArgument): float
            {
                return 0;
            }
            public function sign(): string
            {
                return $this->sign;
            }
        };
    }
}
