<?php

namespace Test;

use Laravel\Lumen\Application;
use LumenApiQueryParser\RequestParams;
use LumenApiQueryParser\RequestQueryParserProvider;
use LumenApiQueryParser\ResourceQueryParserTrait;
use Illuminate\Http\Request;

class ResourceQueryParserTraitTest extends AbstractQueryParserTest
{
    use ResourceQueryParserTrait;

    protected $application;

    public function setup(): void
    {
        parent::setUp();
        $this->application = $this->createApplication();
    }

    protected function createApplication(): Application
    {
        $app =new Application();

        $app->register(RequestQueryParserProvider::class);

        return $app;
    }

    /**
     * @test
     */
    public function parseWithoutParameters(): void
    {
        $request = new Request([]);
        $parsedParameters = $this->parseQueryParams($request);
        $expectedParameters = new RequestParams();

        $this->assertEquals($expectedParameters, $parsedParameters);
    }

    /**
     * @test
     * @dataProvider goodRequests
     */
    public function parseWithParameters(Request $request, array $expected): void
    {
        $parsedParams = $this->parseQueryParams($request);
        $expectedParams = $this->createRequestParams($expected['filters'], $expected['sorts'], $expected['limit'], $expected['page'], $expected['connections']);

        $this->assertEquals($expectedParams, $parsedParams);
    }

    /**
     * @test
     * @dataProvider badRequests
     */
    public function parseWithWrongParameters(Request $request, string $expectedException): void
    {
        $this->expectException($expectedException);

        $this->parseQueryParams($request);
    }
}
