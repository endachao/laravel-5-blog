<?php namespace App\Http\Requests;

use App\Http\Requests\Request;

class Comment extends Request {

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return true;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
			'email'=>'required|email',
            'username'=>'required',
            'parent_id'=>'required|numeric',
            'content'=>'required|max:1000',
            'captcha'=>'required',
		];
	}

}
