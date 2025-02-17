<?php

namespace App\Console\Commands\Config;

use Illuminate\Console\Command;
use File;

class Exception extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'config:add-exception {name : The name is the exception error code source class}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Insert exception error code basic configuration class';

    /**
     * The placeholder for feature generating
     *
     * @var string
     */
    public $generatePlaceholder = '//:end-generating:';

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
     * @return mixed
     */
    public function handle()
    {
        try {
            /* Input name */
            $name = $this->qualifyClass($this->argument('name'));

            $name = ucwords($name, '\\/');

            $list = config('exception', []);
            // Check data
            if (! isset($list[$name])) {
                // Add exception error code to the profile
                $content = File::get($this->getConfigPath());
        
                $append = $name . '::class => [' . PHP_EOL;
                $append .= '        0 => 0,' . PHP_EOL;
                $append .= '    ],' . PHP_EOL;
                $append .= '    ' . $this->generatePlaceholder;

                $content = str_replace($this->generatePlaceholder, $append, $content);

                File::put($this->getConfigPath(), $content);
            } else {
                $this->line('Exception error code source class \'' . $name . '\' is configured!', 'fg=red;bg=cyan');
            }
            $this->info('Exception error code source class configuration insert successfully.');
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
     * Get config path for generated file.
     *
     * @return string
     */
    public function getConfigPath()
    {
        return base_path('config' . DIRECTORY_SEPARATOR . 'exception.php');
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
}
