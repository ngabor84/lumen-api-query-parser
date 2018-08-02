<?php

namespace Test;

use Illuminate\Http\Request;
use LumenApiQueryParser\Params\Connection;
use LumenApiQueryParser\Params\Filter;
use LumenApiQueryParser\Params\Pagination;
use LumenApiQueryParser\Params\RequestParams;
use LumenApiQueryParser\Params\Sort;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

abstract class AbstractQueryParserTest extends TestCase
{
    public function goodRequests(): array
    {
        return [
            [
                new Request(['filter' => ['name:eq:John Doe']]),
                [
                    'filters' => [['field' => 'name', 'operator' => 'eq', 'value' => 'John Doe']],
                    'sorts' => [],
                    'limit' => null,
                    'page' => null,
                    'connections' => [],
                ]
            ],
            [
                new Request(['sort' => ['name:DESC']]),
                [
                    'filters' => [],
                    'sorts' => [['field' => 'name', 'direction' => 'DESC']],
                    'limit' => null,
                    'page' => null,
                    'connections' => [],
                ]
            ],
            [
                new Request(['limit' => 20, 'page' => 2]),
                [
                    'filters' => [],
                    'sorts' => [],
                    'limit' => 20,
                    'page' => 2,
                    'connections' => [],
                ]
            ],
            [
                new Request(['connection' => ['profile']]),
                [
                    'filters' => [],
                    'sorts' => [],
                    'limit' => null,
                    'page' => null,
                    'connections' => ['profile'],
                ]
            ],
            [
                new Request([
                    'filter' => ['email:ew:@gmail.com', 'createdAt:eq:2018-07-2017:06:41'],
                    'sort' => ['updated:ASC'],
                    'limit' => 50,
                    'page' => 11,
                    'connection' => ['profile'],
                ]),
                [
                    'filters' => [
                        ['field' => 'email', 'operator' => 'ew', 'value' => '@gmail.com'],
                        ['field' => 'createdAt', 'operator' => 'eq', 'value' => '2018-07-2017:06:41']
                    ],
                    'sorts' => [['field' => 'updated', 'direction' => 'ASC']],
                    'limit' => 50,
                    'page' => 11,
                    'connections' => ['profile'],
                ]
            ],
        ];
    }

    public function badRequests(): array
    {
        return [
            [
                new Request(['filter' => ['name']]),
                UnprocessableEntityHttpException::class
            ],
            [
                new Request(['sort' => [':DESC']]),
                UnprocessableEntityHttpException::class
            ],
            [
                new Request(['sort' => [''], 'filter' => ['']]),
                UnprocessableEntityHttpException::class
            ],
        ];
    }

    protected function createRequestParams(array $filters = [], array $sorts = [], int $limit = null, int $page = null, $connections = []): RequestParams
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

        if (!empty($connections)) {
            foreach ($connections as $connection) {
                $requestParams->addConnection(new Connection($connection));
            }
        }

        return $requestParams;
    }
}
