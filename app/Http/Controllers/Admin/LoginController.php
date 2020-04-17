<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LoginController extends Controller {

    /**
     * 登录页
     * @param Request $request
     * @return Ambigous <\Illuminate\View\View, \Illuminate\Contracts\View\Factory>
     */
    public function index(Request $request)
    {
        if (!empty(session('staff_id'))) {
            return redirect("admincp/home");
        }
        return view('Admin.login');
    }
}
