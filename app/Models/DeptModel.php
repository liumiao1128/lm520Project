<?php

namespace App\Models;

use App\Models\Model;

class DeptModel extends Model
{
    //use SoftDeletes;
    /**
     * @var string
     */
    protected $table = 'sys_dept';

    protected $primaryKey = 'DeptId';

    public $incrementing = true;

    protected $guarded = [];

    const UPDATED_AT = null; //更新不需要这个字段可以设置为null

    const CREATED_AT = null; //更新不需要这个字段可以设置为null


}
