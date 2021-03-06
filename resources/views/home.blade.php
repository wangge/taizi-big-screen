@extends('layouts.app')

@section('content')
    @php
        $img_pad = "?x-oss-process=style/tv";
    @endphp
    <div class="container-fluid main-class">

        <!-- Nav tabs -->
        <ul class="nav nav-tabs row type-tab" role="tablist">
            <li class="nav-item col">
                <a class="nav-link active" data-toggle="tab" href="#home" style="background:url('/public/images/test01.png') no-repeat center;">
                    <span class="tab-name">全部系列</span>
                    <span class="tab-center"></span>
                </a>
            </li>
            @foreach($lists as $item)
                <li class="nav-item col">
                    @if(file_exists(public_path()."uploads/".$item['image_url']))
                        <a class="nav-link @if($loop->iteration==0) active @endif" data-toggle="tab" href="#menu{!! $loop->iteration !!}" style="background:url('/public/uploads/{!! $item['image_url'] !!}')no-repeat center;">
                    @else
                        <a class="nav-link @if($loop->iteration==0) active @endif" data-toggle="tab" href="#menu{!! $loop->iteration !!}" style="background:url('https://taizicasabeifen.oss-cn-shenzhen.aliyuncs.com/uploads/{!! $item['image_url'] !!}{!! $img_pad !!}')no-repeat center;">
                    @endif
                        <span class="tab-name">{!! $item['name'] !!}</span>
                        <span class="tab-center"></span>
                    </a>
                </li>
            @endforeach
        </ul>

        <!-- Tab panes -->
        <div class="tab-content">
            <div id="home" class="tab-pane active">
                <div class="row">
                    @foreach($lists as $item)
                        @foreach($item['xilie'] as $x)
                            <div class="col-xl-3 col-lg-3 col-md-3 type-item">
                                <a href="{{route('product',['vid'=>$x['id'],'xid'=>$item['id']])}}">
                                    @if(file_exists(public_path()."uploads/".$x['image_url']))
                                    <img src="/public/uploads/{!! $x['image_url'] !!}{!! $img_pad !!}" width="100%" title="{!! $x['name'] !!}">
                                    @else
                                    <img src="https://taizicasabeifen.oss-cn-shenzhen.aliyuncs.com/uploads/{!! $x['image_url'] !!}{!! $img_pad !!}" width="100%" title="{!! $x['name'] !!}">
                                    @endif
                                </a>
                            </div>
                        @endforeach
                    @endforeach
                </div>
            </div>
            @foreach($lists as $item)
                <div id="menu{!! $loop->iteration !!}" class="tab-pane @if($loop->iteration==0) active @endif">
                    <div class="row">
                        @foreach($item['xilie'] as $x)
                        <div class="col-xl-3 col-lg-3 col-md-3 type-item">
                            <a href="{{route('product',['vid'=>$x['id'],'xid'=>$item['id']])}}">
                                @if(file_exists(public_path()."uploads/".$x['image_url']))
                                    <img src="/public/uploads/{!! $x['image_url'] !!}{!! $img_pad !!}" width="100%" title="{!! $x['name'] !!}">
                                @else
                                    <img src="https://taizicasabeifen.oss-cn-shenzhen.aliyuncs.com/uploads/{!! $x['image_url'] !!}{!! $img_pad !!}" width="100%" title="{!! $x['name'] !!}">
                                @endif
                            </a>
                        </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <script src="/public/js/app.js"></script>
    <script>
        var url = '/update/db';
        $.ajax({ url: url, success: function(){
                console.log('ok');
            }});
        var url = '/update/img';
        $.ajax({ url: url, success: function(){
                console.log('ok');
            }});
    </script>
@endsection

