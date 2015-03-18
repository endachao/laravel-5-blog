<?php
/**
 * User: 袁超<yccphp@163.com>
 * Time: 2015.03.18 下午5:23
 */
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
use Auth;
use Response;
class BackendForm extends FormRequest
{
    public function rules()
    {

    }
    public function authorize()
    {
        // 只允许登陆用户
        return Auth::check();
    }

    // 可选: 重写基类方法
    public function forbiddenResponse()
    {
        // 这个是可选的, 当认证失败时返回自定义的 HTTP 响应.
        // (框架默认的行为是带着错误信息返回到起始页面)
        // 可以返回 Response 实例, 视图, 重定向或其它信息
        return Response::make('Permission denied foo!', 403);
    }

}