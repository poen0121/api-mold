<?php

namespace App\Console\Commands\Make\Exception;

use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class Converter extends GeneratorCommand
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:ex-converter';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new exception converter language file';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Exception';

    /**
     * The base name used to create the file.
     *
     * @var string
     */
    protected $basename = 'converter';

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

            if ($this->hasOption('rely') && $this->option('rely')) {
                $name =  $name . (preg_match('/.*' . $this->type . '$/', $name) ? 'Code' : (preg_match('/.*ExceptionCode$/', $name) ? '' : 'ExceptionCode'));
            }

            $path = $this->getPath($name);

            // First we will check to see if the class already exists. If it does, we don't want
            // to create the class and overwrite the user's code. So, we will bail out so the
            // code is untouched. Otherwise, we will continue generating this class' files.
            if ((! $this->hasOption('force') || ! $this->option('force')) && $this->alreadyExists($name)) {
                $this->error($this->type . ' converter language already exists!');

                return false;
            }

            // Next, we will generate the path to the location where this class' file should get
            // written. Then, we will build the class and make the proper replacements on the
            // stub files so that it gets the correctly formatted namespace and class name.
            $this->makeDirectory($path);
            
            $this->files->put($path, $this->buildClass($name));

            if (! $this->hasOption('unconf') || ! $this->option('unconf')) {
                // Insert exception to the profile
                $command = [];
                $command['name'] = $name;
                $this->call('config:add-exception', $command);
            }

            $this->info($this->type . ' converter language created successfully.');
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
     * Parse the class name and format.
     *
     * @param string $name
     * @return string
     */
    protected function qualifyClass($name)
    {
        $name = ltrim($name, '\\/');

        return str_replace('/', '\\', $name);
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return base_path('resources' . DIRECTORY_SEPARATOR . 'stubs' . DIRECTORY_SEPARATOR . ($this->hasOption('rely') && $this->option('rely') ? 'ExceptionCodeConverter.stub' : 'ExceptionConverter.stub'));
    }

    /**
     * Get the destination class path.
     *
     * @param string $name
     * @return string
     */
    protected function getPath($name)
    {
        return base_path('resources' . DIRECTORY_SEPARATOR . 'lang' . DIRECTORY_SEPARATOR . config('app.locale', 'en') . DIRECTORY_SEPARATOR . 'exception' . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $name) . DIRECTORY_SEPARATOR . $this->basename . '.php');
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
        $class = str_replace($this->getNamespace($name), '', $name);
        
        return str_replace('DummyClass', $class, $stub);
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
                'rely',
                null,
                InputOption::VALUE_NONE,
                'Indicates the source of the ExceptionCode object'
            ],
            [
                'unconf',
                null,
                InputOption::VALUE_NONE,
                'Code is not restricted by exception configuration control'
            ]
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
                'The name is the exception source class'
            ]
        ];
    }
}
