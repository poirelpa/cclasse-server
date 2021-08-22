<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Level extends BaseModel
{
    use HasFactory;

    protected static function booted()
    {
      static::addGlobalScope('order', function (Builder $builder) {
           $builder->orderBy('order_index', 'asc');
      });
    }
}
