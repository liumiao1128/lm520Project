<?php

namespace App\Models;

use App\Models\Model;

class CareerModel extends Model
{
    //use SoftDeletes;
    /**
     * @var string
     */
    protected $table = 'sys_career';

    protected $primaryKey = 'CareerId';

    public $incrementing = true;

    protected $guarded = [];

    const UPDATED_AT = null;

    const CREATED_AT = null;

    public static function organizeOrderJoin(){
        $query = \DB::table('sys_career as c')
            ->leftjoin('sys_dept as d', 'c.DeptId', '=', 'd.DeptId');
        return $query;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     * 获取部门信息
     */
    function getDeptInfo(){
        return $this->hasOne(DeptModel::class,'DeptId', 'DeptId');
    }

    function getCareerInfo(){
        return $this->hasOne(CareerModel::class,'CareerId', 'SuperiorId');
    }

}
