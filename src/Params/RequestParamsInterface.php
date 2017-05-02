<?php

namespace LumenApiQueryParser\Params;

interface RequestParamsInterface
{
    public function getFilters(): array;

    public function getSorts(): array;

    public function getPagination(): PaginationInterface;

    public function getConnection(): array;
}
