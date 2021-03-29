<?php


namespace Futurfuturfuturfutur\Duckduckduck\Services\Format;


use Illuminate\Support\Facades\File;

abstract class FormatServiceAbstract
{
    protected array $params;
    protected array $call;

    protected string  $configPath;
    protected array $config;

    protected array $method;

    public function __construct()
    {
        $this->generateConfigPath();
    }

    public function save(array $params, array $call)
    {
        $this->params = $params;
        $this->call = $call;

        $this->getConfig();
        $this->getMethod();

        $this->getResponse();
        $this->getBodyParameters();
        $this->getPathParameters();
        $this->getQueryParameters();

        $this->setMethod();
        $this->updateConfig($this->config);
    }

    public function resetConfig()
    {
        File::delete($this->configPath);
    }

    private function updateConfig($data)
    {
        File::put($this->configPath, json_encode($data));
    }

    private function getConfig()
    {
        if(!File::exists($this->configPath)){
            $template = $this->getConfigTemplate();
            $this->updateConfig($template);
        }

        $this->config = json_decode(File::get($this->configPath), true);
    }

    private function setMethod()
    {
        $this->config['paths']['/' . $this->call['path']][strtolower($this->call['method'])] = $this->method;
    }

    private function getMethod()
    {
        if (!isset($this->config['paths']['/' . $this->call['path']][strtolower($this->call['method'])])) {
            $this->method = $this->getMethodTemplate();
        }else{
            $this->method = $this->config['paths']['/' . $this->call['path']][strtolower($this->call['method'])];
        }
    }

    protected abstract function generateConfigPath();

    protected abstract function getConfigTemplate();

    protected abstract function getMethodTemplate();

    protected abstract function getResponse();

    protected abstract function getBodyParameters();

    protected abstract function getPathParameters();

    protected abstract function getQueryParameters();
}
