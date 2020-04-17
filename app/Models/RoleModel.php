<?php

namespace App\Models;

use App\Models\Model;

class RoleModel extends Model {
    //use SoftDeletes;
    /**
     * @var string
     */
    protected $table = 'sys_role';

    protected $primaryKey = 'RoleId';

    public $incrementing = true;

    protected $guarded = [];

    const UPDATED_AT = null;

    const CREATED_AT = null;

    public static function organizeOrderJoin()
    {
        $query = \DB::table('sys_role as r')
            ->leftjoin('sys_dept as d', 'r.DeptId', '=', 'd.DeptId');
        return $query;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     * 获取部门信息
     */
    function getDeptInfo()
    {
        return $this->hasOne(DeptModel::class, 'DeptId', 'DeptId');
    }

    function getRoleInfo()
    {
        return $this->hasOne(RoleModel::class, 'RoleId', 'SuperiorId');
    }

}
