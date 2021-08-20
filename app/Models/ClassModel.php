<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class ClassModel extends Model
{
    use HasFactory;

    protected $table="classes";
    protected $with="levels";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'year'];

    public function levels()
    {
        return $this->belongsToMany(Level::class, 'classes_levels', 'class_id');
    }

    protected static function booted()
    {
        static::addGlobalScope('order', function (Builder $builder) {
            $builder->orderByDesc('updated_at');
        });
    }
}
