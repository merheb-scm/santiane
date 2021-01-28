<?php

namespace Tests\Feature;

use App\Models\Voyage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;


class CreateVoyagesTest extends TestCase
{
    /**
     * Test that we are able to use the factory to generate and create voyages and its related steps
     *
     * @return void
     */
    public function testCreatingVoyagesAndSteps()
    {
        // Run the DatabaseSeeder...
        if (Voyage::all()->count() == 0) {
            $this->seed() ;
        }

        $cnt = Voyage::all()->count() ;

        $this->assertDatabaseCount('voyages', $cnt);
    }
}
