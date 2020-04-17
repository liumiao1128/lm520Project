<?php

namespace App\Models;

use App\Models\Model;

class GroupModel extends Model {
    //use SoftDeletes;
    /**
     * @var string
     */
    protected $table = 'sys_group';

    protected $primaryKey = 'GroupId';

    public $incrementing = true;

    protected $guarded = [];

    const UPDATED_AT = null;

    const CREATED_AT = null;

}
