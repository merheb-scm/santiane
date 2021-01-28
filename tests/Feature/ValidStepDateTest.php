<?php

namespace Tests\Feature;

use App\Models\Voyage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ValidStepDateTest extends TestCase
{
    /**
     * Check if the created steps for all voyages have all valid date
     *
     * @return void
     */
    public function testValidStepDates()
    {
        // Run the DatabaseSeeder...
        if (Voyage::all()->count() == 0) {
            $this->seed() ;
        }

        $cnd = true ;
        foreach (Voyage::all() as $voyage) {
            foreach ($voyage->steps as $step) {
                $cnd = $step->departure_date < $step->arrival_date;
                if (!$cnd) {
                    break;
                }
            }
            if (!$cnd) {
                break;
            }
        }
        $this->assertTrue($cnd) ;

    }
}
