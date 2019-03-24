@extends('layouts.app')

@section('content')
    <div class="container-fluid main-class">

        <!-- Nav tabs -->
        <ul class="nav nav-tabs row type-tab" role="tablist">
            @foreach($lists as $item)
                <li class="nav-item col">
                    <a class="nav-link @if($loop->iteration==1) active @endif" data-toggle="tab" href="#menu{!! $loop->iteration !!}" style="background:url(@if($loop->iteration == 1)/public/images/test0{!! $loop->iteration!!}.png @else/public/images/test0{!! $loop->iteration!!}.jpg @endif)no-repeat center;">
                        <span class="tab-name">{!! $item['name'] !!}</span>
                        <span class="tab-center"></span>
                    </a>
                </li>
            @endforeach
        </ul>

        <!-- Tab panes -->
        <div class="tab-content">
            @foreach($lists as $item)
                <div id="menu{!! $loop->iteration !!}" class="tab-pane @if($loop->iteration==1) active @endif">
                    <div class="row">
                        @foreach($item['xilie'] as $x)
                        <div class="col-xl-3 col-lg-3 col-md-3 type-item">
                            <a href="{{route('product',['vid'=>$x['id']])}}">
                                <img src="/public/uploads/{!! $x['image_url'] !!}" width="100%" title="{!! $x['name'] !!}">
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
    </script>
@endsection
