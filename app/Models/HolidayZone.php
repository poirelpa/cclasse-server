<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HolidayZone extends BaseModel
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name'];

    public function academies()
    {
        return $this->hasMany(Academy::class);
    }

    public function publicHolidays()
    {
        return $this->hasMany(PublicHoliday::class);
    }
}
