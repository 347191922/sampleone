<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\User;
use Auth;

class UsersController extends Controller
{
    // 验证用户是否登录
    public function __construct()
    {
        $this->middleware('auth',[
           'except' => ['show','create','store','index']
        ]);
        //只让未登录用户访问注册页面
        $this->middleware('guest',[
            'only' => ['create']
        ]);
    }

    //用户列表页
    public function index()
    {
        $users = User::paginate(5);
        return view('users.index',compact('users'));
    }
    //用户注册页
    public function create()
    {
        return view('users.create');
    }

    // show 方法两个参数 第一个为 Models 下的User模型类  第二个变量会匹配路由中的{user}
    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    // 接收用户填写数据并验证
    public function store(Request $request)
    {
        // 数据验证
        $this->validate($request, [
            'name' => 'required|max:50',
            'email' => 'required|email|unique:users|max:255',
            'password' => 'required|confirmed|min:6' // confirmed 两次密码是否一致
        ]);
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);
        Auth::login($user);
        session()->flash('success', '欢迎，您将在这里开启一段新的旅程~');
        return redirect()->route('users.show', [$user]);
    }

    // 用户编辑页
    public function edit(User $user)
    {
        $this->authorize('update',$user);
        return view('users.edit', compact('user'));
    }

    //数据接收修改
    public function update(User $user, Request $request)
    {
        $this->validate($request,[
            'name'=>'required|max:50',
            'password'=> 'required|confirmed|min:6'
        ]);

        $this->authorize('update',$user);

        $data = [];
        $data['name'] = $request->name;
        if ($request->password) {
            $data['password'] = bcrypt($request->password);
        }
        $user->update($data);

        session()->flash('success','资料更新成功');
        return redirect()->route('users.show',$user->id);
    }
    // 删除 用户数据
    public function destroy(User $user)
    {
        $this->authorize('destroy',$user);
        $user->delete();
        session()->flash('success','成功删除用户！');
        return back();
    }
}
