<?php

namespace App\Console\Commands\Upgrades;

use App\Libraries\Upgrades\BetterControllerGenerator as ControllerGenerator;
use Prettus\Repository\Generators\FileAlreadyExistsException;
use Prettus\Repository\Generators\Commands\ControllerCommand;

class BetterControllerCommand extends ControllerCommand
{

    /**
     * Execute the command.
     *
     * @return void
     */
    public function fire()
    {
        try {
            // Generate create request for controller
            $this->call('make:request', [
                'name' => $this->argument('name') . 'CreateRequest'
            ]);

            // Generate update request for controller
            $this->call('make:request', [
                'name' => $this->argument('name') . 'UpdateRequest'
            ]);

            // Generate create response for controller
            $this->call('make:response', [
                'name' => $this->argument('name') . 'Create'
            ]);
            
            // Generate update response for controller
            $this->call('make:response', [
                'name' => $this->argument('name') . 'Update'
            ]);
            
            // Generate ExceptionCode for controller
            $this->call('make:ex-code', [
                'name' => $this->argument('name')
            ]);

            (new ControllerGenerator([
                'name' => $this->argument('name'),
                'force' => $this->option('force'),
            ]))->run();

            $this->info($this->type . ' created successfully.');
        } catch (FileAlreadyExistsException $e) {
            $this->error($this->type . ' already exists!');

            return false;
        }
    }
}
