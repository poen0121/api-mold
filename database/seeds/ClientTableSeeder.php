<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class ClientTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (! App\Entities\Service\Client::where('name', 'Global Manager')->first()) {
            /* Default client for app id : 1002212294583 */
            App\Entities\Service\Client::create([
                'name' => 'Global Manager',
                'ban' => 0
            ]);
        }
        // Factory make test
        // Model::unguard();
        // factory(App\Entities\Service\Client::class, 2)->create();
        // Model::reguard();
    }
}