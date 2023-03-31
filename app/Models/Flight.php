<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\FlightsCrew;
use App\Models\Passenger;

class Flight extends Model
{
    use HasFactory;

    /**
    * The table associated with the model.
    *
    * @var string
    */
    protected $table = 'flights';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Indicates if the model's ID is auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * create crew relatation
     * return object
     */
    public function flightsCrew()
    {
        return $this->hasOne(FlightsCrew::class);
        //return $this->hasManyThrough(Passenger::class,FlightsCrew::class);
    }

    /**
     * create crew relatation
     * return object
     */
    public function passenger()
    {
        return $this->hasMany(Passenger::class);
    }
}
