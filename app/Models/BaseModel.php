<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

abstract class BaseModel extends Model
{
    public function getMorphClass()
    {
        return Str::snake((new \ReflectionClass($this))->getShortName());
    }
}
