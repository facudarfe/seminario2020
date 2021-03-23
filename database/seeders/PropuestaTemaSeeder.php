<?php

namespace Database\Seeders;

use App\Models\Estado;
use App\Models\PropuestaTema;
use App\Models\User;
use Illuminate\Database\Seeder;

class PropuestaTemaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PropuestaTema::factory(5)->create();
    }
}
