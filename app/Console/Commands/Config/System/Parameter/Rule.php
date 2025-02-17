<?php

namespace App\Console\Commands\Config\System\Parameter;

use Illuminate\Console\Command;
use File;

class Rule extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'config:add-sp-rule {name : The name of the system parameter name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Insert system parameter validator rule';

    /**
     * The placeholder for generating
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
            $name = $this->argument('name');

            /* Check format */
            if (! preg_match('/^[a-z0-9_]*$/i', $name)) {
                $this->error('The entered parameter name uses only characters such as ( a ~ z 0 ~ 9 _ ) .');
                $this->error('System parameter validator rule insertion failed.');
                return false;
            }
            /* Check length */
            if (strlen($name) > 128) {
                $this->error('The parameter name entered must not exceed 128 bytes!');
                $this->error('System parameter validator rule insertion failed.');
                return false;
            }

            $list = config('sp.rules', []);
            // Check data
            if (! isset($list[$name])) {
                // Add the provider generating to the profile
                $content = File::get($this->getConfigPath());

                $append = '\'' . $name . '\' => [' . PHP_EOL;
                $append .= '            \'required\',' . PHP_EOL;
                $append .= '        ],' . PHP_EOL;
                $append .= '        ' . $this->generatePlaceholder;

                $content = str_replace($this->generatePlaceholder, $append, $content);

                File::put($this->getConfigPath(), $content);
            } else {
                $this->line('System parameter validator rule \'' . $name . '\' is configured!', 'fg=red;bg=cyan');
            }
            $this->info('System parameter validator rule configuration insert successfully.');
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
        return base_path('config' . DIRECTORY_SEPARATOR . 'sp.php');
    }
}
