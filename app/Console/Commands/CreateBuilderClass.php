<?php

namespace App\Console\Commands;

use Illuminate\Console\GeneratorCommand;

class CreateBuilderClass extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:builder {name} {--model=} {--relation=relation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a builder class.';

    protected function getStub()
    {
        return storage_path('stubs\builder\builder.stub');
    }

    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\QueryBuilders';
    }

    protected function replaceClass($stub, $name)
    {
        $model = $this->option('model') ?: str_replace('Builder', '', class_basename($name));
        $relation = $this->option('relation');

        $stub = parent::replaceClass($stub, $name);
        $stub = str_replace('{{relation}}', $relation, $stub);
        $stub = str_replace('{{model}}', $model, $stub);

        return $stub;
    }
}
