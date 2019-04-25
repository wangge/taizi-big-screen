<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
@include('layouts.head')
<body>
<header>
    <nav class="navbar navbar-expand-md navbar-dark fixed-top">
        <a class="navbar-brand" href="/"><img src="/public/images/ico_logo.png"></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse"
                aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <span>{{ Auth::user()->company_name }}</span>
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <ul class="navbar-nav mr-auto">

                <li class="nav-item active">
                    <a class="nav-link" href="/">首页</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{!! route('favorite') !!}">我的收藏</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="http://www.taizicasa.com/">浏览官网</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('logout') }}"
                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                        退出登录
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        {{ csrf_field() }}
                    </form>
                </li>
            </ul>
            <form class="form-inline mt-2 mt-md-0 main-search" action="{{route('product')}}">
                <input class="form-control" type="text" placeholder="输入产品名或型号" aria-label="Search" name="no" value="{!! request('no','') !!}">
                <button class="btn my-2 my-sm-0" type="submit">搜索</button>
            </form>
        </div>
    </nav>
</header>
@yield('content')
<div class="copyright">
    Copyright © 2008-2019 TAIZI CASA All Right Reserved.<br> 太子家居版权所有
    <a href="http://www.miitbeian.gov.cn">蜀ICP备16020576号</a>
</div>
</body>
</html>
