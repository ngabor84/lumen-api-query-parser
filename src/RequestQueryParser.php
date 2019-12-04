<?php

namespace LumenApiQueryParser;

use Illuminate\Http\Request;
use LumenApiQueryParser\Params\Connection;
use LumenApiQueryParser\Params\Filter;
use LumenApiQueryParser\Params\Pagination;
use LumenApiQueryParser\Params\RequestParams;
use LumenApiQueryParser\Params\RequestParamsInterface;
use LumenApiQueryParser\Params\Sort;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class RequestQueryParser implements RequestQueryParserInterface
{
    protected $requestParams;

    public function __construct()
    {
        $this->requestParams = new RequestParams();
    }

    public function parse(Request $request): RequestParamsInterface
    {
        $this->parseFilters($request);
        $this->parseSort($request);
        $this->parsePagination($request);
        $this->parseConnections($request);

        return $this->requestParams;
    }

    protected function parseFilters(Request $request): void
    {
        $filters = $request->has('filter') ? $request->get('filter') : [];

        foreach ($filters as $filter) {
            $filterDatas = explode(':', $filter, 3);

            if (count($filterDatas) < 3) {
                throw new UnprocessableEntityHttpException('Filter must contains field and value!');
            }
            [$field, $operator, $value] = $filterDatas;

            $this->requestParams->addFilter(new Filter($field, $operator, $value));
        }
    }

    protected function parseSort(Request $request): void
    {
        $sorts = $request->has('sort') ? $request->get('sort') : [];

        foreach ($sorts as $sort) {
            [$field, $direction] = explode(':', $sort);

            if ($field === '') {
                throw new UnprocessableEntityHttpException('Sort must contains field!');
            }

            $this->requestParams->addSort(new Sort($field, $direction));
        }
    }

    protected function parsePagination(Request $request): void
    {
        if ($request->has('limit')) {
            $limit = (int) $request->get('limit');
            $page = (int) ($request->has('page') ? $request->get('page') : 1);

            $this->requestParams->addPagination(new Pagination($limit, $page));
        }
    }

    protected function parseConnections($request): void
    {
        if ($request->has('connection')) {
            foreach ($request->get('connection') as $connection) {
                $this->requestParams->addConnection(new Connection($connection));
            }
        }
    }
}
