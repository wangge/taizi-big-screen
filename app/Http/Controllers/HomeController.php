<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Entities\AttributeValue,App\Entities\Category,App\Entities\Favorite,DB,App\Entities\Product,App\Entities\ProductsSku;
use App\Events\Updates;
use App\Jobs\updateDB;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $list = app(\App\Repositories\AttributeRepositoryEloquent::class)->getFengGeList(5);
        \Log::info("start at:".date('Y-m-d H:i:s'));
        return view('home',['lists'=>$list]);
    }
    public function product(){
        $xilie = AttributeValue::where('attr_id',1)->get();
        $fengge = AttributeValue::where('attr_id',5)->get();
        $kongjian = Category::where('parent_id',0)->get();
        $attr_id = request('aid',0);//全部属性
        $value_id = request('vid',0);//风格
        $xvalue_id = request('xid',0);//系列
        $cid = request('cid',0);
        $sql ='';
        $sql3 ='';
        $sql = "select DISTINCT(product_id) from product_attributes where 1=1 ";
        if($value_id && $xvalue_id){
            $sql .= " and id in (SELECT a.product_id
                        FROM (                     
                        SELECT  `product_id` 
                        FROM product_attributes
                        WHERE FIND_IN_SET( '".$value_id."', value_id )
                        ) AS a
                        INNER JOIN (    
                        SELECT  `product_id` 
                        FROM product_attributes
                        WHERE FIND_IN_SET( '".$xvalue_id."', value_id )
                        ) AS b ON a.product_id = b.product_id )";
        }
        elseif($value_id){
            $sql .= " and find_in_set('".$value_id."', value_id)";
        }
        elseif($xvalue_id){
            $sql .= " and find_in_set('".$xvalue_id."', value_id)";
        }
        if($attr_id){
            $sql .= " and attr_id='".$attr_id."'";
        }
        if($cid){
            $sql3 = "select id from category where parent_id='".$cid."'";
        }
        $sql2 = "select * from product  where 1=1 ";
        $product_r = new Product();
        $sort = request('sort','id');
        $agency_id = \Auth::user()->id;
        $f_product = Favorite::where("agency_id",$agency_id)->pluck('product_id')->toArray();
        $ids = [];
        if($sort == 'favorite' && !empty($f_product)){
            $sql2 = "select * , if(id in (".join(',',$f_product).") ,1,0) as favorite_count from product where 1=1 ";
            $ids = array_merge($ids,$f_product);
        }
        if($value_id || $xvalue_id || $attr_id){
            $ids2 = DB::select($sql);
            if(!empty($ids2)){
                foreach($ids2 as &$v){
                    $v = $v->product_id;
                }
                $ids = array_merge($ids,$ids2);
            }
            $sql2 .= "  and id in (".$sql.")";
        }
        if($cid){
            $cateIds = DB::select($sql3);
            if(!empty($cateIds)){
                foreach($cateIds as &$v){
                    $v = $v->id;
                }
                $product_r = $product_r->whereIn('category_id',$cateIds);
            }
            $sql2 .= " and category_id in(".$sql3.")";
        }
        $xinghao = request('no','');
        if($xinghao){
            $sql2 .= " and xinghao like '%".$xinghao."%'";
            $xinghao = "%".$xinghao."%";
            $product_r = $product_r->where('xinghao','like',$xinghao)->orWhere('name','like',$xinghao);
        }

        if($sort != '' && $sort != 'favorite'){
            $sql2 .= " order by ".$sort." desc ";
            $product_r = $product_r->orderBy($sort,'desc')->orderBy('id','desc');
        }
        if($sort == 'favorite'&& !empty($f_product)){
            //$product_r = $product_r->orderBy($sort,'desc');
            $sql2 .= " order by favorite_count desc ,id desc ";
        }

        if(!empty($ids)){
            $product_r = $product_r->whereIn("id",$ids);
        }

        //$result = DB::select($sql2);
        $result = $product_r->paginate(12);
        //dd($product_r->toSql());
        return view('product',['xilie'=>$xilie,'fengge'=>$fengge,'kongjian'=>$kongjian,'result'=>$result,'f_product'=>$f_product]);
    }
    public function favorite(){
        $xilie = AttributeValue::where('attr_id',1)->get();
        $fengge = AttributeValue::where('attr_id',5)->get();
        $kongjian = Category::where('parent_id',0)->get();
        $attr_id = request('aid',0);//全部属性
        $value_id = request('vid',0);//风格
        $xvalue_id = request('xid',0);//系列
        $cid = request('cid',0);
        $sql ='';
        $sql3 ='';
        $sql = "select DISTINCT(product_id) from product_attributes where 1=1 ";
        if($value_id && $xvalue_id){
            $sql .= " and id in (SELECT a.product_id
                        FROM (                     
                        SELECT  `product_id` 
                        FROM product_attributes
                        WHERE FIND_IN_SET( '".$value_id."', value_id )
                        ) AS a
                        INNER JOIN (    
                        SELECT  `product_id` 
                        FROM product_attributes
                        WHERE FIND_IN_SET( '".$xvalue_id."', value_id )
                        ) AS b ON a.product_id = b.product_id )";
        }
        elseif($value_id){
            $sql .= " and find_in_set('".$value_id."', value_id)";
        }
        elseif($xvalue_id){
            $sql .= " and find_in_set('".$xvalue_id."', value_id)";
        }
        if($attr_id){
            $sql .= " and attr_id='".$attr_id."'";
        }
        if($cid){
            $sql3 = "select id from category where parent_id='".$cid."'";
        }
        $sql2 = "select * from product  where 1=1 ";
        $product_r = new Product();
        $sort = request('sort','id');
        $agency_id = \Auth::user()->id;
        $f_product = Favorite::where("agency_id",$agency_id)->pluck('product_id')->toArray();
        $ids = [];
        $ids = array_merge($ids,$f_product);
        if($value_id || $xvalue_id || $attr_id){
            $ids2 = DB::select($sql);
            if(!empty($ids2)){
                foreach($ids2 as &$v){
                    $v = $v->product_id;
                }
                $ids = array_intersect($ids,$ids2);
            }
            $sql2 .= "  and id in (".$sql.")";
        }
        if($cid){
            $cateIds = DB::select($sql3);
            if(!empty($cateIds)){
                foreach($cateIds as &$v){
                    $v = $v->id;
                }
                $product_r = $product_r->whereIn('category_id',$cateIds);
            }
            $sql2 .= " and category_id in(".$sql3.")";
        }
        $xinghao = request('no','');
        if($xinghao){
            $sql2 .= " and xinghao like '%".$xinghao."%'";
            $xinghao = "%".$xinghao."%";
            $product_r = $product_r->where('xinghao','like',$xinghao)->orWhere('name','like',$xinghao);
        }

        if($sort != '' && $sort != 'favorite'){
            $sql2 .= " order by ".$sort." desc ";
            $product_r = $product_r->orderBy($sort,'desc');
        }

        if(!empty($ids)){
            $product_r = $product_r->whereIn("id",$ids);
        }

        //$result = DB::select($sql2);
        $result = $product_r->paginate(12);
        //dd($product_r->toSql());
        //dd($result);
        return view('favorite',['xilie'=>$xilie,'fengge'=>$fengge,'kongjian'=>$kongjian,'result'=>$result,'f_product'=>$f_product]);
    }
    public function detail($id){
        $product = Product::find($id);
        $attr = $product->attributes;
        $attributes = [];
        foreach($attr as $a){
            $value_id = $a->value_id;
            $value_name = [];
            foreach($value_id as $id){
                $value_name[] = AttributeValue::find($id)->name;
            }
            $attributes[$a->attr_id] = join(' ',$value_name);
        }
        $categroy = $product->category;
        $sku = [];
        foreach($product->sku as $sk){
            $sku[] = $sk->title;
        }
        $cate = [];
        $cate_name_arr = [
            1=>'床',
            2=>'柜/餐桌/椅',
            3=>'沙发',
            4=>'床垫',
        ];
        $cate_name = isset($cate_name_arr[$product->cate]) ? $cate_name_arr[$product->cate]:'';
        if($product->cate == 1){
            $cate = [
                'chicun' => ['name'=>'床尾尺寸','value'=>$product->chicun],
                'chuangtou_chicun' => ['name'=>'床头尺寸','value'=>$product->chuangtou_chicun],
                'mianliao' => ['name'=>'面料','value'=>$product->mianliao],
                'tianchongwu' => ['name'=>'填充物','value'=>$product->tianchongwu],
                'neiwaicaizhi' => ['name'=>'内外材质','value'=>$product->neiwaicaizhi],
                'chuangjiao' => ['name'=>'床脚','value'=>$product->chuangjiao],
                'paigujia' => ['name'=>'排骨架','value'=>$product->paigujia],
            ];

        }
        if($product->cate == 2){
            $cate = [
                'chicun2' => ['name'=>'尺寸','value'=>$product->chicun2],
                'yanse' => ['name'=>'颜色','value'=>$product->yanse],
                'taimian' => ['name'=>'台面','value'=>$product->taimian],
            ];
        }
        if($product->cate == 3){
            $cate = [
                'yirenwei' => ['name'=>'一人位','value'=>$product->yirenwei],
                'liangrenwei' => ['name'=>'两人位','value'=>$product->liangrenwei],
                'sanrenwei' => ['name'=>'三人位','value'=>$product->sanrenwei],
                'sirenwei' => ['name'=>'四人位','value'=>$product->sirenwei],
                'tianchongwu3' => ['name'=>'填充物','value'=>$product->tianchongwu3],
                'mianliao3' => ['name'=>'面料','value'=>$product->mianliao3],
                'waijia' => ['name'=>'外架','value'=>$product->waijia],
                'fei' => ['name'=>'妃','value'=>$product->fei],
                'fu' => ['name'=>'2扶','value'=>$product->fu],
                'danfu' => ['name'=>'单扶','value'=>$product->danfu],
                'zhuan' => ['name'=>'转','value'=>$product->zhuan],
                'dan' => ['name'=>'单','value'=>$product->dan],
                'jiaokao' => ['name'=>'脚靠','value'=>$product->jiaokao],
            ];
        }
        if($product->cate == 4){
            $cate = [
                'chicun4' => ['name'=>'尺寸','value'=>$product->chicun4],
                'houdu' => ['name'=>'厚度','value'=>$product->houdu],
                'huanbaodengji' => ['name'=>'环保等级','value'=>$product->huanbaodengji],
                'raunyingdu' => ['name'=>'软硬度','value'=>$product->raunyingdu],
                'mianliao4' => ['name'=>'面料','value'=>$product->mianliao4],
                'tanhuang' => ['name'=>'弹簧','value'=>$product->tanhuang],
                'tianchongwu4' => ['name'=>'填充物','value'=>$product->tianchongwu4],
            ];
        }
        return view('detail',['product'=>$product,'attr'=>$attributes,'categroy'=>$categroy ,'sku'=>join(' ',$sku),'cate'=>$this->filterEmpty($cate),'cate_name'=>$cate_name]);

    }
    private function filterEmpty($arr){
        foreach($arr as $key=>$value)
        {
            if(empty($value['value']) || trim($value['value']) == ''){
                unset($arr[$key]);
            }
        }
        return $arr;
    }
}
