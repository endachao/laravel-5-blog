<?php namespace App\Http\Controllers\backend;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Model\Comment;
use Validation;
use Notification;
use App\Events\CommentSendEmail;
class CommentController extends Controller
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
        $model = Comment::orderBy('id', 'DESC')->paginate(10);

        return backendView('index', [
            'commentList' => $model
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(Request $request)
    {
        //
        $id = $request->input('id');
        return backendView('create', ['id' => $id]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'content' => 'required',
        ]);


        $id = $request->input('parent_id');
        $commentInfo = Comment::find($id);
        $userInfo = $request->user();


        try {
            $data = [
                'el_id' => $commentInfo->el_id,
                'type_id' => $commentInfo->type_id,
                'username' => $userInfo->name,
                'email' => $userInfo->email,
                'parent_id' => $id,
                'content' => $request->input('content')
            ];

            Comment::create($data);
            Notification::success('回复成功');
            event(new CommentSendEmail($id));
            return redirect()->route('backend.comment.index');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(array('error' => $e->getMessage()))->withInput();
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
        $commentInfo = Comment::find($id);
        if (empty($commentInfo)) {
            Notification::error('查看的评论已被删除');
            return redirect()->back();
        }
        return backendView('show', ['commentInfo' => $commentInfo]);

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
        if (Comment::destroy($id)) {
            Notification::success('删除成功');
        } else {
            Notification::success('删除失败');
        }
        return redirect()->back();

    }

}
