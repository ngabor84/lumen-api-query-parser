<?php

namespace LumenApiQueryParser;

class Sort implements SortInterface
{
    protected $field;
    protected $direction;

    public function __construct(string $field, string $direction = 'ASC')
    {
        $this->setField($field);
        $this->setDirection($direction);
    }

    public function setField(string $field): void
    {
        $this->field = $field;
    }

    public function getField(): string
    {
        return $this->field;
    }

    public function setDirection(string $direction): void
    {
        $this->direction = $direction;
    }

    public function getDirection(): string
    {
        return $this->direction;
    }
}
