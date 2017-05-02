<?php

namespace LumenApiQueryParser\Params;

class Filter implements FilterInterface
{
    protected $field;
    protected $operator;
    protected $value;

    public function __construct(string $field, string $operator, string $value)
    {
        $this->setField($field);
        $this->setOperator($operator);
        $this->setValue($value);
    }

    public function setField(string $field): void
    {
        $this->field = $field;
    }

    public function getField(): string
    {
        return $this->field;
    }

    public function setOperator(string $operator): void
    {
        $this->operator = $operator;
    }

    public function getOperator(): string
    {
        return $this->operator;
    }

    public function setValue(string $value): void
    {
        $this->value = $value;
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
