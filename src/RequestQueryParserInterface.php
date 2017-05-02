<?php

namespace LumenApiQueryParser;

use Illuminate\Http\Request;
use LumenApiQueryParser\Params\RequestParamsInterface;

interface RequestQueryParserInterface
{
    public function parse(Request $request): RequestParamsInterface;
}
