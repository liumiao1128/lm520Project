<?php

namespace App\Common;

use App\Models\UserTokenModel;

class UserToken
{
    /**
     * 产生token
     * @param $user_id
     * @return mixed
     */
    public static function createToken($user_id)
    {
        $user_record = UserTokenModel::findRecordOneCondition(['UserId' => $user_id]);
        $token = getUuid();
        if (empty($user_record)) {
            $user_token_data = [
                'UserId' => $user_id,
                'token' => $token
            ];
            UserTokenModel::create($user_token_data);
        } else {
            UserTokenModel::updateRecordORM($user_record['user_token_id'], ['token' => $token]);
        }
        return $token;
    }

    /**
     * 检查token是否有效
     * @param $token
     */
    public static function checkToken($token)
    {
        $user_record = UserTokenModel::findRecordOneCondition(['token' => $token]);
        if (empty($user_record)) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * 根据token获取客户信息
     * @param $token
     * @return object
     */
    public static function getUserToken($token)
    {
        $user = UserTokenModel::findRecordOneCondition(['token' => $token])->UserId;
        if (empty($user)) {
            return false;
        } else {
            return $user;
        }
    }
}
