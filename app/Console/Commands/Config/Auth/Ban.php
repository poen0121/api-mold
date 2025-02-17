<?php

namespace App\Console\Commands\Config\Auth;

use Illuminate\Console\Command;
use File;

class Ban extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'config:add-ban-service {name? : The name is the ban service name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Insert auth guard ban service basic configuration';

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
            $list = config('ban.release');

            if (! is_array($list)) {
                $this->error('Ban service configuration insert failed.');
            } else {
                 /* Input name */
                 if (!($name = $this->argument('name'))) {
                    $this->comment('The name of ban service uses only characters such as ( a ~ z 0 ~ 9 _ ) .');
                    /* Ask name */
                    $name = $this->ask('What is the name of the ban service?');
                }
                /* Check empty */
                if (! isset($name[0])) {
                    $this->question('The entered ban service name cannot be empty!');
                    $this->error('Ban service configuration insert failed.');
                    return;
                }
                /* Check format */
                if (! preg_match('/^[a-z0-9_]*$/i', $name)) {
                    $this->question('The entered ban service name uses only characters such as ( a ~ z 0 ~ 9 _ ) .');
                    $this->error('Ban service `' . $name . '` configuration insert failed.');
                    return;
                }
                // Add the provider generating to the profile
                $content = File::get($this->getConfigPath());

                $append = count($list)  . ' => [' . PHP_EOL;
                $append .= '            \'description\' => \'' . $name . '\',' . PHP_EOL;
                $append .= '            \'restrict_access_guards\' => [],' . PHP_EOL;
                $append .= '            \'unique_auth_ignore_guards\' => [' . PHP_EOL;
                $append .= '                \'client\',' . PHP_EOL;
                $append .= '            ],' . PHP_EOL;
                $append .= '            \'unique_auth_inherit_login_guards\' => [],' . PHP_EOL;
                $append .= '            \'status\' => true,' . PHP_EOL;
                $append .= '            \'allow_named\' => [],' . PHP_EOL;
                $append .= '            \'unallow_named\' => []' . PHP_EOL;
                $append .= '        ],' . PHP_EOL;
                $append .= '        ' . $this->generatePlaceholder;

                $content = str_replace($this->generatePlaceholder, $append, $content);

                File::put($this->getConfigPath(), $content);

                $this->info('Ban service configuration insert successfully.');
            }
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
        return base_path('config' . DIRECTORY_SEPARATOR . 'ban.php');
    }
}