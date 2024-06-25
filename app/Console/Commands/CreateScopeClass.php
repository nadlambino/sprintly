<?php

namespace App\Console\Commands;

use Illuminate\Console\GeneratorCommand;

class CreateScopeClass extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:scope {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new model scope class.';

    public function handle()
    {
        if (parent::handle() === false) {
            return false;
        }

        $this->addTraitToModel();
    }

    protected function getStub(): string
    {
        return storage_path('stubs\model\scope.stub');
    }

    protected function getDefaultNamespace($rootNamespace): string
    {
        return $rootNamespace . '\Models\Scopes';
    }

    protected function getNameInput(): string
    {
        $name = $this->argument('name');

        return match (true) {
            str_ends_with($name, 'Scopes') => $name,
            str_ends_with($name, 'Scope') => $name . 's',
            default => $name . 'Scopes',
        };
    }

    protected function addTraitToModel(): bool
    {
        $originalModelContent = null;

        try {
            $trait = $this->getNameInput();
            $model = str_replace(['Scope', 'Scopes'], '', class_basename($trait));
            $modelPath = app_path("Models/{$model}.php");

            if (! file_exists($modelPath)) {
                return throw new \Exception("{$model} model does not exist.");
            }

            $fileContent = file_get_contents($modelPath);
            $originalModelContent = $fileContent;
            $namespace = $this->getDefaultNamespace(trim($this->rootNamespace(), '\\')) . "\\{$trait}";
            $useNamespaceStatement = "use {$namespace};";
            $useTraitStatement = "use {$trait};";

            // Add the namespace import statement if not already present
            if (! str_contains($fileContent, $useNamespaceStatement)) {
                $fileContent = $this->insertUseNamespaceStatement($fileContent, $useNamespaceStatement);
            }

            // Add the trait use statement within the class if not already present
            if (! str_contains($fileContent, $useTraitStatement)) {
                $fileContent = $this->insertUseTraitStatement($fileContent, $useTraitStatement);
            }

            $this->files->put($modelPath, $this->sortImports($fileContent));
            $this->info("{$trait} is added to the {$model} model.");

            return true;
        } catch (\Exception $exception) {
            $this->rollbackModelChanges($modelPath, $originalModelContent);
            $this->deleteCreatedTrait();
            $this->error($exception->getMessage() . $this->getNameInput() . ' was deleted.');

            return false;
        }
    }

    protected function insertUseNamespaceStatement($fileContent, $useNamespaceStatement): string
    {
        // Find the last use statement and insert the new use statement after it
        $pattern = '/^use [^;]+;/m';
        if (preg_match_all($pattern, $fileContent, $matches, PREG_OFFSET_CAPTURE)) {
            $lastUseStatement = end($matches[0]);
            $position = $lastUseStatement[1] + strlen($lastUseStatement[0]);
            $fileContent = substr_replace($fileContent, "\n{$useNamespaceStatement}", $position, 0);
        } else {
            // If no use statements found, insert after the opening PHP tag
            $fileContent = preg_replace('/^<\?php/m', "<?php\n\n{$useNamespaceStatement}", $fileContent, 1);
        }

        return $fileContent;
    }

    protected function insertUseTraitStatement($fileContent, $useTraitStatement): string
    {
        // Find the class definition
        $pattern = '/class\s+\w+\s+extends\s+\w+/';
        if (preg_match($pattern, $fileContent, $matches, PREG_OFFSET_CAPTURE)) {
            $classPosition = $matches[0][1];
            // Find the position of the opening brace of the class
            $classBodyPosition = strpos($fileContent, '{', $classPosition) + 1;
            $fileContent = substr_replace($fileContent, "\n    {$useTraitStatement}", $classBodyPosition, 0);
        }

        return $fileContent;
    }

    protected function rollbackModelChanges(string $path, string|null $content): void
    {
        if ($content) {
            $this->files->put($path, $content);
        }
    }

    protected function deleteCreatedTrait(): void
    {
        $name = $this->qualifyClass($this->getNameInput());
        $path = $this->getPath($name);
        $this->files->delete($path);
    }
}
