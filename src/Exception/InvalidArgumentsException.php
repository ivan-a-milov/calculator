<?php

declare(strict_types=1);

namespace Calculator\Exception;

class InvalidArgumentsException extends AbstractApplicationException
{
    public function __construct(
        private array $fields,
        int $code = 0,
        ?\Throwable $previous = null
    ) {
        parent::__construct('', $code, $previous);
    }

    public function getFields(): array
    {
        return $this->fields;
    }
}
