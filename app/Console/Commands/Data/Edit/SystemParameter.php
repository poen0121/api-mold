<?php

namespace App\Console\Commands\Data\Edit;

use Illuminate\Console\Command;
use App\Repositories\System\ParameterRepository;
use SystemParameter as SP;

class SystemParameter extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'data:sp-edit';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Edit the system parameter\'s value';

    /**
     * Indicates whether the command should be shown in the Artisan command list.
     *
     * @var bool
     */
    protected $hidden = false;

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
     * @param ParameterRepository $repository
     * @return mixed
     */
    public function handle(ParameterRepository $repository)
    {
        try {
            $this->info('Information :');
            /* Criteria Index */
            $repository->pushCriteria(app('App\Criteria\System\Parameter\IndexCriteria'));

            $parameters = $repository->all()['data'];

            if (count($parameters) > 0) {
                
                $display = [];

                $slugList = [];

                foreach ($parameters as $item) {

                    $slugList[] = $item['slug'];

                    $display[] = [
                        'parameter' => $item['slug'],
                        'value' => $item['value'],
                        'rule' => (isset($item['rule']['description']) ? $item['rule']['description'] : 'Undefined'),
                        'description' => $item['description']
                    ];
                }

                $headers = [
                    'Parameter',
                    'Value',
                    'Rule',
                    'Description'
                ];

                $this->table($headers, $display);
            }

            if (count($parameters) > 0) {
                /* Input name */
                $name = $this->ask('Please enter the name of the system parameter to modify?');
                /* Check empty */
                if (! isset($name[0])) {
                    $this->question('The entered parameter name cannot be empty!');
                    $this->error('System parameter editing failed.');
                    return;
                }
                /* Check format */
                if (! preg_match('/^[a-z0-9_]*$/i', $name)) {
                    $this->question('The entered parameter name uses only characters such as ( a ~ z 0 ~ 9 _ ) .');
                    $this->error('System parameter editing failed.');
                    return;
                }
                /* Check length */
                if (strlen($name) > 128) {
                    $this->question('The parameter name entered must not exceed 128 bytes!');
                    $this->error('System parameter editing failed.');
                    return;
                }
                /* Check parameter exists */
                if (in_array($name, $slugList, true)) {
                    /* Input value */
                    $value = $this->ask('What is the system parameter\'s new value?');
                    /* Check empty */
                    if (! isset($value[0])) {
                        $this->question('The entered parameter value cannot be empty!');
                        $this->error('System parameter editing failed.');
                        return;
                    }
                    /* Check length */
                    if (strlen($value) > 128) {
                        $this->question('The parameter value entered must not exceed 128 bytes!');
                        $this->error('System parameter editing failed.');
                        return;
                    }
                    /* Set value */
                    if ($this->confirm('Do you want to save system parameter edits?')) {
                        if (SP::setValue($name, $value)) {
                            $this->info('The value of the system parameter has changed successfully.');
                        } else {
                            $this->question('Writing of system parameter value was rejected.');
                            $this->error('System parameter editing failed.');
                        }
                    } else {
                        $this->info('End operation.');
                    }
                } else {
                    $this->question('This parameter does not exist!');
                    $this->error('System parameter editing failed.');
                }
            } else {
                $this->line('No parameter content currently.');
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
