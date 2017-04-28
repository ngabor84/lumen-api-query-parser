<?php

namespace LumenApiQueryParser;

interface RequestParamsInterface
{
    public function getFilters(): array;

    public function getSorts(): array;

    public function getPagination(): PaginationInterface;
}
