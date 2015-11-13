<?php

namespace App\Http\Controllers\backend;

use App\Model\Links;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\LinksRequest;
use App\Http\Controllers\Controller;
use Notification;
class LinksController extends Controller
{
    public function __construct()
    {
        conversionClassPath(__CLASS__);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return backendView('index', [
            'list' => Links::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return backendView('create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(LinksRequest $request)
    {
        //
        try {
            if (Links::create($request->all())) {
                Notification::success('添加成功');
                return redirect()->route('backend.links.index');
            }
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(array('error' => $e->getMessage()))->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        return backendView('edit', [
            'link' => Links::find($id),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(LinksRequest $request, $id)
    {
        //
        try {
            if (Links::find($id)->update($request->all())) {
                Notification::success('修改成功');
            }
            return redirect()->route('backend.links.index');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(array('error' => $e->getMessage()))->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        try {
            Links::destroy($id);
            Notification::success('删除成功');
        } catch (\Exception $e) {
            Notification::error($e->getMessage());
        }


        return redirect()->route('backend.links.index');
    }
}
