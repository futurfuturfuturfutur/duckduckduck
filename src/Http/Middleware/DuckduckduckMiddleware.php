<?php

namespace Futurfuturfuturfutur\Duckduckduck\Http\Middleware;

use Closure;
use Futurfuturfuturfutur\Duckduckduck\Services\DuckduckduckService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File;

class DuckduckduckMiddleware
{
    /**
     * @var DuckduckduckService
     */
    private DuckduckduckService $duckduckduckService;

    public function __construct(DuckduckduckService $duckduckduckService)
    {
        $this->duckduckduckService = $duckduckduckService;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if(App::runningInConsole() && File::exists(base_path('duckduckduck/.duckduckduck.cache')))
            $this->duckduckduckService->parse($request, $response);

        return $response;
    }
}
