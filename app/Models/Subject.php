<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends BaseModel
{
    use HasFactory;

    protected $fillable = ['name', 'color','reference_xpath', 'parent_subject_id', 'program_id'];

    public function program()
    {
        return $this->belongsTo(Program::class);
    }
    
    public function class_()
    {
        return $this->belongsTo(ClassModel::class);
    }
}
