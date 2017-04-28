<?php

namespace LumenApiQueryParser;

interface PaginationInterface
{
    public function getLimit(): ?int;

    public function getPage(): ?int;
}
