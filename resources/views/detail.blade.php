<!DOCTYPE html>
<html lang="en">
@include('layouts.head')
<body>
@php $thumb = explode(',',$product->image); @endphp

<div class="good-info">
    <h2>{!! $product->name !!}</h2>
    <h3>类型: {!! $categroy->name !!} 类目: {!! $cate_name !!} 风格: {!! $attr[5] or ''!!} 系列: {!! $attr[1] or '' !!} @foreach($cate as $c) {!! $c['name'] !!}: {!! $c['value'] !!} @endforeach</h3>
</div>
<div id="carouselExampleIndicators" class="img-detail-wrap carousel slide" data-ride="carousel">

    <div class="carousel-inner">
        @foreach($thumb as $img)
            @if($loop->index<5)
            <div class="carousel-item @if($loop->index == 0) active @endif">
                <img class="d-block w-100" src="/public{!! $img !!}" alt="First slide">
            </div>
            @endif
        @endforeach
    </div>
    <div class="carousel-indicators row">
        @foreach($thumb as $img)
            @if($loop->index<5)
            <div data-target="#carouselExampleIndicators" data-slide-to="{!! $loop->index !!}" class="ca-item @if($loop->index == 0) active @endif">
                <img class="d-block w-100" src="/public{!! $img !!}">
            </div>
            @endif
        @endforeach
    </div>
    <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
    </a>
    <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
    </a>
</div>
<div class="good-info2 container">
    <h3>{!! $product->name !!}</h3>
    <hr>
    <h5>类型: {!! $categroy->name !!} 类目: {!! $cate_name !!} 风格: {!! $attr[5] or ''!!} 系列: {!! $attr[1] or '' !!} @foreach($cate as $c) {!! $c['name'] !!}: {!! $c['value'] !!} @endforeach</h5>

</div>
<script>
    if (isWideScreen) {
        setGoodInfoBottom();
    } else {
        $(".good-info").hide(0);
        $(".img-detail-wrap .carousel-indicators").css({
            "position":"relative",
            "padding-top": "15px"
        });
    }
</script>
</body>

</html>
