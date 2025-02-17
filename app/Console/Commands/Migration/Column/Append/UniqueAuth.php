<?php

namespace App\Console\Commands\Migration\Column\Append;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Prettus\Repository\Generators\Stub;
use Schema;
use File;

class UniqueAuth extends Command
{
    /**
     * The filesystem instance.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mg-column:append-unique-auth {table? : Table in the database} {--y|yes : Allow execution}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Append a `unique_auth` column for database table';

    /**
     * Create a new controller creator command instance.
     *
     * @param  \Illuminate\Filesystem\Filesystem  $files
     * @return void
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct();

        $this->files = $files;
    }

    /**
     * Get destination path for generated migration file.
     *
     * @param string $table
     * @return string
     */
    public function getMigrationPath($table)
    {
        return base_path('database' . DIRECTORY_SEPARATOR . 'migrations' . DIRECTORY_SEPARATOR . $this->getMigrationFileName($table) . '.php');
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return base_path('resources' . DIRECTORY_SEPARATOR . 'stubs' . DIRECTORY_SEPARATOR . 'migrations' . DIRECTORY_SEPARATOR . 'column' . DIRECTORY_SEPARATOR . 'append' . DIRECTORY_SEPARATOR . 'UniqueAuth.stub');
    }

    /**
     * Get the migration file name.
     *
     * @param string $table
     * @return string
     */
    public function getMigrationFileName($table)
    {
        return date('Y_m_d_His_') . snake_case($this->getClassName($table));
    }

    /**
     * Get class name.
     *
     * @param string $table
     * @return string
     */
    public function getClassName($table)
    {
        return 'Append' . studly_case($table . ucfirst($this->getColumnName())) . 'Column';
    }

    /**
     * Check if the migrations class name already exists.
     * 
     * @param  string  $className
     * @return boolean
     */
    public function classAlreadyExists($className)
    {
        $migrationsPath = database_path('migrations');
        $migrationFiles = File::glob($migrationsPath . DIRECTORY_SEPARATOR . '*_*.php');
        foreach ($migrationFiles as $migrationFile) {
            $content = file_get_contents($migrationFile);
            if (preg_match('/class\s+([^\s]+)/', $content, $matches)) {
                if ($className === $matches[1]) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Get column name.
     *
     * @return string
     */
    public function getColumnName()
    {
        return 'unique_auth';
    }

    /**
     * Get column note.
     *
     * @return string
     */
    public function getColumnNote()
    {
        return '唯一身份授權碼';
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
            if (!($table = $this->argument('table'))) {
                $this->comment('Table name uses only characters such as ( a ~ z 0 ~ 9 _ ) .');
                /* Ask name */
                $table = $this->ask('What is the database table\'s name?');
            }
            /* Check empty */
            if (! isset($table[0])) {
                $this->question('The entered table name cannot be empty!');
                $this->error('Table `' . $this->getColumnName() . '` column creation failed.');
                return;
            }
            /* Check format */
            if (! preg_match('/^[a-z0-9_]*$/i', $table)) {
                $this->question('The entered table name uses only characters such as ( a ~ z 0 ~ 9 _ ) .');
                $this->error('Table `' . $this->getColumnName() . '` column creation failed.');
                return;
            }
            /* Check table exists */
            if (! Schema::hasTable($table)) {
                $this->question('Table does not exist!');
                $this->error('Table `' . $this->getColumnName() . '` column creation failed.');
                return;
            }

            /* Check column exists */
            if (Schema::hasColumn($table, $this->getColumnName())) {
                $this->question('The `' . $this->getColumnName() . '` column already exists!!');
                $this->error('Table `' . $this->getColumnName() . '` column creation failed.');
                return;
            }

            /* Save */
            if ($this->option('yes') || $this->confirm('Do you want to save the creation `' . $this->getColumnName() . '` column on `' . $table . '` table?')) {

                /* Check migrations class name */
                $className = $this->getClassName($table);
                if ($this->classAlreadyExists($className)) {
                    $this->question('The migration class for the `' . $this->getColumnName() . '` column already exists!');
                    $this->error('Table `' . $this->getColumnName() . '` column creation failed.');
                    return;
                }

                /* Create migrations file */

                $stubFile = $this->getStub();

                if (! file_exists($stubFile)) {
                    $this->question('The `' . $this->getColumnName() . '` column migrations stub file does not exist!');
                    $this->error('Table `' . $this->getColumnName() . '` column creation failed.');
                    return;
                }

                $replacements = [
                    'class'  => $className,
                    'table'  => $table,
                    'column' => $this->getColumnName(),
                    'note' => $this->getColumnNote()
                ];

                $contents = Stub::create($stubFile, $replacements);
                /* Migration file path */
                $path = $this->getMigrationPath($table);

                if (! $this->files->isDirectory(dirname($path))) {
                    $this->files->makeDirectory(dirname($path), 0777, true, true);
                }
                /* Write file */
                $this->files->put($path, $contents);

                $this->line('Config : ban.php release-unique_auth_ignore_guards');

                $this->line('Config : ban.php release-unique_auth_inherit_login_guards');

                $this->info('The `' . $this->getColumnName() . '` column migration append created successfully.');
            
                /* Execute migrate */
                $this->call('migrate', []);
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
