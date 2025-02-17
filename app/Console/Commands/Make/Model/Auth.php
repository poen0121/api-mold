<?php

namespace App\Console\Commands\Make\Model;

use Str;
use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class Auth extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'make:user-auth';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Eloquent model user Auth class';

    /**
      * The type of class being generated.
      *
      * @var string
      */
    protected $type = 'Auth';

    /**
     * Execute the console command.
     *
     * @return bool|null
     */
    public function handle()
    {
        try {
            $name = $this->qualifyClass($this->getNameInput());

            $name = ucwords($name, '\\/');

            $name .= (preg_match('/.*' . $this->type . '$/', $name) ? '' : $this->type);

            $path = $this->getPath($name);

            // First we will check to see if the class already exists. If it does, we don't want
            // to create the class and overwrite the user's code. So, we will bail out so the
            // code is untouched. Otherwise, we will continue generating this class' files.
            if ((! $this->hasOption('force') || ! $this->option('force')) && $this->alreadyExists($name)) {
                $this->error($this->type . ' already exists!');

                return false;
            }

            // Insert feature to the profile
            if (! $this->hasOption('unregister') || ! $this->option('unregister')) {
                $command = [];
                $command['name'] = $name;
                $this->call('config:add-auth-guard', $command);
            }

            // Make observer
            $command = [];
            $command['name'] = Str::replaceFirst($this->getDefaultNamespace(trim($this->rootNamespace(), '\\')).'\\', '', $name);
            $command['--force'] = ($this->hasOption('force') && $this->option('force') ? true : false);
            if ($this->call('make:user-observer', $command)) {
                // Insert observer to the profile
                $command = [];
                $command['name'] = $name;
                $this->call('service:add-auth-observer', $command);
            }

            // Next, we will generate the path to the location where this class' file should get
            // written. Then, we will build the class and make the proper replacements on the
            // stub files so that it gets the correctly formatted namespace and class name.
            $this->makeDirectory($path);

            $this->files->put($path, $this->buildClass($name));

            $this->info($this->type . ' model created successfully.');
        } catch (\Throwable $th) {
            $this->comment('Error Information :');
            $display = [];
            $display[] = [
                'index' => 'Message',
                'description' => $th->getMessage()
            ];
            $display[] = [
                'index' => 'Type',
                'description' => get_class($th)
            ];
            $display[] = [
                'index' => 'Code',
                'description' => $th->getCode()
            ];
            $display[] = [
                'index' => 'File Path',
                'description' => $th->getFile()
            ];
            $display[] = [
                'index' => 'File Line',
                'description' => $th->getLine()
            ];
            $headers = [
                'Index',
                'Description'
            ];
            $this->table($headers, $display);
            $this->error('Something error happens, please fix them.');
        }
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return base_path('resources' . DIRECTORY_SEPARATOR . 'stubs' . DIRECTORY_SEPARATOR . 'AuthModel.stub');
    }

    /**
     * Get the destination class path.
     *
     * @param string $name
     * @return string
     */
    protected function getPath($name)
    {
        $name = Str::replaceFirst($this->rootNamespace(), '', $name);
        return $this->laravel['path'] . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $name) . '.php';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param string $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\Entities';
    }

    /**
     * Replace the class name for the given stub.
     *
     * @param  string  $stub
     * @param  string  $name
     * @return string
     */
    protected function replaceClass($stub, $name)
    {
        $class = str_replace($this->getNamespace($name).'\\', '', $name);

        $body = str_replace('DummyClass', $class, $stub);

        $table = Str::replaceFirst($this->getDefaultNamespace(trim($this->rootNamespace(), '\\')).'\\', '', $name);

        $table = strtr($table, ['\\' => '']);

        $table = Str::plural(Str::snake($table));

        $body = str_replace('DummyTable', $table, $body);

        return $body;
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            [
                'force',
                null,
                InputOption::VALUE_NONE,
                'Create the class even if the file already exists'
            ],
            [
                'unregister',
                null,
                InputOption::VALUE_NONE,
                'Unregister as a provider'
            ],
        ];
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            [
                'name',
                InputArgument::REQUIRED,
                'The name of the class'
            ]
        ];
    }
}