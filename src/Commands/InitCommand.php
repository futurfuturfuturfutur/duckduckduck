<?php

namespace Futurfuturfuturfutur\Duckduckduck\Commands;

use Futurfuturfuturfutur\Duckduckduck\DuckduckduckServiceProvider;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;

class InitCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'duckduckduck:init';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Initialize package';

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
    public function handle()
    {
        $this->info('Initializing DuckDuckDuck.');

        $this->call('vendor:publish', [
            '--provider' => DuckduckduckServiceProvider::class,
            '--tag' => 'config'
        ]);

        $this->call('vendor:publish', [
            '--provider' => DuckduckduckServiceProvider::class,
            '--tag' => 'package-dir'
        ]);

        $this->line('Package successfully initialized.');
        $this->info('Root directory /duckduckduck was created with initial example documentations in it.');
        $this->info('Duckduckduck config file was created inside the default /config folder.');
    }
}
