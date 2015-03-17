@extends('backend.app')

@section('content')
        <div class="col-md-10">
            <div class="panel panel-default">
                <div class="panel-heading">创建分类</div>

                <div class="panel-body">
                    <form class="form-horizontal">
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">上级分类</label>
                            <div class="col-sm-3">
                                <select class="form-control" name="parent_id">
                                    <option value="0">顶级分类</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputPassword3" class="col-sm-2 control-label">分类名称</label>
                            <div class="col-sm-3">
                                <input type="text" name="cate_name" class="form-control"  placeholder="category_name">
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <button type="submit" class="btn btn-success">创建</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
@endsection
