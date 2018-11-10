<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\User;

class UsersController extends Controller
{
    //用户注册页
    public function create()
    {
        return view('users.create');
    }
    // show 方法两个参数 第一个为 Models 下的User模型类  第二个变量会匹配路由中的{user}
    public function show(User $user)
    {
        return view('users.show',compact('user'));
    }
    // 接收用户填写数据并验证
    public function store(Request $request)
    {
        // 数据验证
        $this->validate($request,[
            'name' => 'required|max:50',
            'email' => 'required|email|unique:users|max:255',
            'password' => 'required|confirmed|min:6' // confirmed 两次密码是否一致
        ]);
        $user = User::create([
           'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);
        session()->flash('success', '欢迎，您将在这里开启一段新的旅程~');
        return redirect()->route('users.show',[$user]);
    }
}
