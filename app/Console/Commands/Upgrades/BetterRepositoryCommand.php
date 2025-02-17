<?php

namespace App\Console\Commands\Upgrades;

use Prettus\Repository\Generators\Commands\RepositoryCommand;
use Illuminate\Support\Collection;
use Prettus\Repository\Generators\FileAlreadyExistsException;
use Prettus\Repository\Generators\MigrationGenerator;
use Prettus\Repository\Generators\ModelGenerator;
use App\Libraries\Upgrades\BetterRepositoryEloquentGenerator as RepositoryEloquentGenerator;
use Prettus\Repository\Generators\RepositoryInterfaceGenerator;

class BetterRepositoryCommand extends RepositoryCommand
{
    /**
     * Execute the command.
     *
     * @return void
     */
    public function fire()
    {
        $this->generators = new Collection();

        $migrationGenerator = new MigrationGenerator([
            'name'   => 'create_' . strtr(snake_case(str_plural($this->argument('name'))), ['\\'=>'','/'=>'']) . '_table',
            'fields' => $this->option('fillable'),
            'force'  => $this->option('force'),
        ]);
        
        if (! $this->option('skip-migration')) {
            $this->generators->push($migrationGenerator);
        }
        
        $modelGenerator = new ModelGenerator([
            'name'     => $this->argument('name'),
            'fillable' => $this->option('fillable'),
            'force'    => $this->option('force')
        ]);
        
        if (! $this->option('skip-model')) {
            $this->generators->push($modelGenerator);
        }
        
        $this->generators->push(new RepositoryInterfaceGenerator([
            'name'  => $this->argument('name'),
            'force' => $this->option('force'),
        ]));
        
        foreach ($this->generators as $generator) {
            $generator->run();
        }
        
        $model = $modelGenerator->getRootNamespace() . '\\' . $modelGenerator->getName();
        $model = str_replace([
            "\\",
            '/'
        ], '\\', $model);
        
        try {
            (new RepositoryEloquentGenerator([
                'name'      => $this->argument('name'),
                'rules'     => $this->option('rules'),
                'validator' => $this->option('validator'),
                'force'     => $this->option('force'),
                'model'     => $model
            ]))->run();
            $this->info("Repository created successfully.");
        } catch (FileAlreadyExistsException $e) {
            $this->error($this->type . ' already exists!');
            
            return false;
        }
    }
}
