<?php

namespace Futurfuturfuturfutur\Duckduckduck\Commands;

use Futurfuturfuturfutur\Duckduckduck\Services\Format\FormatServiceInterface;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Symfony\Component\Process\Process;

class GenerateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'duckduckduck:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate API documentation';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(FormatServiceInterface $formatService)
    {
        $this->info('Preparing before tests..');
        $this->beforeTests($formatService);
        $this->info('Running PHPUnit tests..');

        $process = new Process(['php', 'artisan', 'test', '--testsuite=Feature']);
        $process->run();

        $this->afterTests();
        $this->info('API documentation successfully auto generated!');
    }

    private function beforeTests($formatService)
    {
        File::put(base_path('duckduckduck/.duckduckduck.cache'), '{}');
        $formatService->resetConfig();
    }

    private function afterTests()
    {
        File::delete(base_path('duckduckduck/.duckduckduck.cache'));
    }
}
