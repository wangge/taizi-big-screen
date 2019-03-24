<!DOCTYPE html>
<html lang="en">
@include('layouts.head')
<body>
<div class="login-bg">
    <div class="login-box">
        <div class="login-logo"><img src="/public/images/loading_logo.png" width="100%"></div>
        <form method="POST" action="{{ route('login') }}">
            {{ csrf_field() }}
            <div class="mb-3">
                <div class="input-group text-input">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><img src="/public/images/ico_pwd@2x.png" width="100%"></span>
                    </div>
                    <input type="text" class="form-control" id="username" placeholder="请输入经销商账号" required="" name="username">
                    @if ($errors->has('username'))
                        <span class="help-block">
                                        <strong>{{ $errors->first('username') }}</strong>
                                    </span>
                    @endif
                </div>
                <div class="input-group text-input">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><img src="/public/images/ico_username@2x.png" width="100%"></span>
                    </div>
                    <input type="password" class="form-control" id="pwd" placeholder="请输入密码" required="" name="password">
                    @if ($errors->has('password'))
                        <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                    @endif
                </div>
            </div>

            <button class="btn btn-primary btn-lg btn-block" type="submit">Get Started</button>
        </form>
        <p class="mt-3 text-left"><a class="login-tips" href="javascript:;" onclick="alert('登陆官网修改或联系客服修改')">忘记密码？</a>请登录官网或联系客服修改</p>
    </div>
    <div class="gutianle-xixi">
        <img src="/public/images/login_pic_01.jpg" height="100%">
    </div>
</div>
</body>

</html>



