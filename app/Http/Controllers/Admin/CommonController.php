<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;

class CommonController extends Controller
{
    /**
     * home页
     * @param Request $request
     * @return Ambigous <\Illuminate\View\View, \Illuminate\Contracts\View\Factory>
     */
    public function home(Request $request)
    {
        $staff_id = session('userId');

        if (empty($staff_id)) {
            return redirect('admincp/login');
        }
        $staff_permission = session('permissionInfo');
//        dd($staff_permission);
        return view('Admin.Common.home', ['menu' => $staff_permission]);
    }

    /**
     * 欢迎页
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function welcome(Request $request)
    {
        $staff_id = session('userId');
        if (empty($staff_id)) {
            return redirect('admincp/login');
        }
        $page_title = '欢迎页';
        return view('Admin.Common.welcome', ['page_title' => $page_title]);
    }

    /**
     * 提示跳转页
     * @param $message 提示信息
     * @param $second 停留秒数
     * @param $url_forward 跳转链接
     * @return Ambigous <\Illuminate\View\View, \Illuminate\Contracts\View\Factory>
     */
    public function message($message, $second = 1, $url_forward = '')
    {
        $msg_array = array(
            'msg_ok' => '操作成功！',
            'msg_add' => '添加成功！',
            'msg_edit' => '编辑成功！',
            'msg_delete' => '删除成功！',
            'msg_delete_driver_failed' => '正在使用不能被删除！',
            'msg_recovery' => '恢复成功！',
            'msg_true_delete' => '彻底删除成功！',
            'msg_import' => '导入成功！',
            'msg_submit' => '提交成功！',
            'msg_login' => '您还没有登录或登录已过期，请登录！',
            'msg_no_permission' => '您没有该操作的权限，请联系管理员！',
            'msg_none_left' => '该菜单下无左侧菜单，请联系管理员！',
            'msg_updatepassword' => '密码修改成功！',
            'msg_no_data' => '数据不存在！',
            'login_success' => '登录成功！',
            'no_permission' => '没有操作该功能的权限，请联系管理员！',
            'role_delete' => '请先删除该角色下的所有管理员，再进行此操作！',
            'permission_delete' => '请先删除该权限下的所有子权限，再进行此操作！',
            'msg_permission_parent' => '所属权限只能选择顶级或一级权限！',
            'province_delete' => '请先删除该省份下的所有城市，再进行此操作！',
            'order_permission' => '非法操作，您无权操作该订单！',
            'transaction_error' => '事务异常！',
            'confirm_receipt_ok' => '确认收款成功！',
            'receipt_exception_ok' => '收款异常成功！',
            'confirm_refund_ok' => '确认退款成功！',
            'refund_expire' => '变更保险受益人超时，无权操作！',
            'no_mortgage' => '未办理抵押手续，无权操作！',
            'no_change_insurance' => '未变更保险受益人，无权操作！',
            'camp_delete' => '该营地有员工或该营地已经调价，无法删除！',
            'type_sort_delete' => '该房型有房屋类型，无法删除！',
            'facilities_delete' => '该设施有营地使用，无法删除！',
            'send_marketing_sms' => '短信发送成功！',
        );

        $page_title = '操作提示';
        $msg = isset($msg_array[$message]) ? $msg_array[$message] : $message;
        $url = urlsafe_b64decode($url_forward);
        $wait_time = intval($second) * 1000;

        return view('admin.common.message', ['page_title' => $page_title, 'msg' => $msg, 'url' => $url, 'wait_time' => $wait_time]);
    }

    public function showMap($lng, $lat, Request $request)
    {
        $view_data['lng'] = $lng;
        $view_data['lat'] = $lat;
        return view('admin.showMap', $view_data);
    }
}
