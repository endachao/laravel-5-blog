<?php namespace App\Http\Controllers\backend;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Services\Registrar;
use App\User;
use Notification;
use Validator;
use URL;
use Redirect;

class UserController extends Controller
{

    public function __construct()
    {
        conversionClassPath(__CLASS__);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
        return backendView('index', array('users' => User::orderBy('id', 'DESC')->paginate(10)));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
        return backendView('create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $register = new Registrar();
        $validator = $register->validator($request->all());
        if ($validator->fails()) {
            $this->throwValidationException(
                $request, $validator
            );
        }

        if ($register->create($request->all())) {
            Notification::success('创建用户成功');
        }

        return redirect()->route('backend.user.index');

    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function edit($id)
    {
        //
        return backendView('edit', array('user' => User::find($id)));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @return Response
     */
    public function update($id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255',
        ]);
        if ($validator->fails()) {
            $this->throwValidationException(
                $request, $validator
            );
        }
        try {

            if (User::updateUserInfo($id, $request->all())) {
                Notification::success('修改成功');
            }

        } catch (\Exception $e) {
            return redirect()->back()->withErrors(array('error' => $e->getMessage()))->withInput();
        }

        return redirect()->route('backend.user.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
        if (User::destroy($id)) {
            Notification::success('删除成功');
        }
        return redirect()->route('backend.user.index');
    }

}
