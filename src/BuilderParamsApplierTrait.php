<?php

namespace LumenApiQueryParser;

use Illuminate\Database\Eloquent\Builder;
use LumenApiQueryParser\Params\Filter;
use LumenApiQueryParser\Params\RequestParamsInterface;
use LumenApiQueryParser\Params\Sort;

trait BuilderParamsApplierTrait
{
    public function applyParams(Builder $query, RequestParamsInterface $params)
    {
        if ($params->hasFilter()) {
            foreach ($params->getFilters() as $filter) {
                $this->applyFilter($query, $filter);
            }
        }

        if ($params->hasSort()) {
            foreach ($params->getSorts() as $sort) {
                $this->applySort($query, $sort);
            }
        }

        if ($params->hasPagination()) {
            $pagination = $params->getPagination();
            $query->limit($pagination->getLimit());
            $query->offset($pagination->getPage() * $pagination->getLimit());
        }

        if ($params->hasConnection()) {
            $with = [];
            foreach ($params->getConnections() as $connection) {
                $with[] = $connection->getName();
            }
            $query->with($with);
        }

        return $query->get();
    }

    protected function applyFilter(Builder $query, Filter $filter): void
    {
        $table = $query->getModel()->getTable();
        $field = sprintf('%s.%s', $table, $filter->getField());
        $operator = $filter->getOperator();
        $value = $filter->getValue();
        $method = 'where';
        $clauseOperator = null;
        $databaseField = null;

        switch ($operator) {
            case 'ct':
                $value = '%' . $value . '%';
                $clauseOperator = 'LIKE';
                break;
            case 'sw':
                $value = $value . '%';
                $clauseOperator = 'LIKE';
                break;
            case 'ew':
                $value = '%' . $value;
                $clauseOperator = 'LIKE';
                break;
            case 'eq':
            default:
                $clauseOperator = '=';
                break;
            case 'ne':
                $clauseOperator = '!=';
                break;
            case 'gt':
                $clauseOperator = '>';
                break;
            case 'ge':
                $clauseOperator = '>=';
                break;
            case 'lt':
                $clauseOperator = '<';
                break;
            case 'le':
                $clauseOperator = '<=';
                break;
        }

        if ($operator === 'in') {
            call_user_func_array([$query, 'whereIn'], [
                $field, explode('|', $value)
            ]);
        } else {
            call_user_func_array([$query, $method], [
                $field, $clauseOperator, $value
            ]);
        }
    }

    protected function applySort(Builder $query, Sort $sort)
    {
        $query->orderBy($sort->getField(), $sort->getDirection());
    }
}
