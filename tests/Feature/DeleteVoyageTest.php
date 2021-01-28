<?php

namespace Tests\Feature;

use App\Models\Voyage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;


class DeleteVoyageTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testDeleteVoyage()
    {
        // Run the DatabaseSeeder...
        if (Voyage::all()->count() == 0) {
            $this->seed() ;
        }
        $voyage = Voyage::first();

        $voyage->delete();

        $this->assertDeleted($voyage);

    }
}
