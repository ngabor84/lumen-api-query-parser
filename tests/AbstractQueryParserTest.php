<?php

namespace Test;

use Illuminate\Http\Request;
use LumenApiQueryParser\Filter;
use LumenApiQueryParser\Pagination;
use LumenApiQueryParser\RequestParams;
use LumenApiQueryParser\Sort;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

abstract class AbstractQueryParserTest extends TestCase
{
    public function goodRequests(): array
    {
        return [
            [
                new Request(['filter' => [['field' => 'name', 'operator' => 'eq', 'value' => 'John Doe']]]),
                [
                    'filters' => [['field' => 'name', 'operator' => 'eq', 'value' => 'John Doe']],
                    'sorts' => [],
                    'limit' => null,
                    'page' => null,
                ]
            ],
            [
                new Request(['sort' => [['field' => 'name', 'direction' => 'DESC']]]),
                [
                    'filters' => [],
                    'sorts' => [['field' => 'name', 'direction' => 'DESC']],
                    'limit' => null,
                    'page' => null,
                ]
            ],
            [
                new Request(['limit' => 20, 'page' => 2]),
                [
                    'filters' => [],
                    'sorts' => [],
                    'limit' => 20,
                    'page' => 2,
                ]
            ],
            [
                new Request([
                    'filter' => [['field' => 'email', 'operator' => 'lk', 'value' => '@gmail.com']],
                    'sort' => [['field' => 'updated', 'direction' => 'ASC']],
                    'limit' => 50,
                    'page' => 11,
                ]),
                [
                    'filters' => [['field' => 'email', 'operator' => 'lk', 'value' => '@gmail.com']],
                    'sorts' => [['field' => 'updated', 'direction' => 'ASC']],
                    'limit' => 50,
                    'page' => 11,
                ]
            ],
        ];
    }

    public function badRequests(): array
    {
        return [
            [
                new Request(['filter' => [['field' => 'name']]]),
                UnprocessableEntityHttpException::class
            ],
            [
                new Request(['sort' => [['direction' => 'DESC']]]),
                UnprocessableEntityHttpException::class
            ],
            [
                new Request(['sort' => [[]], 'filter' => [[]]]),
                UnprocessableEntityHttpException::class
            ],
        ];
    }

    protected function createRequestParams(array $filters = [], array $sorts = [], int $limit = null, int $page = null): RequestParams
    {
        $requestParams = new RequestParams();

        if (!empty($filters)) {
            foreach ($filters as $filter) {
                $requestParams->addFilter(new Filter($filter['field'], $filter['operator'], $filter['value']));
            }
        }

        if (!empty($sorts)) {
            foreach ($sorts as $sort) {
                $requestParams->addSort(new Sort($sort['field'], $sort['direction']));
            }
        }

        if (!empty($limit)) {
            $requestParams->addPagination(new Pagination($limit, $page ?? 1));
        }

        return $requestParams;
    }
}
