<?php

namespace LumenApiQueryParser\Params;

interface RequestParamsInterface
{
    public function hasFilter(): bool;

    public function getFilters(): array;

    public function hasSort(): bool;

    public function getSorts(): array;

    public function hasPagination(): bool;

    public function getPagination(): PaginationInterface;

    public function hasConnection(): bool;

    public function getConnections(): array;
}
