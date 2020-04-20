<?php

namespace App\Http\Controllers\Admin;

use App\Common\ResponseCode;
use App\Models\MenuModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;

class MenuController extends Controller {

    private static $view_data;

    /**
     * 菜单列表页
     * @param Request $request
     * @return Ambigous <\Illuminate\View\View, \Illuminate\Contracts\View\Factory>
     */
    public function menuList(Request $request)
    {
        $input = $request->all();
        foreach ($input as $k => $v) {
            $input[$k] = trim($v);
        }
        //查询所属系统
        $systemType = !empty($input['SystemType']) ? $input['SystemType'] : 1;

        $menuList = MenuModel::getPermissionTreeList1($systemType);

        self::$view_data['menuList'] = $menuList;
        return view('Admin.Menu.menuList', self::$view_data);
    }
}
