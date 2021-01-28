<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Voyage
 * @package App\Models
 * @property int $id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class Voyage extends Model
{
    use HasFactory;

    protected $fillable = ['reference'] ;

    public function steps()
    {
        return $this->hasMany(Step::class);
    }

    public function getCityFromToAttribute() {
        $firstStep = $this->steps()->orderBy('departure_date', 'asc')->first() ;
        $lastStep = $this->steps()->orderBy('arrival_date', 'desc')->first() ;
        return (isset($firstStep) && isset($lastStep)) ? (isset($firstStep) ? $firstStep->departure : '')
            . ' - ' .
            (isset($lastStep) ? $lastStep->arrival : '') : '';
    }

    public function getDateFromToAttribute() {
        $firstStep = $this->steps()->orderBy('departure_date', 'asc')->first() ;
        $lastStep = $this->steps()->orderBy('arrival_date', 'desc')->first() ;
        return (isset($firstStep) && isset($lastStep)) ? (isset($firstStep) ? Carbon::parse($lastStep->departure_date)->format('d M Y H:i') : '')
            . ' - ' .
            (isset($lastStep) ? Carbon::parse($lastStep->arrival_date)->format('d M Y H:i') : '') : '';
    }
}
