<nav class="navbar navbar-expand-lg navbar-light navbar-static-top site-header sticky-top py-1" style="background-color:rgba(0,0,0,0);">
    <div class="container">
        <!-- 品牌形象 -->
        <a class="navbar-brand" href="{{ url('/') }}">
            可定制电脑商城
        </a>

        <!-- data-target，data-toggle(单击显示，单击隐藏) -->
        <!-- aria-expanded表示展开状态，aria-controls表示控制的元素，aria-label表示给当前元素加上的标签描述，aria-hidden表示元素隐藏(不可见)，aria-haspopup表示点击时会出现菜单或是浮动元素 -->
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- 导航条的左侧 -->
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
                        <!-- 遍历 $categoryTree 集合，将集合中的每一项以 $category 变量注入 layouts._category_item 模板中并渲染 -->
                        <!-- 第一个参数是模板名称，第二个参数是要遍历的数组，第三个参数是遍历的项在模板中的变量名 -->
                        @each('layouts._category_item', $categoryTree, 'category')
                    </ul>
                </li>
                @endif
            </ul>

            <a class="nav-link nav-style" href="{{ url('/about') }}">
                关于我们
            </a>
            <a class="nav-link mr-auto nav-style" href="{{ url('/topics') }}">
                社区
            </a>

            <!-- 导航条的右侧 -->
            <ul class="navbar-nav navbar-right">
                <!-- 登录注册链接开始 -->
                @guest
                <!-- 身份验证链接 --> 
                <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">登录</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">注册</a></li>
                @else
                <li class="nav-item">
                    <a class="nav-link mt-1" href="{{ route('cart.index') }}">
                        <i class="fa fa-shopping-cart"></i>
                        <span class="badge badge-success">
                        @if(Auth::user()->cartItems()->count() != '0')
                            {{ Auth::user()->cartItems()->count() }}
                        @endif
                        </span>
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <img src="{{ URL::asset(Auth::user()->avatar) }}" id="avatar" class="img-responsive img-circle" width="30px" height="30px">
                        <span id="user-name">{{ Auth::user()->name }}</span>
                    </a>
                    <div class="dropdown-menu"  aria-labelledby="navbarDropdown">
                        <a href="{{ route('user_information.index') }}" class="dropdown-item">个人信息</a>
                        <a href="{{ route('user_addresses.index') }}" class="dropdown-item">收货地址</a>
                        <a href="{{ route('products.index') }}" class="dropdown-item">商品列表</a>
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
                <!-- 登录注册链接结束 -->
            </ul>
        </div>
    </div>
</nav>