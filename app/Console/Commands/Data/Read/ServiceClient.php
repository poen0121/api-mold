<?php

namespace App\Console\Commands\Data\Read;

use Illuminate\Console\Command;
use App\Repositories\Service\ClientRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ServiceClient extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'data:client-read';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Read basic information about client users';

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
     * @param ClientRepository $repository
     *
     * @return mixed
     */
    public function handle(ClientRepository $repository)
    {
        $appId = $this->ask('What is the client\'s app id?');

        try {
            $model = app($repository->model());
            /* Real id */
            $id = $model->asPrimaryId($appId);
            if (isset($id)) {
                /* Check exist */
                try {
                    $client = $repository->focusClient((int) $id);
                } catch (\Throwable $th) {
                    if ($th instanceof ModelNotFoundException) {
                        $this->question('This client service does not exist!');
                        $this->error('Client read failed.');
                        return;
                    }
                    throw $th;
                }
                /* Information */
                $this->info('Information :');

                $display = [];

                foreach ($client as $key => $val) {
                    if ($key !== 'id') {
                        $display[] = [
                            'column' => $key,
                            'value' => (is_bool($val) ? ($val ? 1 : 0) : $val)
                        ];
                    }
                }

                $headers = [
                        'Column',
                        'Value'
                    ];

                $this->table($headers, $display);

                $this->info('The read returned successfully.');
            } else {
                $this->question('The entered app id is wrong!');
                $this->error('Client read failed.');
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
