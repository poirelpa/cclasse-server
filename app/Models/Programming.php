<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Programming extends BaseModel
{
    use HasFactory;

    protected $fillable = ['name', 'color', 'class_id'];

    public function class_()
    {
        return $this->belongsTo(ClassModel::class, 'class_id', 'id');
    }
}
