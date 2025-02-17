<?php

namespace App\Console\Commands\Config\Signature;

use Illuminate\Console\Command;
use Str;

class Secret extends Command
{
    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'signature:secret
        {--f|force : Skip confirmation when overwriting an existing key.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set the signature secret key used to store the signature';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $key = Str::random(64);
    
            if (file_exists($path = $this->envPath()) === false) {
                $this->error('Environment file does not exist.');
                return;
            }

            if (Str::contains(file_get_contents($path), 'SIGNATURE_SECRET') === false) {
                // create new entry
                file_put_contents($path, PHP_EOL."SIGNATURE_SECRET=$key".PHP_EOL, FILE_APPEND);
            } else {
                if ($this->isConfirmed() === false) {
                    $this->comment('No changes were made to your signature secret key.');
                    return;
                }

                // update existing entry
                file_put_contents($path, str_replace(
                    'SIGNATURE_SECRET='.$this->laravel['config']['signature.secret'],
                    'SIGNATURE_SECRET='.$key,
                    file_get_contents($path)
                ));
            }

            $this->displayKey($key);
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
     * Display the key.
     *
     * @param  string  $key
     * @return void
     */
    protected function displayKey($key)
    {
        $this->laravel['config']['signature.secret'] = $key;

        $this->info("Signature secret key [$key] set successfully.");
    }

    /**
     * Check if the modification is confirmed.
     *
     * @return bool
     */
    protected function isConfirmed()
    {
        return $this->option('force') ? true : $this->confirm(
            'This will invalidate all existing signatures. Are you sure you want to override the secret key?'
        );
    }

    /**
     * Get the .env file path.
     *
     * @return string
     */
    protected function envPath()
    {
        if (method_exists($this->laravel, 'environmentFilePath')) {
            return $this->laravel->environmentFilePath();
        }

        return $this->laravel->basePath('.env');
    }
}
