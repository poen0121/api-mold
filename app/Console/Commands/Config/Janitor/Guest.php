<?php

namespace App\Console\Commands\Config\Janitor;

use Illuminate\Console\Command;
use Str;
use File;

class Guest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'config:add-janitor-guest 
    {name : The name is the janitor guest class name} 
    {type? : Custom type code is the janitor guest code} 
    {belong=normal : The name is the category of the janitor} 
    {items? : This value is a list of data preset item names of the janitor and its format is [item=value,item=value,...]}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Insert janitor guest basic configuration class';

    /**
     * The placeholder for source generating
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
            /* Input type code */
            $type = $this->argument('type');
            /* Get type code */
            $type = (isset($type) ? strtolower($type) : $this->getGuestCode($name)); 
            /* Input belong name */
            $belong = strtolower($this->argument('belong'));
            /* Input items */
            $items = $this->argument('items');
            
            $janitor = config('janitor', []);
            // Check data
            if (! isset($janitor[$type])) {
                /* Get the items array string */
                if (isset($items)) {
                    $items = explode(',', $items);
                    $items = collect($items)->map(function ($item) {
                        if ($item) {
                            $offset = strpos($item, '=');
                            $key = ($offset !== false ? substr($item, 0, $offset) : $item);
                            $value = ($offset !== false ? substr($item, $offset + 1) : 'null');
                            /* Replace value format */
                            if (preg_match('/null|false|true|\[\]/i', $value)) {
                               $value = strtolower($value);
                            } elseif (preg_match('/::class$/i', $value)) {
                               $value = preg_replace('/::class$/i', '::class', $value);
                            } else {
                               $value = '\'' . $value . '\'';
                            }
                            return '            \'' . strtr($key, ['\'' => '\\\'']) . '\' => ' . $value . ',' . PHP_EOL;
                        }
                        return null;
                    })->reject(function ($item) {
                        return !isset($item);
                    })->all();

                    $items = implode($items);
                }

                // Add the provider generating to the profile
                $content = File::get($this->getConfigPath());

                $append = '\'' . $type . '\' => [' . PHP_EOL;
                $append .= '        \'belong\' => \'' . $belong . '\',' . PHP_EOL;
                $append .= '        \'status\' => true,' . PHP_EOL;
                $append .= '        \'class\' => ' . $name . '::class,' . PHP_EOL;
                $append .= '        \'data\' => [' . PHP_EOL;
                $append .= $items;
                $append .= '            // You can attach custom item data' . PHP_EOL;
                $append .= '        ]' . PHP_EOL;
                $append .= '    ],' . PHP_EOL;
                $append .= '    ' . $this->generatePlaceholder;

                $content = str_replace($this->generatePlaceholder, $append, $content);

                File::put($this->getConfigPath(), $content);
            } else {
                $this->line('Janitor guest \'' . $type . '\' is configured!', 'fg=red;bg=cyan');
            }

            $this->line('About file : app/Libraries/Instances/Router/Janitor.rd');

            $this->info('Janitor guest configuration insert successfully.');
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
        return base_path('config' . DIRECTORY_SEPARATOR . 'janitor.php');
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
     * Get the guest code.
     *
     * @param string $name
     * @return string
     */
    public function getGuestCode($name)
    {
        return Str::snake(str_replace('\\', '', Str::replaceFirst('App\\', '', $name)));
    }
}

