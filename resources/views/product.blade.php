@extends('layouts.app')

@section('content')
    @php
        $img_pad = "?x-oss-process=style/tv";
    @endphp
    <div class="container-fluid good-list">
        <div class="sort-good clearfix">
            <div class="btn-group btn-group-toggle float-left" data-toggle="buttons">
                <label class="btn btn-secondary @if(request('sort','all')=='all') active @endif" data-url="{!! route('product') !!}" onclick="openSort2(this);">
                    <input type="radio" name="options" id="option1" autocomplete="off" checked> 全部
                </label>
                <label class="btn btn-secondary @if(request('sort','all')=='is_new') active @endif" data-url="{!! route('product',['sort'=>'is_new','cid'=>request('cid',0),'vid'=>request('vid',0),'xid'=>request('xid',0)]) !!}" onclick="openSort2(this);">
                    <input type="radio" name="options" id="option2" autocomplete="off"> 新品
                </label>
                <label class="btn btn-secondary @if(request('sort','all')=='recommend') active @endif" data-url="{!! route('product',['sort'=>'recommend','cid'=>request('cid',0),'vid'=>request('vid',0),'xid'=>request('xid',0)]) !!}" onclick="openSort2(this);">
                    <input type="radio" name="options" id="option3" autocomplete="off"> 推荐
                </label>
            </div>
            <div class="chose-option row float-left">
                <div class="col-auto btn-group">
                    <select class="custom-select btn" id="inlineFormCustomSelect">
                        <option selected data-url="{{route('product',['cid'=>0,'vid'=>request('vid',0),'xid'=>request('xid',0)])}}">选择空间</option>
                        @if(!empty($kongjian))
                        @foreach($kongjian as $xl)
                            <option value="{!! $xl->id !!}" @if(request( 'cid',0)==$xl->id) selected @endif data-url="{{route('product',['cid'=>$xl->id,'vid'=>request('vid',0),'xid'=>request('xid',0)])}}">{{$xl->name}}</option>
                        @endforeach
                            @endif
                    </select>
                    <select class="custom-select btn" id="inlineFormCustomSelect">
                        <option selected data-url="{{route('product',['cid'=>request('cid',0),'vid'=>request('vid',0),'xid'=>request('xid',0)])}}">选择</option>
                        @if(!empty($kongjian2))
                        @foreach($kongjian2 as $xl)
                            <option value="{!! $xl->id !!}" @if(request( 'ccid',0)==$xl->id) selected @endif data-url="{{route('product',['xid'=>request('xid',0),'vid'=>request('vid',0),'cid'=>request('cid',0),'ccid'=>$xl->id])}}">{{$xl->name}}</option>
                        @endforeach
                            @endif

                    </select>
                </div>
            </div>
            <div class="chose-option row float-left">
                <div class="col-auto btn-group">
                    <select class="custom-select btn" id="inlineFormCustomSelect">
                        <option selected data-url="{{route('product',['cid'=>request('cid',0),'ccvid'=>request('ccid',0)])}}">选择风格</option>
                        @if(!empty($fengge))
                        @foreach($fengge as $xl)
                            <option value="{!! $xl->id !!}" @if(request( 'xid',0)==$xl->id) selected @endif data-url="{{route('product',['xid'=>$xl->id,'vid'=>0,'cid'=>request('cid',0),'ccid'=>request('ccid',0)])}}">{{$xl->name}}</option>
                        @endforeach
                        @endif
                    </select>
                    <select class="custom-select" id="inlineFormCustomSelect">
                        <option selected data-url="{{route('product',['cid'=>request('cid',0),'ccid'=>request('ccid',0),'xid'=>request('xid',0)])}}">选择系列</option>
                        @if(!empty($xilie))
                        @foreach($xilie as $xl)
                            <option data-url="{{route('product',['vid'=>$xl->id,'xid'=>request('xid',0),'cid'=>request('cid',0),'ccid'=>request('ccid',0)])}}" value="{!! $xl->id !!}" @if(request( 'vid',0)==$xl->id) selected @endif>{{$xl->name}}</option>
                        @endforeach
                            @endif
                    </select>
                </div>
            </div>
        </div>

        <div class="row">
            @if(!empty($result))
            @foreach($result as $pro)
                @php $thumb = explode(',',$pro->image); @endphp
                    @if(file_exists(public_path()."/". $thumb[0] ))
            <div class="col-4 good-item">
                <div class="shoucang-btn @if(in_array($pro->id,$f_product)) shoucanged-btn @endif"><button title="收藏"></button></div>

                    <a href="#" style="background:url(/public{!! $thumb[0] !!}) no-repeat center bottom;"
                   onclick="openDetailWindow(this);" data-src="{!! route('detail',['id'=>$pro->id]) !!}">
                        <div class="shadow-sm">
                            <p>{!! $pro->name !!}</p>
                        </div>
                    </a>
            </div>

                    {{--@else
                        <div class="col-4 good-item">
                            <div class="shoucang-btn @if(in_array($pro->id,$f_product)) shoucanged-btn @endif"><button title="收藏"></button></div>
                        <a href="#" style="background:url(https://taizicasabeifen.oss-cn-shenzhen.aliyuncs.com{!! $thumb[0] !!}{!! $img_pad !!}) no-repeat center bottom;"
                           onclick="openDetailWindow(this);" data-src="{!! route('detail',['id'=>$pro->id]) !!}">
                            <div class="shadow-sm">
                                <p>{!! $pro->name !!}</p>
                            </div>
                        </a>
                        </div>--}}
            @endif
            @endforeach
            @endif
        </div>
        <nav aria-label="Page navigation example">
            {{ $result->appends(request()->input())->links() }}
        </nav>
    </div>
@endsection