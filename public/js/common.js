// 效果控制

//全局变量
const windowHeight = window.innerHeight;
const windowWidth = window.innerWidth;
const isWideScreen = windowWidth > windowHeight; //宽屏判断

function resizeItemHeight(params) {
    let itemWidth = $(".good-item").width();
    $(".good-item").height(itemWidth / 1.78);
}
$(window).resize(function () {
    setTimeout(() => {
        resizeItemHeight();
    }, 500);
});
window.onload = function () {
    resizeItemHeight();
}

//控制古天乐照片
$(function () {
    let GTL_DIV = $(".gutianle-xixi")
    let GTL_IMG = $(".gutianle-xixi img")
    if (!isWideScreen) {
        // GTL_DIV.css("height", "100%");
        GTL_IMG.css("height", "100%");
    } else {
        GTL_DIV.css("height", "100%");
        GTL_IMG.css({
            "width": "auto",
            "height": ""
        });
    }
});

function openDetailWindow(obj) {
    let IMG_URL = $(obj).attr("data-src");
    let iframeHtml = `
    <div class="hide-detail-window">
    <div class="window-console">
        <a href="javascript:;" class="closewindow" onclick="closewindow();"></a>
    </div>
    <iframe src="${ IMG_URL}" scrolling="no"></iframe>
    </div>
    `
    $("body").append(iframeHtml).css("overflow", "hidden");
}
function closewindow() {
    $(".hide-detail-window").remove();
    $("body").css({
        "overflow": "auto",
    });
}

//打开筛选链接

$(document).ready(function () {
    $(".chose-option .custom-select").change(function (e) { 
        let SORT_URL = $(this).children("option:selected").attr("data-url");
        window.location.href = SORT_URL
    });
});


//控制详情窗口
$(function () {
    function setFullCarouselWindow() {
        $(".img-detail-wrap").css({
            "position": "absolute",
            "height": windowHeight,
            "width": "100%",
            "overflow": "hidden"
        });
    }
    function resetFullCarouselWindow() {
        $(".img-detail-wrap").css({
            "position": "relative",
            "height": "auto",
            "width": "auto",
        });
    }
    //初始化
    if (isWideScreen) {
        setFullCarouselWindow();
    }
    else {
        resetFullCarouselWindow();
    }
})

function setGoodInfoBottom() {
    let xxHeight = $(".img-detail-wrap .carousel-indicators").height();
    $(".good-info").css("bottom", xxHeight + 25);
    console.log(xxHeight);
}


function openSort2(obj) {
    let SORT2_URL = $(obj).attr("data-url");
    window.location.href = SORT2_URL
}