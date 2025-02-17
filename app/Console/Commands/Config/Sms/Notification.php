<?php

namespace App\Console\Commands\Config\Sms;

use Illuminate\Console\Command;
use Str;
use File;

class Notification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'config:add-sms-notification {name : The name is the notification class}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Insert SMS notification basic configuration class';

    /**
     * The placeholder for notification generating
     *
     * @var string
     */
    public $generatePlaceholder = '//:end-notification-generating:';

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

            $list = config('sms.notifications', []);

            $code = $this->getTelecomerCode($name);
            // Check data
            if (! isset($list[$code])) {
                 // Add the provider generating to the profile
                 $content = File::get($this->getConfigPath());

                 $append = '\'' . $code . '\' => ' . $name . '::class,' . PHP_EOL;
                 $append .= '        ' . $this->generatePlaceholder;

                 $content = str_replace($this->generatePlaceholder, $append, $content);

                 File::put($this->getConfigPath(), $content);
            } else {
                $this->line('SMS notification \'' . $code . '\' is configured!', 'fg=red;bg=cyan');
            }

            $this->info('SMS notification configuration insert successfully.');
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
     * Get the telecomer code.
     *
     * @param string $name
     * @return string
     */
    public function getTelecomerCode($name)
    {
        return Str::replaceFirst('_telecomer', '', Str::snake(str_replace('\\', '', Str::replaceFirst('App\\Notifications\\Sms\\', '', $name))));
    }
}
