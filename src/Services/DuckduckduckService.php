<?php

namespace Futurfuturfuturfutur\Duckduckduck\Services;

use Futurfuturfuturfutur\Duckduckduck\Services\Request\RequestService;
use Futurfuturfuturfutur\Duckduckduck\Services\Response\ResponseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Symfony\Component\HttpFoundation\Response;

class DuckduckduckService
{

    public function parse(Request $request, Response $response)
    {
        $requestFormat = new RequestService($request);
        $responseFormat = new ResponseService($response);

        $call = $this->formatCall($requestFormat, $responseFormat);
        $this->save($call);
    }

    private function formatCall(RequestService $request, ResponseService $response)
    {
        return [
            'server' => $request->getServer(),
            'path' => $request->getPath(),
            'headers' => $request->getHeaders(),
            'method' => $request->getMethod(),
            'body_params' => $request->getBodyParams(),
            'query_params' => $request->getQueryParams(),
            'path_params' => $request->getPathParams(),
            'code' => $response->getStatusCode(),
            'payload' => $response->getPayload(),
        ];
    }

    private function save($call)
    {
        File::put(base_path('duckduckduck/.duckduckduck.cache'), json_encode($call));
    }

}
