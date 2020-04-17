<?php

namespace App\Models;

use App\Models\Model;

class UserModel extends Model {
    //use SoftDeletes;
    /**
     * @var string
     */
    protected $table = 'Sys_User';

    protected $primaryKey = 'UserId';

    public $incrementing = true;

    protected $guarded = [];

    const UPDATED_AT = null; //更新不需要这个字段可以设置为null

    const CREATED_AT = 'CreateTime'; //更新不需要这个字段可以设置为null

    //获取馆信息
    function getOrganizeInfo()
    {
        return $this->hasOne(OrganizeModel::class, 'OrgId', 'OrgId');
    }

}
