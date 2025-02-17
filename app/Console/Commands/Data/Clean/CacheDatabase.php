<?php

namespace App\Console\Commands\Data\Clean;

use Illuminate\Console\Command;
use Schema;
use DB;

class CacheDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cache:db-expired-clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear the expired database cache ( Recommended for use after service suspension )';

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
            if ($this->confirm('Do you want to clear expired database cache and optimize table?')) {
                /* Input name */
                $store = $this->ask('What is the cache stores name for database?');
                /* Check store empty */
                if (! isset($store[0])) {
                    $this->question('The entered store name cannot be empty!');
                    $this->error('The store cache clean failed.');
                    return;
                }
                /* Check store driver */
                if (config('cache.stores.'.$store.'.driver') !== 'database') {
                    $this->question('The store driver entered is not a database type.');
                    $this->error('The `' . $store . '` store cache clean failed.');
                    return;
                }
                $table = config('cache.stores.'.$store.'.table');
                /* Check store table exists */
                if (! isset($table) || ! Schema::connection(config('cache.stores.'.$store.'.connection'))->hasTable($table)) {
                    $this->question('The `' . $store . '` database table not exist!');
                    $this->error('The `' . $store . '` store cache clean failed.');
                    return;
                }
                /* Delete expiration data */
                $this->line('Prepare to clear expired database cache...');
                DB::connection(config('cache.stores.'.$store.'.connection'))->table($table)->where('expiration', '<', time())->delete();
                $this->info('All expiration database cache have been successfully cleared.');
                $this->line('Prepare to optimize the table...');
                /* Handling data fragments */
                DB::connection(config('cache.stores.'.$store.'.connection'))->select(DB::raw('OPTIMIZE TABLE `' . $table . '`'));
                $this->info('The optimization table is completed.');
            } else {
                $this->info('End operation.');
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
}
