<?php

namespace App\Console\Commands\Config\Sms;

use Illuminate\Console\Command;
use Str;
use File;

class Source extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'config:add-sms-source {name : The name is the entitie SMS source model class}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Insert SMS source basic configuration class';

    /**
     * The placeholder for source generating
     *
     * @var string
     */
    public $generatePlaceholder = '//:end-source-generating:';

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
            
            $list = config('sms.sourceables', []);

            $code = $this->getSourceCode($name);
            // Check data
            if (! isset($list[$code])) {
                // Add the provider generating to the profile
                $content = File::get($this->getConfigPath());

                $append = '\'' . $code . '\' => [' . PHP_EOL;
                $append .= '            \'status\' => true,' . PHP_EOL;
                $append .= '            \'model\' => ' . $name . '::class' . PHP_EOL;
                $append .= '        ],' . PHP_EOL;
                $append .= '        ' . $this->generatePlaceholder;

                $content = str_replace($this->generatePlaceholder, $append, $content);

                File::put($this->getConfigPath(), $content);
            } else {
                $this->line('SMS source \'' . $code . '\' is configured!', 'fg=red;bg=cyan');
            }
            $this->info('SMS source configuration insert successfully.');
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
        return base_path('config' . DIRECTORY_SEPARATOR . 'sms.php');
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
     * Get the source code.
     *
     * @param string $name
     * @return string
     */
    public function getSourceCode($name)
    {
        return Str::snake(str_replace('\\', '', Str::replaceFirst('App\\Entities\\', '', $name)));
    }
}
