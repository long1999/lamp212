<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Admin\User;
use Hash;
use DB;
use Carbon\Carbon;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //查找表中所有的数据
        $rs = DB::table('auser')->get();
        //获取当前时间
        $time = Carbon::now();
        //用户列表页面
        return view('admin.user.user-list',['title'=>'用户浏览','rs'=>$rs]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //用户的添加页面
        return view('admin.user.add',['title'=>'用户添加页面']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //接收表单传过来的数据
        //表单验证
        $this->validate($request, [
            'username' => 'required|unique:auser|regex:/\w{6,16}/',
            'password' => 'required|regex:/\w{6,12}/',
            'repass' => 'same:password',
            'email' =>'email',
            'phone' => 'regex:/1[3456789]\d{9}/'
        ],[
            'username.required' => '用户名不能为空',
            'username.regex' => '用户名格式不正确',
            'password.required'  => '密码不能为空',
            'password.regex'  => '密码格式不正确',
            'repass.same' =>'两次密码不一致',
            'email.email'=>'邮箱格式不正确',
            'phone.regex'=>'手机号码格式不正确'
        ]);
        //获取数据
        $res = $request->except(['repass','_token','pic']);

        //头像处理
        if(!$request->hasFile('pic')){

            echo '没有选择文件上传';die;
        } else {

            $file = $request->file('pic');

            //设置名字
            $name = rand(1111,9999).time();

            //获取后缀
            $suffix = $file->getClientOriginalExtension();

            //移动文件
            $file->move('./uploads', $name.'.'.$suffix);

            //存到数据库
            $res['pic'] = '/uploads/'.$name.'.'.$suffix;
        }

        //密码  加密
        //hash  加密解密
        $res['password'] = Hash::make($request->password);

        //添加数据
        try{

            //添加
             $data = User::create($res);

            
            if($data){
                return redirect('/admin/user');
            }

        }catch(\Exception $e){

            return back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
