<?php
/**
 * User: 袁超<yccphp@163.com>
 * Time: 2015.04.06 下午4:38
 */
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Response;

class NavigationForm extends BackendForm
{
    public function rules()
    {

        return [
            'sequence' => 'required|integer',
            'name' => 'required',
            'url' => 'required|url',
        ];

    }
}