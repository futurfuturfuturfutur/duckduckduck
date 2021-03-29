<?php

namespace Futurfuturfuturfutur\Duckduckduck\Traits;

use Futurfuturfuturfutur\Duckduckduck\Services\Format\FormatServiceInterface;
use Futurfuturfuturfutur\Duckduckduck\Services\PhpDocParserService;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File;

trait DuckduckduckTestCase
{
    protected function tearDown(): void
    {
        if(App::runningInConsole() && File::exists(base_path('.duckduckduck.cache'))) {
            $cache = json_decode(File::get(base_path('.duckduckduck.cache')), true);
            if(!empty($cache)) {
                if (!$this->hasFailed()) {
                    $service = App::make(FormatServiceInterface::class);

                    $testParams = $this->getTestParams();
                    $testCallParams = $this->getTestCallParams();

                    $cache['duckduckduck'] = $testCallParams;
                    $service->save($testParams, $cache);
                }
            }

            File::put(base_path('.duckduckduck.cache'), '{}');
        }

        parent::tearDown();
    }

    private function getTestParams()
    {
        $testClass = get_class($this);
        $testReflection = new \ReflectionClass($testClass);
        return PhpDocParserService::parsePhpDoc($testReflection->getDocComment());
    }

    private function getTestCallParams()
    {
        $testCallTarget = explode('::', $this->provides()[0]->getTarget());
        $testCallReflection = new \ReflectionMethod(get_class($this), $testCallTarget[1]);
        return PhpDocParserService::parsePhpDoc($testCallReflection->getDocComment());
    }
}
