<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;


class PublicController extends Controller {


    public function getCaptcha(){

        return captcha('flat');
    }

}
