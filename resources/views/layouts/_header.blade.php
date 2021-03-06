<nav class="navbar navbar-expand-lg navbar-light navbar-static-top site-header sticky-top py-1">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">
            可定制电脑商城
        </a>

        <!-- data-target，data-toggle(单击显示，单击隐藏) -->
        <!-- aria-expanded表示展开状态，aria-controls表示控制的元素，aria-label表示给当前元素加上的标签描述，aria-hidden表示元素隐藏(不可见)，aria-haspopup表示点击时会出现菜单或是浮动元素 -->
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <a class="nav-link nav-style" href="{{ url('/products') }}">
                所有商品
            </a>

            <ul class="navbar-nav">
                @if(isset($categoryTree))
                <li class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" id="categoryTree">
                        所有分类
                        <b class="caret"></b>
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="categoryTree">
                        <!-- 第一个参数是模板名称，第二个参数是要遍历的数组，第三个参数是遍历的项在模板中的变量名 -->
                        @each('layouts._category_item', $categoryTree, 'category')
                    </ul>
                </li>
                @endif
            </ul>

            <a class="nav-link nav-style" href="{{ route('custom.index', ['category_id' => '1']) }}">
                定制
            </a>

            <a class="nav-link nav-style" href="{{ url('/about') }}">
                关于我们
            </a>

            <ul class="nav navbar-nav mr-auto">
                <li class="dropdown">
                    <a href="" class="nav-link nav-style dropdown-toggle" data-toggle="dropdown">
                        社区
                        <b class="caret"></b>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="{{ active_class(if_route('topics.index')) }} nav-item"><a class="nav-link" href="{{ route('topics.index') }}">话题</a></li>
                        @foreach ($categories as $category)
                        <li class="{{ active_class((if_route('categories.show') && if_route_param('category', $category->id))) }} nav-item"><a class="nav-link" href="{{ route('categories.show', $category->id) }}">{{ $category->name }}</a></li>
                        @endforeach
                    </ul>
                </li>

                @guest
                @else
                <li class="nav-item notification-badge">
                    <a class="nav-link mr-3 badge badge-pill badge-{{ Auth::user()->notification_count > 0 ? 'hint' : 'secondary' }} text-white" href="{{ route('notifications.index') }}">
                        {{ Auth::user()->notification_count }}
                    </a>
                </li>
                @endguest
            </ul>

            <!-- 导航条的右侧 -->
            <ul class="navbar-nav navbar-right">
                @guest
                <!-- 身份验证链接 -->
                <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">登录</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">注册</a></li>
                @else
                <li class="nav-item">
                    <a class="nav-link mt-1" href="{{ route('cart.index') }}">
                        <i class="fa fa-shopping-cart"></i>
                        @if(Auth::user()->cartItems()->count() != '0')
                        <span class="badge badge-success">
                            {{ Auth::user()->cartItems()->count() }}
                        </span>
                        @endif
                    </a>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <img src="{{ URL::asset(Auth::user()->avatar) }}" id="avatar" class="img-responsive img-circle" width="30px" height="30px">
                        <span id="user-name">{{ Auth::user()->name }}</span>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a href="{{ route('user_information.index') }}" class="dropdown-item">个人信息</a>
                        <a href="{{ route('users.topicsShow') }}" class="dropdown-item">我的社区信息</a>
                        <a href="{{ route('user_addresses.index') }}" class="dropdown-item">收货地址</a>
                        <a href="{{ route('products.favorites') }}" class="dropdown-item">我的收藏</a>
                        <a href="{{ route('orders.index') }}" class="dropdown-item">历史订单</a>
                        <!-- event.preventDefault() 阻止元素发生默认的行为 -->
                        <a class="dropdown-item" id="logout" href="#" onclick="event.preventDefault();document.getElementById('logout-form').submit();">退出登录</a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            <!-- 在表单中引入CSRF令牌字段 -->
                            {{ csrf_field() }}
                        </form>
                    </div>
                </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>