<?php

namespace App\Http\Controllers\Admin;

use App\Common\ResponseCode;
use App\Http\Controllers\Controller;
use App\Models\MenuModel;
use App\Models\UserModel;
use App\Models\UserRolesModel;
use App\Models\UserTokenModel;
use Illuminate\Http\Request;
use Validator;

class LoginController extends Controller {

    /**
     * 登录页
     * @param Request $request
     * @return Ambigous <\Illuminate\View\View, \Illuminate\Contracts\View\Factory>
     */
    public function index(Request $request)
    {
        if (!empty(session('userId'))) {
            return redirect("admincp/home");
        }
        return view('Admin.login');
    }

    public function store(Request $request)
    {
        $input = $request->all();
        foreach ($input as $k => $v) {
            $input[$k] = trim($v);
        }

        //检查验证码
        $rules = ['yam' => 'captcha'];
        $messages = ['captcha' => '验证码错误'];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }

        $telPhone = $input['username'];
        $password = $input['password'];
        //验证用户
        $userData = UserModel::findRecordOneCondition(['Mobile' => $telPhone])->load('getOrganizeInfo');
        if (empty($userData)) {
            return $this->_response(null, ResponseCode::STAFF_ERROR, '用户不存在');
        }
        if ($userData['IsActived'] != 1 || $userData['IsDeleted'] != 0) {
            return $this->_response(null, ResponseCode::STAFF_ERROR, '用户信息错误');
        }

        //验证密码
        if (md5($telPhone . $password) == $userData['UserPwd']) {
            $userId = $userData['UserId'];
            UserModel::updateRecordORM($userId, ['LoginTime' => date("Y-m-d H:i:s")]);

            $token = getUuid();

            $userToken = UserTokenModel::firstOrNew(['UserId' => $userId]);
            if (!empty($userToken)) {
                $userToken->UpdateTime = date("Y-m-d H:i:s");
            } else {
                $userToken->CreateTime = date("Y-m-d H:i:s");
            }
            $userToken->Token = $token;
            $userToken->UserId = $userId;
            $userToken->save();

            UserModel::updateRecordORM($userId, ['LoginTime' => date("Y-m-d H:i:s")]);

            //保存session
            $roleMapInfo = UserRolesModel::getRecordListCondition(['UserId' => $userId], ['RoleId'])->toArray();
            $roleIdInfo = ($userData['Mobile'] == 'Admin') ? 'Admin' : $roleMapInfo;
            $arr = MenuModel::getPermissionParentChildrenList($roleIdInfo);
            $result = [
                'token' => $token,
                'userId' => $userData['UserId'],
                'nickName' => $userData['NickName'],
                'workNum' => $userData['WorkNum'],
                'mobile' => $userData['Mobile'],
                'userAvatar' => $userData['UserAvatar'],
                'OrgName' => !empty($userData['getOrganizeInfo']['OrgName']) ? $userData['getOrganizeInfo']['OrgName'] : '',
                'OrgStar' => !empty($userData['getOrganizeInfo']['OrgStar']) ? $userData['getOrganizeInfo']['OrgStar'] : '',
                'permissionInfo' => $arr['permission_arr'],
                'operatingInfo' => $arr['operating_arr'],
            ];
            session($result);
            return redirect("admincp/home");
        }
        return back()->withInput()->withErrors('密码错误');
    }


    /**
     * 生成验证码
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCaptcha()
    {
        //echo captcha_img();
        return response()->json(['code' => '1', 'msg' => 'success', 'data' => captcha_src()]);
    }


    /**
     * 退出
     * @param Request $request
     */
    public function logout(Request $request)
    {
        //清session
        $request->session()->pull('userId');
        $request->session()->pull('permissionInfo');

        return redirect('admincp/login');
    }
}
