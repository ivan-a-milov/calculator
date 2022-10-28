<?php

namespace Calculator\Tests\Domain;

use Calculator\Domain\Division;
use Calculator\Domain\OperationInterface;
use Calculator\Exception\DivisionByZeroException;
use PHPUnit\Framework\TestCase;

class DivisionTest extends TestCase
{
    private OperationInterface $operation;

    protected function setUp(): void
    {
        $this->operation = new Division();
    }

    public function testApply()
    {
        $this->assertEqualsWithDelta(
            0.333333,
            $this->operation->apply(1, 3),
            0.1111111,
        );
    }

    public function testValidate()
    {
        $this->expectException(DivisionByZeroException::class);
        $this->operation->validate(1, 0);
    }
}
