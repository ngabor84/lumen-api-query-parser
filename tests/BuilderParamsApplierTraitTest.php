<?php

namespace Test;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use LumenApiQueryParser\BuilderParamsApplierTrait;
use LumenApiQueryParser\Params\Connection;
use LumenApiQueryParser\Params\Filter;
use LumenApiQueryParser\Params\Pagination;
use LumenApiQueryParser\Params\RequestParams;
use LumenApiQueryParser\Params\Sort;
use Mockery;
use PHPUnit\Framework\TestCase;

class BuilderParamsApplierTraitTest extends TestCase
{
    use BuilderParamsApplierTrait;

    /**
     * @test
     */
    public function parametersAppliedCorrectly()
    {
        $mock = Mockery::mock(Builder::class);
        $mock->shouldReceive('with')->once()->with(Mockery::mustBe([
            'children1', 'children2'
        ]));
        $mock->shouldReceive('orderBy')->once()->with('property', 'ASC');
        $mock->shouldReceive('limit')->once()->with(20);
        $mock->shouldReceive('offset')->once()->with(40);
        $mock->shouldReceive('where')->times(3);
        $modelMock = Mockery::mock(Model::class);
        $modelMock->shouldReceive('getTable')->times(3)->andReturn('user');
        $mock->shouldReceive('getModel')->times(3)->andReturn($modelMock);
        $mock->shouldReceive('count')->andReturn(1);
        $mock->shouldReceive('paginate')->andReturn(new LengthAwarePaginator(collect(['Test']), 1, 1, 1));

        $params = new RequestParams();
        $params->addConnection(new Connection('children1'));
        $params->addConnection(new Connection('children2'));
        $params->addFilter(new Filter('name', 'eq', 'foo'));
        $params->addFilter(new Filter('name', 'ct', 'bar'));
        $params->addFilter(new Filter('name', 'ne', 'baz'));
        $params->addSort(new Sort('property'));
        $params->addPagination(new Pagination(20, 2));

        $this->assertInstanceOf(LengthAwarePaginator::class ,$this->applyParams($mock, $params));
    }

    /**
     * @test
     */
    public function noParamersApplied()
    {
        $mock = Mockery::mock(Builder::class);
        $mock->shouldNotReceive('orderBy');
        $mock->shouldNotReceive('limit');
        $mock->shouldNotReceive('offset');
        $mock->shouldNotReceive('where');
        $mock->shouldReceive('count')->andReturn(0);
        $mock->shouldReceive('paginate')->andReturn(new LengthAwarePaginator(collect([]), 0, 1, 1));

        $this->assertInstanceOf(LengthAwarePaginator::class ,$this->applyParams($mock, new RequestParams()));
    }
}