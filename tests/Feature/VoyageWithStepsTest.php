<?php

namespace Tests\Feature;

use App\Models\Voyage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class VoyageWithStepsTest extends TestCase
{
    /**
     * Verify that all the created voyages have at least one step
     *
     * @return void
     */
    public function testNoEmptySteps()
    {
        $cnd = true ;
        foreach (Voyage::all() as $voyage) {
            $cnd = count($voyage->steps) > 0 ;
            if (!$cnd) {
                break;
            }
        }
        $this->assertTrue($cnd) ;
    }
}
