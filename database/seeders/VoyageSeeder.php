<?php

namespace Database\Seeders;

use App\Models\Voyage;
use Illuminate\Database\Seeder;

class VoyageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Voyage::factory()
            ->count(10)
            ->create();

    }
}
