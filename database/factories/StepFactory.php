<?php

namespace Database\Factories;

use App\Models\Step;
use App\Models\Voyage;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class StepFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Step::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $type = $this->faker->randomElement(['train','plane','car','bus']);
        $departure_date = $this->faker->dateTimeBetween(Carbon::today(), Carbon::today()->addDay(rand(1, 14)));
        $hours = rand(1, 24) ;

        return [
            'voyage_id' => Voyage::factory(),
            'type' => $type,
            'transport_number' => Str::random(8),
            'departure' => $this->faker->city,
            'arrival' => $this->faker->city,
            'departure_date' => $departure_date,
            'arrival_date' => $this->faker->dateTimeBetween(Carbon::parse( $departure_date)->addHours($hours), Carbon::parse( $departure_date)->addHours($hours+2)),
            'seat' => $this->randomSeat($type),
            'gate' =>  ($type == 'plane') ? $this->randomGate() : null,
            'baggage_drop' => ($type == 'plane') ? $this->randomBaggageDrop() : null
        ];

    }


    protected function randomGate() {
        return $this->faker->randomElement(['A','B','C','D','E','F']) . $this->faker->numberBetween(1, 100) ;
    }

    protected function randomBaggageDrop() {
        return $this->faker->randomElement(['A','B','C','D','E','F']) . $this->faker->numberBetween(1,  50);
    }

    protected function randomSeat($type) {
        if ($type == 'car') {
            return null ;
        } else {
            if ($type == 'bus') {
                $maxSeats = 32 ;
            } else if ($type == 'plane') {
                $maxSeats = 360 ;
            } else {
                $maxSeats = 1000 ;
            }
            return $this->faker->numberBetween(1, $maxSeats);
        }
    }

}
