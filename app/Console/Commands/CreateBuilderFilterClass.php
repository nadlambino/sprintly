<?php

namespace App\Console\Commands;

use Illuminate\Console\GeneratorCommand;

class CreateBuilderFilterClass extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:builder-filter {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new builder filter class.';

    protected function getStub()
    {
        return storage_path('stubs\builder\filter.stub');
    }

    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\QueryBuilders';
    }
}
