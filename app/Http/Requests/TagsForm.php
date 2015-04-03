<?php
/**
 * User: 袁超<yccphp@163.com>
 * Time: 2015.04.03 下午10:19
 */
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
use Response;
class TagsForm extends BackendForm{

    public function rules()
    {

        return [
            'name' => 'required',
        ];

    }

}