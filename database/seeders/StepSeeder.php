<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use App\Models\Step;
use App\Models\Voyage;

class StepSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $voyages = Voyage::all() ;

        foreach ($voyages as $voyage) {

            $step = Step::factory()->create(['voyage_id' => $voyage->id]) ;

            $cnt = rand(1, 10) ;

            for($i = 0; $i < $cnt; $i++) {
                //create consecutive steps where each step start at the arrival of the previous one
                $departure_date = Carbon::parse( $step->arrival_date )->addHours(rand(1, 12)) ;
                $arrival_date = Carbon::parse($departure_date)->addHours(rand(1, 12)) ;
                $step = Step::factory()
                    ->create(['voyage_id' => $voyage->id, 'departure' => $step->arrival, 'departure_date' => $departure_date, 'arrival_date' => $arrival_date]) ;
            }

        }
    }
}
