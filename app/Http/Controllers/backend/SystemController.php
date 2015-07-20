<?php namespace App\Http\Controllers\backend;

use App\Http\Requests;
use App\Http\Controllers\Controller;

//use Illuminate\Http\Request;
use App\Model\System;
use App\Http\Requests\SystemForm;
use Input, Redirect, Notification;
use Request;

class SystemController extends Controller
{

    public function __construct()
    {
        conversionClassPath(__CLASS__);
    }


    public function getIndex($type = SYSTEM::SYSTEM_INFO_TYPE)
    {

        return backendView('index', ['system' => System::where('cate', '=', $type)->get()]);
    }

    public function getCreate()
    {
        return backendView('create');
    }

    public function postCreate(SystemForm $result)
    {

        try {
            if (System::create($result->all())) {
                Notification::success('添加成功,请修改语言包文件');
                return redirect('backend/system/index');
            }
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(array('error' => $e->getMessage()))->withInput();
        }

    }

    public function getDelete($id)
    {
        if (System::destroy($id)) {
            Notification::success('删除成功');
        } else {
            Notification::success('删除失败');
        }

        return redirect()->back();
    }

    public function postStore()
    {
        $system = Request::get('system');
        if (!empty($system)) {
            foreach ($system as $K => $v) {
                System::where('system_name', '=', $K)->update(['system_value' => $v]);
            }
            Notification::success('保存成功');
            return redirect()->back();
        }
        Notification::success('保存失败');
        return redirect()->back();
    }


}
