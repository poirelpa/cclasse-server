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

    public function scopeWhereUserCan($query, $ability, $user = null)
    {
        if (empty($user)) {
            $user = auth()->id();
        }

        return $query->whereRaw("id in (
            select e.id from {$this->table} e
            inner join abilities a
                on (a.entity_type = ? or a.entity_type = '*') and (a.entity_id = e.id or a.entity_id is null) and (a.name =? or a.name='*')
            inner join permissions p
                on p.ability_id = a.id and not p.forbidden
            left join roles r
                on p.entity_id = r.id and p.entity_type = 'roles'
            left join assigned_roles ar
                on ar.role_id = r.id
            inner join users u
                on (u.id = ? and (u.id = ar.entity_id and ar.entity_type = 'user') or u.id = (p.entity_id and p.entity_type = 'user'))
                ". (in_array('user_id', $this->fillable) ? 'and (a.only_owned = 0 or u.id = e.user_id)' : '')."
            )", [$this->getMorphClass(), $ability, $user]);
    }
}
