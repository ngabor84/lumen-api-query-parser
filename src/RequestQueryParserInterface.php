<?php

namespace LumenApiQueryParser;

use Illuminate\Http\Request;

interface RequestQueryParserInterface
{
    public function parse(Request $request): RequestParamsInterface;
}
