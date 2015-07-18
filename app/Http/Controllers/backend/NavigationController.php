<?php namespace App\Http\Controllers\backend;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Model\Navigation;
use Illuminate\Http\Request;
use App\Http\Requests\NavigationForm;
use Notification;

class NavigationController extends Controller
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
        return backendView('index', [
            'list' => Navigation::getTreeNavigationAll(),
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
        $parentId = $request->input('parentId', 0);

        return backendView('create', [
            'parent_id' => $parentId
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(NavigationForm $request)
    {

        //
        try {
            if (Navigation::create($request->all())) {
                Notification::success('添加成功');
                return redirect()->route('backend.nav.index');
            }
        } catch (\Exception $e) {
            return redirect()->back()->withInput();
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
        return backendView('edit', [
            'nav' => Navigation::find($id),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @return Response
     */
    public function update(NavigationForm $request, $id)
    {
        //

        try {
            $data = $request->all();
            unset($data['_method']);
            unset($data['_token']);
            if (Navigation::where('id', $id)->update($data)) {
                Notification::success('修改成功');
                return redirect()->route('backend.nav.index');
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
        if (Navigation::isChildNav($id)) {
            Notification::error('该导航包含子导航，请先删除');
        } else {
            try {
                Navigation::destroy($id);
                Notification::success('删除成功');
            } catch (\Exception $e) {
                Notification::error($e->getMessage());
            }
        }

        return redirect()->route('backend.nav.index');
    }

}
