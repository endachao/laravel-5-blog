<?php
/**
 * User: 袁超<yccphp@163.com>
 * Time: 2015.04.06 下午4:38
 */
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
use Response;
class SystemForm extends BackendForm
{
    public function rules()
    {

        return [
            'cate' => 'integer',
            'system_name' => 'required',
            'system_value' => 'required',
        ];

    }
}