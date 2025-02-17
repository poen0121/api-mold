<?php

namespace App\Console\Commands\Make\System\Parameter;

use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class Document extends GeneratorCommand
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:sp-document';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a system parameter document language file';

    /**
     * The base name used to create the file.
     *
     * @var string
     */
    protected $basename = 'document';

    /**
     * Execute the console command.
     *
     * @return bool|null
     */
    public function handle()
    {
        try {
            $name = $this->getNameInput();

            /* Check format */
            if (! preg_match('/^[a-z0-9_]*$/i', $name)) {
                $this->error('The entered parameter name uses only characters such as ( a ~ z 0 ~ 9 _ ) .');
                $this->error('System parameter document creation failed.');
                return false;
            }
            /* Check length */
            if (strlen($name) > 128) {
                $this->error('The parameter name entered must not exceed 128 bytes!');
                $this->error('System parameter document creation failed.');
                return false;
            }

            $path = $this->getPath($name);

            // First we will check to see if the file already exists. If it does, we don't want
            // to create the class and overwrite the user's code. So, we will bail out so the
            // code is untouched. Otherwise, we will continue generating this file.
            if ((! $this->hasOption('force') || ! $this->option('force')) && $this->files->exists($path)) {
                $this->error('System parameter document already exists!');

                return false;
            }

            // Next, we will generate the path to the location where this class' file should get
            // written. Then, we will build the class and make the proper replacements on the
            // stub files so that it gets the correctly formatted namespace and class name.
            $this->makeDirectory($path);

            $this->files->put($path, $this->buildClass($name));

            $this->info('System parameter document created successfully.');
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
        return base_path('resources' . DIRECTORY_SEPARATOR . 'stubs' . DIRECTORY_SEPARATOR . 'SystemParameterConverter.stub');
    }

    /**
     * Get the destination class path.
     *
     * @param string $name
     * @return string
     */
    protected function getPath($name)
    {
        return base_path('resources' . DIRECTORY_SEPARATOR . 'lang' . DIRECTORY_SEPARATOR . config('app.locale', 'en') . DIRECTORY_SEPARATOR . 'system' . DIRECTORY_SEPARATOR . 'parameters' . DIRECTORY_SEPARATOR . $name . DIRECTORY_SEPARATOR . $this->basename . '.php');
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
                'Create the document even if the file already exists'
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
                'The name of the system parameter name'
            ]
        ];
    }
}
