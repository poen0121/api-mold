<?php

namespace App\Console\Commands\Data\Read;

use Illuminate\Console\Command;
use App\Repositories\System\ParameterRepository;

class SystemParameter extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'data:sp-read';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Read index information about system parameter';

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
     *
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

                foreach ($parameters as $item) {
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
                $this->info('The read returned successfully.');
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
