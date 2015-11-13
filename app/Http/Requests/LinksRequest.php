<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class LinksRequest extends BackendForm
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'sequence' => 'required|integer',
            'name' => 'required',
            'url' => 'required|url',
        ];
    }
}
