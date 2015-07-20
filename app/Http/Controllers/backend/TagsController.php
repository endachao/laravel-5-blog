<?php namespace App\Http\Controllers\backend;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Input, Redirect, Notification;
use App\Model\Tag;
use App\Http\Requests\TagsForm;

class TagsController extends Controller
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
        return backendView('index', ['tags' => Tag::orderBy('id', 'desc')->paginate(10)]);
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
    public function store(TagsForm $result)
    {
        //
        try {

            if (Tag::create($result->all())) {
                Notification::success('添加成功');
                return redirect()->route('backend.tags.index');
            }

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
        return backendView('edit', ['tag' => Tag::find($id)]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @return Response
     */
    public function update(TagsForm $result, $id)
    {
        //
        try {

            if (Tag::where('id', $id)->update(['name'=>$result->input('name')])) {

                Notification::success('更新成功');

                return redirect()->route('backend.tags.index');
            }

        } catch (\Exception $e) {
            return redirect()->back()->withErrors(array('error' => $e->getMessage()))->withInput();
        }
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
        if (Tag::destroy($id)) {
            Notification::success('删除成功');
        } else {
            Notification::error('删除失败');
        }
        return redirect()->route('backend.tags.index');
    }

}
