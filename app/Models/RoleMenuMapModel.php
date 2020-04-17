<?php

namespace App\Models;

use App\Models\Model;

class RoleMenuMapModel extends Model {
    //use SoftDeletes;
    /**
     * @var string
     */
    protected $table = 'sys_rolemenumap';

    protected $primaryKey = 'Id';

    public $incrementing = true;

    protected $guarded = [];

    const UPDATED_AT = null;

    const CREATED_AT = null;

    /**
     * 根据角色id得到相应的权限
     * @param $roleId
     * @return
     */
    public static function getPermissionArr($roleId)
    {
        $result = array();
        if (!empty($roleId)) {
            $data = self::getRecordListWhereIn('RoleId', $roleId, [], ['MenuId'])->toArray();
            foreach ($data as $val) {
                $result[] = $val['MenuId'];
            }
        }
        return $result;
    }

}
