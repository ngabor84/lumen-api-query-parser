<?php

namespace LumenApiQueryParser\Params;

interface FilterInterface
{
    public function getField(): string;

    public function getOperator(): string;

    public function getValue(): string;
}
