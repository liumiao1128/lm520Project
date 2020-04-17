<?php

namespace App\Models;


class UserRolesModel extends Model
{
    //use SoftDeletes;
    /**
     * @var string
     */
    protected $table = 'sys_userroles';

    protected $primaryKey = 'Id';

    public $incrementing = true;


    protected $guarded = [];

    const UPDATED_AT = null; //更新不需要这个字段可以设置为null

    const CREATED_AT = null; //更新不需要这个字段可以设置为null
}
