<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Http\Requests\Comment;
use App\Model\Comment as CommentModel;
use Response;
use Session;
use Notification;
class CommentController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Comment $result)
    {
        $attributes = $result->all();
        $attributes['type_id'] = 0;
        if (!captcha_check($attributes['captcha'])) {
            Notification::error('验证码错误');
            return redirect()->route('article.show',['id'=>$attributes['el_id'],'#commentList'])->withInput();
        }
        unset($attributes['captcha']);
        if (Session::token() !== $attributes['_token']) {
            Notification::error('token错误');
            return redirect()->route('article.show',['id'=>$attributes['el_id'],'#commentList'])->withInput();
        }
        unset($attributes['_token']);
        try {
            CommentModel::create($attributes);
            Notification::success('评论成功');
            return redirect()->route('article.show',['id'=>$attributes['el_id'],'#commentList']);
        } catch (\Exception $e) {
            Notification::error($e->getMessage());
            return redirect()->route('article.show',['id'=>$attributes['el_id'],'#commentList'])->withInput();
        }
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
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @return Response
     */
    public function update($id)
    {
        //
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
    }

}
