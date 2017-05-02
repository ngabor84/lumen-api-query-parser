<?php

namespace LumenApiQueryParser\Params;

class Pagination implements PaginationInterface
{
    protected $limit;
    protected $page;

    public function __construct(int $limit = null, int $page = null)
    {
        $this->setLimit($limit);
        $this->setPage($page);
    }

    public function setLimit(int $limit): void
    {
        $this->limit = $limit;
    }

    public function getLimit(): ?int
    {
        return $this->limit;
    }

    public function setPage(int $page): void
    {
        $this->page = $page;
    }

    public function getPage(): ?int
    {
        return $this->page;
    }
}
