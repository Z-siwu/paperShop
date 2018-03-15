<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\Client;
use Socialite;
use GuzzleHttp\Client as HttpClient;
use App\User;
use Illuminate\Http\Request;
use Validator;
use EasyWeChat\Factory;

class AuthenticateController extends ApiController
{

    use AuthenticatesUsers;

    public function __construct()
    {
        $this->middleware('auth:api')->only([
            'logout'
        ]);
    }

    public function username()
    {
        return 'openid';
    }

    public function easyWechatGetSession($code)
    {
        $config = config('wechat.mini_program.default');
        $app = Factory::miniProgram($config);
        return $app->auth->session($code);
    }

    /**
     * 处理小程序的自动登陆和注册
     * @param $oauth
     */
    public function auto_login(Request $request)
    {
        // 获取openid
        if ($request->code) {
            $wx_info = $this->easyWechatGetSession($request->code);
        }
        if (!$request->openid && empty($wx_info['openid'])) {
            $this->failed('用户openid没有获取到', 6);
        }
        $openid = empty($wx_info['openid'])?$request->openid:$wx_info['openid'];
        $info = User::where('openid', $openid)->first();
        if ($info && $info->toArray()) {
            //执行登录
            $info->login_ip = $this->getClientIP();
            $info->login_time = Carbon::now();
            $info->save();
            // 直接创建token
            $token = $info->createToken($openid)->accessToken;
            $uid = $info->id;
            return $this->success(compact('token','uid'));
        } else {
            //执行注册
            return $this->register($request,$openid);
        }
    }

    /*
     * 用户注册
    * @param Request $request
    */
    public function register($request,$openid)
    {
        //  进行基本验证
        $user_info = \GuzzleHttp\json_decode($request->input('rawData'),true);
        //注册信息  字段名=》get到的值
        $newUser = [
            'openid' => $openid, //openid
            'nickname' => $user_info['nickName'],// 昵称
            'email' => 'sqc157400661@163.com',// 邮箱
            'name' => $user_info['nickName'],// 昵称
            'avatar' =>$user_info['avatarUrl'], //头像
            'unionid' => '', // unionid (可空)
            'state' => 1,
            'role' => 0,
            'password' => bcrypt('sqcweida'),
            'login_ip' => $this->getClientIP(),
            'login_time' => Carbon::now()
        ];
        //dd($newUser);
        $n_user = User::create($newUser);
        $uid = $n_user->id;
        // 直接创建token
        $token = $n_user->createToken($openid)->accessToken;
        return $this->success(compact('token','uid'));
    }

    protected function sendFailedLoginResponse(Request $request)
    {
        $msg = $request['errors'];
        $code = $request['code'];
        return $this->setStatusCode($code)->failed($msg);
    }
}
