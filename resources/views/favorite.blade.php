@extends('layouts.app')

@section('content')
    @php
        $img_pad = "?x-oss-process=style/tv";
    @endphp
    <div class="container-fluid good-list">
        <div class="sort-good clearfix">
            <div class="btn-group btn-group-toggle float-left" data-toggle="buttons">
                <label class="btn btn-secondary @if(request('sort','all')=='all') active @endif" data-url="{!! route('favorite') !!}" onclick="openSort2(this);">
                    <input type="radio" name="options" id="option1" autocomplete="off" checked> 全部
                </label>
                <label class="btn btn-secondary @if(request('sort','all')=='is_new') active @endif" data-url="{!! route('favorite',['sort'=>'is_new','cid'=>request('cid',0),'vid'=>request('vid',0),'xid'=>request('xid',0)]) !!}" onclick="openSort2(this);">
                    <input type="radio" name="options" id="option2" autocomplete="off"> 新品
                </label>
                <label class="btn btn-secondary @if(request('sort','all')=='recommend') active @endif" data-url="{!! route('favorite',['sort'=>'recommend','cid'=>request('cid',0),'vid'=>request('vid',0),'xid'=>request('xid',0)]) !!}" onclick="openSort2(this);">
                    <input type="radio" name="options" id="option3" autocomplete="off"> 推荐
                </label>
            </div>
            <div class="chose-option row float-left">
                <div class="col-auto btn-group">
                    <select class="custom-select btn" id="inlineFormCustomSelect">
                        <option selected>选择空间</option>
                        @foreach($kongjian as $xl)
                            <option value="{!! $xl->id !!}" @if(request( 'cid',0)==$xl->id) selected @endif data-url="{{route('favorite',['cid'=>$xl->id,'vid'=>request('vid',0),'xid'=>request('xid',0),'ccid'=>request('ccid',0)])}}">{{$xl->name}}</option>
                        @endforeach
                    </select>
                    <select class="custom-select btn" id="inlineFormCustomSelect">
                        <option selected>选择</option>
                        @foreach($kongjian2 as $xl)
                            <option value="{!! $xl->id !!}" @if(request( 'ccid',0)==$xl->id) selected @endif data-url="{{route('favorite',['xid'=>request('xid',0),'vid'=>request('vid',0),'cid'=>request('cid',0),'ccid'=>$xl->id])}}">{{$xl->name}}</option>
                        @endforeach

                    </select>
                </div>
            </div>
            <div class="chose-option row float-left">
                <div class="col-auto btn-group">
                    <select class="custom-select btn" id="inlineFormCustomSelect">
                        <option selected>选择风格</option>
                        @foreach($fengge as $xl)
                            <option value="{!! $xl->id !!}" @if(request( 'xid',0)==$xl->id) selected @endif data-url="{{route('favorite',['xid'=>$xl->id,'vid'=>request('vid',0),'cid'=>request('cid',0),'ccid'=>request('ccid',0)])}}">{{$xl->name}}</option>
                        @endforeach

                    </select>
                    <select class="custom-select" id="inlineFormCustomSelect">
                        <option selected>选择系列</option>
                        @foreach($xilie as $xl)
                            <option data-url="{{route('favorite',['vid'=>$xl->id,'xid'=>request('xid',0),'cid'=>request('cid',0),'ccid'=>request('ccid',0)])}}" value="{!! $xl->id !!}" @if(request( 'vid',0)==$xl->id) selected @endif>{{$xl->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="row">
            @foreach($result as $pro)
                @php $thumb = explode(',',$pro->image); @endphp
                <div class="col-4 good-item">
                    <div class="shoucang-btn @if(in_array($pro->id,$f_product)) shoucanged-btn @endif"><button title="收藏"></button></div>
                    @if(file_exists(public_path()."/". $thumb[0] ))
                        <a href="#" style="background:url(/public{!! $thumb[0] !!}) no-repeat center bottom;"
                           onclick="openDetailWindow(this);" data-src="{!! route('detail',['id'=>$pro->id]) !!}">
                            <div class="shadow-sm">
                                <p>{!! $pro->name !!}</p>
                            </div>
                        </a>
                    @else
                        <a href="#" style="background:url(https://taizicasabeifen.oss-cn-shenzhen.aliyuncs.com{!! $thumb[0] !!}{!! $img_pad !!}) no-repeat center bottom;"
                           onclick="openDetailWindow(this);" data-src="{!! route('detail',['id'=>$pro->id]) !!}">
                            <div class="shadow-sm">
                                <p>{!! $pro->name !!}</p>
                            </div>
                        </a>
                    @endif

                </div>
            @endforeach
        </div>
        <nav aria-label="Page navigation example">
            {{ $result->appends(request()->input())->links() }}
        </nav>
    </div>
@endsection