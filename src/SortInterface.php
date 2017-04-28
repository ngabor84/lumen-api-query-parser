<?php

namespace LumenApiQueryParser;

interface SortInterface
{
    public function getField(): string;

    public function getDirection(): string;
}
