<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    /**
     * Get `protected $table` property
     *
     * @return mixed
     */
    public static function getTableName()
    {
        return with(new static)->getTable();
    }
}
