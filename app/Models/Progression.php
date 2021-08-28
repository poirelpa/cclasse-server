<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Progression extends BaseModel
{
    use HasFactory;

    protected $fillable = ['name', 'parent_id', 'class_id', 'subject_id'];


    public function class_()
    {
        return $this->belongsTo(ClassModel::class, 'class_id', 'id');
    }

    public function parent()
    {
        return $this->belongsTo(Progression::class, 'parent_id');
    }
    public function children()
    {
        return $this->hasMany(Progression::class, 'parent_id');
    }

    // the sub-subjects covered by the progression
    public function subjects()
    {
        return $this->belongToMany(Subject::class);
    }
}
