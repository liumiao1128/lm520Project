<?php

namespace App\Models;

use App\Models\Model;

class UserTokenModel extends Model
{
    //use SoftDeletes;
    /**
     * @var string
     */
    protected $table = 'Sys_UserToken';

    protected $primaryKey = 'UserTokenId';

    public $incrementing = false;

    public $keyType = 'string';

    protected $guarded = [];

    const UPDATED_AT = null; //更新不需要这个字段可以设置为null
    const CREATED_AT = null; //更新不需要这个字段可以设置为null


}
