<?php

namespace App\Console\Commands\Config\Auth;

use Illuminate\Console\Command;
use File;
use Str;

class Observer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'service:add-auth-observer {name : The name is the entitie auth model class}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Insert auth guard observer basic configuration class';

    /**
     * The placeholder for observer generating
     *
     * @var string
     */
    public $generatePlaceholder = '//:end-auth-observer-generating:';

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

            // Add auth guard observer generating to the profile
            $content = File::get($this->getConfigPath());

            $append = '\\App\\Entities\\' . $this->getOriginalName($name) . '::observe(\\App\\Observers\\' . $this->getOriginalName($name) . 'Observer::class);' . PHP_EOL;
            $append .= '        ' . $this->generatePlaceholder;
 
            $content = str_replace($this->generatePlaceholder, $append, $content);

            File::put($this->getConfigPath(), $content);

            $this->info('Service auth observer configuration insert successfully.');
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
        return base_path('app' . DIRECTORY_SEPARATOR . 'Providers' . DIRECTORY_SEPARATOR . 'AppServiceProvider.php');
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
     * Get the original name.
     *
     * @param string $name
     * @return string
     */
    public function getOriginalName($name)
    {
        return Str::replaceFirst('App\\Entities\\', '', $name);
    }
}
