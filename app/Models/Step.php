<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class StepSeeder
 * @package App\Models
 * @property string $type
 * @property string $transport_number
 * @property \Carbon\Carbon $departure_date
 * @property \Carbon\Carbon $arrival_date
 * @property string $departure
 * @property string $arrival
 * @property string $seat
 * @property string $gate
 * @property string $baggage_drop
 * @property \Carbon\Carbon created_at
 * @property \Carbon\Carbon $updated_at
 */
class Step extends Model
{
    use HasFactory;

    protected $fillable = ['type', 'transport_number', 'departure_date', 'arrival_date', 'departure', 'arrival', 'seat', 'gate', 'baggage_drop'] ;

    public function voyage()
    {
        return $this->belongsTo(Voyage::class);
    }
}
