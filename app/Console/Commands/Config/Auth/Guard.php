<?php

namespace App\Console\Commands\Config\Auth;

use Illuminate\Console\Command;
use Str;
use File;

class Guard extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'config:add-auth-guard {name : The name is the entitie auth model class}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Insert auth guard provider basic configuration class';

    /**
     * The placeholder for guard generating
     *
     * @var string
     */
    public $generateGuardPlaceholder = '//:end-guard-generating:';

    /**
     * The placeholder for provider generating
     *
     * @var string
     */
    public $generateProviderPlaceholder = '//:end-provider-generating:';

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

            $guards = config('auth.guards', []);

            $providers = config('auth.providers', []);

            $guard = $this->getGuardCode($name);

            $provider = $this->getProviderCode($name);
            // Check data
            if (! isset($guards[$guard]) || ! isset($providers[$provider])) {
                $content = File::get($this->getConfigPath());
            }
            // Add the guard generating to the profile
            if (! isset($guards[$guard])) {
                $append = '\'' . $guard . '\' => [' . PHP_EOL;
                $append .= '            \'driver\' => \'jwt\',' . PHP_EOL;
                $append .= '            \'provider\' => \'' . $provider . '\',' . PHP_EOL;
                $append .= '            \'jwt_ttl\' => env(\'JWT_TTL\', 60),' . PHP_EOL;
                $append .= '            \'jwt_refresh_ttl\' => env(\'JWT_REFRESH_TTL\', 20160),' . PHP_EOL;
                $append .= '            \'uts_ttl\' => env(\'UTS_TTL\', 3),' . PHP_EOL;
                $append .= '            \'login_rule\' => [' . PHP_EOL;
                $append .= '               \'account\' => \'required|between:1,128\',' . PHP_EOL;
                $append .= '               \'password\' => \'required|between:8,16\'' . PHP_EOL;
                $append .= '            ]' . PHP_EOL;
                $append .= '        ],' . PHP_EOL;
                $append .= '        ' . $this->generateGuardPlaceholder;

                $content = str_replace($this->generateGuardPlaceholder, $append, $content);
            } else {
                $this->line('Auth guard \'' . $guard . '\' is configured!', 'fg=red;bg=cyan');
            }
            // Add the provider generating to the profile
            if (! isset($providers[$provider])) {
                $append = '\'' . $this->getProviderCode($name) . '\' => [' . PHP_EOL;
                $append .= '            \'driver\' => \'eloquent\',' . PHP_EOL;
                $append .= '            \'model\' => ' . $name . '::class' . PHP_EOL;
                $append .= '        ],' . PHP_EOL;
                $append .= '        ' . $this->generateProviderPlaceholder;

                $content = str_replace($this->generateProviderPlaceholder, $append, $content);
            } else {
                $this->line('Auth provider \'' . $provider . '\' is configured!', 'fg=red;bg=cyan');
            }
            // Check data
            if (! isset($guards[$guard]) || ! isset($providers[$provider])) {
                File::put($this->getConfigPath(), $content);
            }
            $this->info('Auth guard and provider configuration insert successfully.');
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
        return base_path('config' . DIRECTORY_SEPARATOR . 'auth.php');
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
     * Get the guard code.
     *
     * @param string $name
     * @return string
     */
    public function getGuardCode($name)
    {
        return Str::replaceFirst('_auth', '', Str::snake(str_replace('\\', '', Str::replaceFirst('App\\Entities\\', '', $name))));
    }

    /**
     * Get the provider code.
     *
     * @param string $name
     * @return string
     */
    public function getProviderCode($name)
    {
        return Str::snake(str_replace('\\', '', Str::replaceFirst('App\\Entities\\', '', $name)));
    }
}