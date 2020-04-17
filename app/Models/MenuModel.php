<?php

namespace App\Models;


class MenuModel extends Model {
    //use SoftDeletes;
    /**
     * @var string
     */
    protected $table = 'sys_menu';

    protected $primaryKey = 'MenuId';

    public $incrementing = true;


    protected $guarded = [];

    const UPDATED_AT = null; //更新不需要这个字段可以设置为null

    const CREATED_AT = null; //更新不需要这个字段可以设置为null


    /**
     * 得到父子级列表
     * @param $role_id 角色id
     * @return
     */
    public static function getPermissionParentChildrenList($role_id)
    {
        $permission_arr = array();
        $child_permission_arr = array();
        $ids_arr = array();
        if ($role_id != 'Admin') {
            $ids_arr = RoleMenuMapModel::getPermissionArr($role_id);
            if (empty($ids_arr)) {//非admin用户，若无权限，直接返回
                return $permission_arr;
            }
        }
        //菜单
        $permissionList = self::select('MenuId', 'SystemType', 'MenuName', 'ParentId', 'MenuType', 'OperationType', 'Keyword','IcoClass','WebUrl')
            ->where('IsDeleted', 0)->where('Status', 1)->where('MenuType', 1)
            ->orderBy('Sort', 'desc')
            ->get()
            ->toArray();
        //操作
        $operatingList = self::select('MenuId', 'SystemType', 'MenuName', 'ParentId', 'MenuType', 'OperationType', 'Keyword','IcoClass','WebUrl')
            ->where('IsDeleted', 0)->where('Status', 1)->where('MenuType', 2)
            ->orderBy('Sort', 'desc')
            ->get()
            ->toArray();

        //记入一级菜单
        foreach ($permissionList as $row) {
            if (empty($row['ParentId'])) {
                if (in_array($row['MenuId'], $ids_arr) || $role_id == 'Admin') {
                    $permission_arr[$row['MenuId']] = $row;
                    $permission_arr[$row['MenuId']]['son'] = [];
                }
            }
        }

        //记入二级菜单
        foreach ($permissionList as $row) {
            if (!empty($row['ParentId'])) {
                if (in_array($row['MenuId'], $ids_arr) || $role_id == 'Admin') {
                    if (!empty($permission_arr[$row['ParentId']])) {
                        $permission_arr[$row['ParentId']]['son'][$row['MenuId']] = $row;
                        $permission_arr[$row['ParentId']]['son'][$row['MenuId']]['son'] = [];
                    }
                }
            }
        }

        $permission_arr = array_map(function ($val) {
            $val['son'] = array_values($val['son']);
            return $val;
        }, $permission_arr);

        $operating_arr = [];
        foreach ($operatingList as $v) {
            $operatingInfo = MenuModel::setArray($v['OperationType']);
//            $operating_arr[$v['Keyword']][] = $operatingInfo;
            if (array_key_exists($v['Keyword'], $operating_arr)) {
                $operating_arr[$v['Keyword']] = array_merge($operating_arr[$v['Keyword']], $operatingInfo);
            } else {
                $operating_arr[$v['Keyword']] = $operatingInfo;
            }
        }

        $data = [
            'permission_arr' => array_values($permission_arr),
            'operating_arr' => $operating_arr,
        ];
        return $data;
    }

    /**
     * @param $OperationType
     * @return array 返回操作权限
     */
    public static function setArray($OperationType)
    {
        $data = [];
        switch ($OperationType) {
            case 1:
                $data['add'] = 1;
                break;
            case 2:
                $data['edit'] = 1;
                break;
            case 3:
                $data['del'] = 1;
                break;
            case 4:
                $data['selectDel'] = 1;
                break;
            case 5:
                $data['export'] = 1;
                break;
            default:
                $data['import'] = 1;
        }
        return $data;

    }

    /**
     * @param $SystemType 隶属系统
     */
    public static function getPermissionTreeList($SystemType)
    {
        $where['SystemType'] = $SystemType;
        $where['Status'] = 1;
        $permissionList = self::select('MenuId', 'SystemType', 'MenuName', 'WebUrl', 'ParentId', 'Sort', 'MenuType', 'OperationType', 'Keyword','IcoClass','Remark','IsCommonly')->where($where)
            ->orderBy('CreateTime', 'asc')
            ->get()
            ->toArray();
        $permissionArr = self::createTreeList($permissionList, 0, 0);

        return $permissionArr;
    }

    /**
     * 产生树列表最终返回的树状列表
     * @param $record_list
     * @param $parent_id
     * @param $level
     */
    public static function createTreeList($record_list, $parent_id, $level)
    {
        $record_arr = [];
        foreach ($record_list as $key => $value) {
            if ($value['ParentId'] == $parent_id) {
                $value['level'] = $level + 1;
                $value['children'] = self::createTreeList($record_list, $value['MenuId'], $value['level']);
                if (empty($value['children'])) {
                    unset($value['children']);
                }
                $record_arr[] = $value;
            }
        }
        return $record_arr;
    }
}
