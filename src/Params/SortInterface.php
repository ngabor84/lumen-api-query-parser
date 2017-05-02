<?php

namespace LumenApiQueryParser\Params;

interface SortInterface
{
    public function getField(): string;

    public function getDirection(): string;
}
