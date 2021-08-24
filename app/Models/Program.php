<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Program extends BaseModel
{
    use HasFactory;

    protected $fillable = ['name', 'reference_file'];

    public function levels()
    {
        return $this->belongsToMany(Level::class);
    }
    
    public function subjects()
    {
        return $this->hasMany(Subject::class);
    }
}
