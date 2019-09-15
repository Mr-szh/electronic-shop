<nav class="navbar navbar-expand-lg navbar-light bg-light navbar-static-top">
    <div class="container">
        <!-- 品牌形象 -->
        <a class="navbar-brand " href="{{ url('/') }}">
            可定制电脑商城
        </a>
        <!-- data-target，data-toggle(单击显示，单击隐藏) -->
        <!-- aria-expanded表示展开状态，aria-controls表示控制的元素，aria-label表示给当前元素加上的标签描述，aria-hidden表示元素隐藏(不可见)，aria-haspopup表示点击时会出现菜单或是浮动元素 -->
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- 导航条的左侧 -->
            <ul class="navbar-nav mr-auto">

            </ul>

            <!-- 导航条的右侧 -->
            <ul class="navbar-nav navbar-right">
                <!-- 登录注册链接开始 -->
                @guest
                <!-- 身份验证链接 --> 
                <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">登录</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">注册</a></li>
                @else
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <img src="{{ URL::asset(Auth::user()->avatar) }}" id="avatar" class="img-responsive img-circle" width="30px" height="30px">
                        <span id="user-name">{{ Auth::user()->name }}</span>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a href="{{ route('user_information.index') }}" class="dropdown-item">个人信息</a>
                        <a href="{{ route('user_addresses.index') }}" class="dropdown-item">收货地址</a>
                        <a href="{{ route('products.index') }}" class="dropdown-item">商品列表</a>
                        <a href="{{ route('products.favorites') }}" class="dropdown-item">我的收藏</a>
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