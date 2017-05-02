<?php

namespace LumenApiQueryParser;

use Illuminate\Http\Request;
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
        if ($request->has('filter')) {
            foreach ($request->get('filter') as $filter) {
                if (!isset($filter['field']) || !isset($filter['value'])) {
                    throw new UnprocessableEntityHttpException('Filter must contains field and value!');
                }

                $field = $filter['field'];
                $operator = $filter['operator'] ?? 'eq';
                $value = $filter['value'];

                $this->requestParams->addFilter(new Filter($field, $operator, $value));
            }
        }
    }

    protected function parseSort(Request $request): void
    {
        if ($request->has('sort')) {
            foreach ($request->get('sort') as $sort) {
                if (!isset($sort['field'])) {
                    throw new UnprocessableEntityHttpException('Sort must contains field!');
                }

                $field = $sort['field'];
                $direction = $sort['direction'];

                $this->requestParams->addSort(new Sort($field, $direction));
            }
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
