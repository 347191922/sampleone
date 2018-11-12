<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Auth;
class SessionsController extends Controller
{
    // 显示登录页面
    public function create()
    {
        return view('sessions.create');
    }
    // 接收用户提交信息 进行验证
    public function store(Request $request)
    {
        $credentials = $this->validate($request,[
            'email' => 'required|email|max:255',
            'password' => 'required'
        ]);
        if (Auth::attempt($credentials,$request->has('remember'))){
            // 登录成功后的相关操作
            session()->flash('success','欢迎回来');
            return redirect()->route('users.show',[Auth::user()]);
        }else {
            // 登录失败后的相关操作
            session()->flash('danger', '邮箱和密码不匹配');
            return redirect()->back();
        }
    }
    // 退出登录
    public function destroy()
    {
        Auth::logout();
        session()->flash('success','已退出');
        return redirect('login');
    }
}
