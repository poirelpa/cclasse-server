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

    public function scopeWhereCan($query, $userId, $ability, $entity){
        
        return $query->whereRaw("user_id = ?
            or user_id in (
                select u.id from users u
                left join assigned_roles ar on ar.entity_id = ? and ar.entity_type = 'user'
                left join roles r on r.id = ar.role_id
                inner join permissions p on (p.entity_id = u.id and p.entity_type = 'user') or (p.ability_id = r.id and p.entity_type = 'role')
                inner join abilities a on a.id = p.ability_id and (a.name='*' or a.name = ?) and (a.entity_type=? or a.entity_type = '*')
            )",[$userId, $userId, $ability, $entity]);
    
    }
}
