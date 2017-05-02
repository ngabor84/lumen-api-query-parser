<?php

namespace LumenApiQueryParser\Params;

class RequestParams implements RequestParamsInterface
{
    protected $filters = [];
    protected $sorts = [];
    protected $pagination;
    protected $connections = [];

    public function addFilter(FilterInterface $filter): void
    {
        $this->filters[] = $filter;
    }

    public function getFilters(): array
    {
        return $this->filters;
    }

    public function addSort(SortInterface $sort): void
    {
        $this->sorts[] = $sort;
    }

    public function getSorts(): array
    {
        return $this->sorts;
    }

    public function addPagination(PaginationInterface $pagination): void
    {
        $this->pagination = $pagination;
    }

    public function getPagination(): PaginationInterface
    {
        return $this->pagination;
    }

    public function addConnection(ConnectionInterface $connection): void
    {
        $this->connections[] = $connection;
    }

    public function getConnection(): array
    {
        return $this->connections;
    }
}
