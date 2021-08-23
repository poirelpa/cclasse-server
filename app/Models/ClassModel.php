<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class ClassModel extends BaseModel
{
    use HasFactory;

    protected $table="classes";
    protected $with="levels";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'year', 'user_id'];

    public function levels()
    {
        return $this->belongsToMany(Level::class, 'classes_levels', 'class_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected static function booted()
    {
        static::addGlobalScope('order', function (Builder $builder) {
            $builder->orderByDesc('updated_at');
        });
    }


    public function getMorphClass()
    {
        return 'class';
    }
}
