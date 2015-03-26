<?php namespace App\Http\Controllers\backend;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Model\Category;

use App\Http\Requests\CateForm;

use Input,Redirect,Notification;

class CateController extends Controller {

    public function __construct(){
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

        $data = array(
            'cate'=>Category::getCategoryDataModel(),
        );
        return backendView('index',$data);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
        return backendView(
            'create',
            [
                'catArr'=>Category::getCatFieldData()
            ]
        );
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(CateForm $resuest)
	{

        try{

            if(Category::create(Category::setFieldData())){

                Notification::success('添加成功');

                return Redirect::route('backend.cate.index');
            }

        }catch (\Exception $e){

            return Redirect::back()->withErrors(array('error' => $e->getMessage()))->withInput();

        }

	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
        return backendView('edit')->withCate(Category::find($id));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id,CateForm $result)
	{
		//
        try{

            if(Category::where('id',$id)->update(Category::setFieldData())){

                Notification::success('更新成功');

                return Redirect::route('backend.cate.index');
            }

        }catch (\Exception $e){
            return Redirect::back()->withErrors(array('error' => $e->getMessage()))->withInput();
        }

	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
        if(Category::destroy($id)){
            Notification::success('删除成功');
            return Redirect::route('backend.cate.index');
        }
	}

}
