<?php

namespace Futurfuturfuturfutur\Duckduckduck\Services\Format;

use Illuminate\Support\Facades\Config;

class SwaggerFormatService extends FormatServiceAbstract implements FormatServiceInterface
{
    protected const DEFAULT = 'Empty';
    protected const CONFIG_NAME = 'swagger.json';

    protected array $params;
    protected array $call;

    protected string $configPath;
    protected array $config;

    protected array $method;

    protected function generateConfigPath()
    {
        $this->configPath = base_path('duckduckduck/' . self::CONFIG_NAME);
    }

    protected function getConfigTemplate()
    {
        return [
            'openapi' => '3.0.0',
            'servers' => [
                [
                    'url' => $this->call['server']['SERVER_NAME'] .
                    (Config::get('duckduckduck.public_port') != '80' ? ":" . Config::get('duckduckduck.public_port') : "")
                ]
            ],
            'info' => [
                'description' => Config::get('duckduckduck.description'),
                'version' => Config::get('duckduckduck.version'),
                "title" => Config::get('duckduckduck.title'),
            ],
            'paths' => [],
        ];
    }

    protected function getMethodTemplate()
    {
        return [
            'tags' => [
                isset($this->params['group']) ? $this->params['group'] : 'Default'
            ],
            'description' => isset($this->params['description']) ? $this->params['description'] : self::DEFAULT,
            'parameters' => [],
            'responses' => [],
        ];
    }

    protected function getResponse()
    {
        $contentType = $this->call['headers']['accept'][0];
        if (!isset($this->method['responses'][intval($this->call['code'])]['content'][$contentType]['examples'])) {
            $this->method['responses'][intval($this->call['code'])] = [
                'description' => isset($this->call['duckduckduck']['description']) ? $this->call['duckduckduck']['description'] : self::DEFAULT,
                'content' => [
                    $contentType => [
                        'schema' => [
                            'type' => 'object'
                        ],
                        'examples' => [
                            'Example 1' => [
                                'value' => $this->call['payload'],
                            ]
                        ]
                    ]
                ],
            ];
        }else{
            $exampleName = 'Example ' . (count($this->method['responses'][intval($this->call['code'])]['content'][$contentType]['examples']) + 1);
            $this->method['responses'][intval($this->call['code'])]['content'][$contentType]['examples'][$exampleName] = [
                'value' => $this->call['payload'],
            ];
        }
    }

    protected function getBodyParameters()
    {
        if(preg_match('/20[01]/', $this->call['code']) && !empty($this->call['body_params'])){
            if(!isset($this->method['requestBody']['content'])){
                $this->method['requestBody'] = [
                    'content' => [
                        'Example 1' => [
                            'schema' => [
                                'type' => 'object',
                                'example' => json_encode($this->call['body_params'])
                            ]
                        ]
                    ]
                ];
            }else{
                $exampleName = 'Example ' . (count($this->method['requestBody']['content']) + 1);
                $this->method['requestBody']['content'][$exampleName] = [
                    'schema' => [
                        'type' => 'object',
                        'example' => json_encode($this->call['body_params'])
                    ]
                ];
            }
        }
    }

    protected function getPathParameters()
    {
        if(!empty($this->call['path_params'])){
            foreach ($this->call['path_params'] as $pathParam => $example){
                if(strpos($this->call['path'], $pathParam) !== false &&
                    (array_search($pathParam, array_column($this->method['parameters'], 'name')) === false ||
                    array_search('path', array_column($this->method['parameters'], 'in')) === false)){
                    $this->method['parameters'][] = [
                        'in' => 'path',
                        'name' => $pathParam,
                        'required' => true,
                        'schema' => [
                            'type' => 'string',
                            'example' => gettype($example) === 'array' ? $example['id'] : $example
                        ]
                    ];
                }
            }
        }
    }

    protected function getQueryParameters()
    {
        foreach ($this->call['query_params'] as $queryParam => $example){
            if(array_search($queryParam, array_column($this->method['parameters'], 'name')) === false ||
                array_search('query', array_column($this->method['parameters'], 'in')) === false) {
                $this->method['parameters'][] = [
                    'in' => 'query',
                    'name' => $queryParam,
                    'required' => true,
                    'schema' => [
                        'type' => 'string',
                        'example' => $example
                    ]
                ];
            }
        }
    }
}
