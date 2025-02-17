<?php

namespace App\Console\Commands\Data\Add;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Prettus\Repository\Generators\Stub;
use App\Repositories\System\ParameterRepository;

class SystemParameter extends Command
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
    protected $signature = 'data:sp-add';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Build a system parameter';

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
     * @param string $name
     * @return string
     */
    public function getMigrationPath($name)
    {
        return base_path('database' . DIRECTORY_SEPARATOR . 'migrations' . DIRECTORY_SEPARATOR . $this->getMigrationFileName($name) . '.php');
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return base_path('resources' . DIRECTORY_SEPARATOR . 'stubs' . DIRECTORY_SEPARATOR . 'SystemParameter.stub');
    }

    /**
     * Get the migration file name.
     *
     * @param string $name
     * @return string
     */
    public function getMigrationFileName($name)
    {
        return date('Y_m_d_His_') . snake_case($this->getClassName($name));
    }

    /**
     * Get class name.
     *
     * @param string $name
     * @return string
     */
    public function getClassName($name)
    {
        return 'Insert' . studly_case($name) . 'SystemParameter';
    }

    /**
     * Execute the console command.
     *
     * @param ParameterRepository $repository
     * @return mixed
     */
    public function handle(ParameterRepository $repository)
    {
        try {
            $this->comment('Parameter name uses only characters such as ( a ~ z 0 ~ 9 _ ) .');
            /* Input name */
            $name = $this->ask('What is the system parameter\'s name?');
            /* Check empty */
            if (! isset($name[0])) {
                $this->question('The entered parameter name cannot be empty!');
                $this->error('System parameter creation failed.');
                return;
            }
            /* Check format */
            if (! preg_match('/^[a-z0-9_]*$/i', $name)) {
                $this->question('The entered parameter name uses only characters such as ( a ~ z 0 ~ 9 _ ) .');
                $this->error('System parameter creation failed.');
                return;
            }
            /* Check length */
            if (strlen($name) > 128) {
                $this->question('The parameter name entered must not exceed 128 bytes!');
                $this->error('System parameter creation failed.');
                return;
            }
            /* Check parameter exists */
            $result = $repository->model()::where('slug', $name)->first();
            if (isset($result)) {
                $rules = config('sp.rules');
                if (! isset($rules[$name])) {
                    $this->question('Parameter name in database data already exists but the configuration is not enabled!');
                } else {
                    $this->question('Parameter name already exists!');
                }
                $this->error('System parameter creation failed.');
                return;
            }
            /* Input value */
            $value = $this->ask('What is the system parameter\'s value?');
            /* Check empty */
            if (! isset($value[0])) {
                $this->question('The entered parameter value cannot be empty!');
                $this->error('System parameter creation failed.');
                return;
            }
            /* Check length */
            if (strlen($value) > 128) {
                $this->question('The parameter value entered must not exceed 128 bytes!');
                $this->error('System parameter creation failed.');
                return;
            }
            /* Save */
            if ($this->confirm('Do you want to save the creation system parameter?')) {

                /* Value format */
                $value = strtr($value, ['\''=>'\\\'']);

                /* Create migrations file */

                $stubFile = $this->getStub();

                if (! file_exists($stubFile)) {
                    $this->question('System parameter migrations Stub file does not exist!');
                    $this->error('Parameter creation failed.');
                    return;
                }

                $replacements = [
                    'class'  => $this->getClassName($name),
                    'slug'  => $name,
                    'value' => $value
                ];

                $contents = Stub::create($stubFile, $replacements);
                /* Migration file path */
                $path = $this->getMigrationPath($name);

                if (! $this->files->isDirectory(dirname($path))) {
                    $this->files->makeDirectory(dirname($path), 0777, true, true);
                }
                /* Write file */
                $this->files->put($path, $contents);

                $this->info('System parameter migration insert created successfully.');
                /* Insert rule to the profile */
                $command = [];
                $command['name'] = $name;
                $this->call('config:add-sp-rule', $command);
                /* Create lang document file */
                $command = [];
                $command['name'] = $name;
                $command['--force'] = false;
                $this->call('make:sp-document', $command);

                /* Insert system parameter */
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
