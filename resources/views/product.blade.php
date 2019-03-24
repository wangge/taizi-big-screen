@extends('layouts.app')

@section('content')
    <div class="container-fluid good-list">
        <div class="sort-good clearfix">
            <div class="btn-group btn-group-toggle float-left" data-toggle="buttons">
                <label class="btn btn-secondary @if(!in_array(request('sort',0),['is_new','recommend'])) active @endif" data-url="{!! route('product') !!}" onclick="openSort2(this);">
                    <input type="radio" name="options" id="option1" autocomplete="off" checked> 全部
                </label>
                <label class="btn btn-secondary @if(request('sort',0)=='is_new') active @endif" data-url="{!! route('product',['sort'=>'is_new','cid'=>request('cid',0),'vid'=>request('vid',0),'xid'=>request('xid',0)]) !!}" onclick="openSort2(this);">
                    <input type="radio" name="options" id="option2" autocomplete="off"> 新品
                </label>
                <label class="btn btn-secondary @if(request('sort',0)=='recommend') active @endif" data-url="{!! route('product',['sort'=>'recommend','cid'=>request('cid',0),'vid'=>request('vid',0),'xid'=>request('xid',0)]) !!}" onclick="openSort2(this);">
                    <input type="radio" name="options" id="option3" autocomplete="off"> 推荐
                </label>
            </div>
            <div class="chose-option row float-left">
                <div class="col-auto btn-group">
                    <select class="custom-select btn" id="inlineFormCustomSelect">
                        <option selected>选择空间</option>
                        @foreach($kongjian as $xl)
                            <option value="{!! $xl->id !!}" @if(request( 'cid',0)==$xl->id) selected @endif data-url="{{route('product',['cid'=>$xl->id,'vid'=>request('vid',0),'xid'=>request('xid',0)])}}">{{$xl->name}}</option>
                        @endforeach
                    </select>
                    <select class="custom-select btn" id="inlineFormCustomSelect">
                        <option selected>选择风格</option>
                        @foreach($fengge as $xl)
                            <option value="{!! $xl->id !!}" @if(request( 'xid',0)==$xl->id) selected @endif data-url="{{route('product',['xid'=>$xl->id,'vid'=>request('vid',0),'cid'=>request('cid',0)])}}">{{$xl->name}}</option>
                        @endforeach

                    </select>
                </div>
            </div>
            <div class="chose-option row float-left">
                <div class="col-auto btn-group">
                    <select class="custom-select" id="inlineFormCustomSelect">
                        <option selected>选择系列</option>
                        @foreach($xilie as $xl)
                            <option data-url="{{route('product',['vid'=>$xl->id,'xid'=>request('xid',0),'cid'=>request('cid',0)])}}" value="{!! $xl->id !!}" @if(request( 'vid',0)==$xl->id) selected @endif>{{$xl->name}}</option>
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
                <a href="#" style="background:url(/public{!! $thumb[0] !!}) no-repeat center bottom;"
                   onclick="openDetailWindow(this);" data-src="{!! route('detail',['id'=>$pro->id]) !!}">
                    <div class="shadow-sm">
                        <p>{!! $pro->name !!}</p>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
        <nav aria-label="Page navigation example">
            {{ $result->appends(request()->input())->links() }}
        </nav>
    </div>
@endsection