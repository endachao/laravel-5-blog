<div id="menu" class="menu-right">
    <ul>
        <form class="menu-search" >
            <div class="form-group header">
                <i class="icon-search searchico"></i>
                <input type="text" placeholder="搜索">
                <a href="#" class="close-menu"><i class="icon-close"></i></a>
            </div>
        </form>

        <?php $menu = App\Model\Navigation::getNavList() ?>
        @if(!empty($menu))
            @foreach($menu as $key=> $nav)
                <li @if(isset($nav->child)) class="submenu" @endif>

                    <a href="@if(isset($nav->child)) javascript:void(0) @else {{ $nav->url }} @endif">
                        <i class="icon-lime"></i>
                        @if(isset($nav->child))
                            <b class="caret"></b>
                        @endif
                        {{ $nav->name }}
                    </a>
                    @if(isset($nav->child))
                        <ul class="submenu-list">
                            @foreach($nav->child as $child)
                                <li>
                                    <a href="{{ $child->url }}">{{ $child->name }}</a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </li>
            @endforeach
        @endif
</div>