<?php

namespace App\Console\Commands\Config\Verifier;

use Illuminate\Console\Command;
use Str;
use File;

class Provider extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'config:add-verifier {name : The name is the verifier source class}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Insert verifier basic configuration class for the validator';

    /**
     * The placeholder for verifier generating
     *
     * @var string
     */
    public $generatePlaceholder = '//:end-verifier-generating:';

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

            // Add verifier generating to the profile providers
            $content = File::get($this->getConfigPath());

            $append = '\\Validator::extend(\'' . $this->getVerifierCode($name) . '\', \'' . $name . '@validate\');'. PHP_EOL;
            $append .= '        ' . $this->generatePlaceholder;
            
            $content = str_replace($this->generatePlaceholder, $append, $content);

            File::put($this->getConfigPath(), $content);

            $this->info('Verifier provider configuration insert successfully.');
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
     * Get the verifier code.
     *
     * @param string $name
     * @return string
     */
    public function getVerifierCode($name)
    {
        return Str::snake(str_replace('\\', '', Str::replaceFirst('App\\Libraries\\Verifiers\\', '', $name)));
    }
}
