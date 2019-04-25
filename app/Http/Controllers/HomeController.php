<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Entities\AttributeValue,App\Entities\Category,App\Entities\Favorite,DB,App\Entities\Product,App\Entities\ProductsSku;
use App\Events\Updates;
use App\Jobs\updateDB;
use Cache;
use Auth;


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
        /*echo '<pre>';
        print_r($list);die;*/
        return view('home',['lists'=>$list]);
    }
    public function product(){
        //$xilie = AttributeValue::where('attr_id',1)->get();
        $xilie = null;
        $fengge = AttributeValue::where('attr_id',5)->where('id','<>',10)->get();
        $kongjian = Category::where('parent_id',0)->get();
        $kongjian2 = [];
        $attr_id = request('aid',0);//全部属性
        $value_id = request('vid',0);//风格
        $xvalue_id = request('xid',0);//系列
        if($xvalue_id){
            $xilie =  AttributeValue::where('parent_value_id',$xvalue_id)->get();
        }
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
            $kongjian2 = Category::where('parent_id',$cid)->get();
        }
        $ccid = request('ccid',0);
        if($ccid){
            $sql3 = "select id from category where parent_id='".$ccid."'";
        }
        $sql3 .= "and site in(1,3)";
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
        return view('product',['xilie'=>$xilie,'fengge'=>$fengge,'kongjian'=>$kongjian,'result'=>$result,'f_product'=>$f_product,'kongjian2'=>$kongjian2]);
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
        $kongjian2 =[];
        if($cid){
            $sql3 = "select id from category where parent_id='".$cid."'";
            $kongjian2 = Category::where('parent_id',$cid)->get();
        }
        $ccid = request('ccid',0);
        if($ccid){
            $sql3 = "select id from category where parent_id='".$ccid."'";
        }
        $sql2 = "select * from product  where 1=1 ";
        $product_r = new Product();
        $sort = request('sort','id');
        $agency_id = \Auth::user()->id;
        $f_product = Favorite::where("agency_id",$agency_id)->pluck('product_id')->toArray();
        $ids = [];
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
            $ids = array_merge($f_product,$ids);
            $product_r = $product_r->whereIn("id",$ids);
        }else{
            $product_r = $product_r->whereIn("id",$f_product);
        }

        //$result = DB::select($sql2);
        $result = $product_r->paginate(12);
        //dd($product_r->toSql());
        //dd($result);
        return view('favorite',['xilie'=>$xilie,'fengge'=>$fengge,'kongjian'=>$kongjian,'result'=>$result,'f_product'=>$f_product,'kongjian2'=>$kongjian2]);
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

    public function updataImage(){
        set_time_limit(0);
        \Log::info('update image');
        $img_list = $this->read_all(public_path().'/uploads');
        $images = $this->httpGet("http://www.taizicasa.com/api/getImagesList");
        $images = json_decode($images,true);
        $diff_images = array_diff($images['data']['images'],$img_list);
        unset($images);
        unset($img_list);
        if(!empty($diff_images)){
            foreach($diff_images as $img){
                $this->download_image($img);
            }
        }
        return 0;
    }
    public function updateDB(){
        set_time_limit(0);
        //phpinfo();die;
        \Log::info('update data');
        $update_date = Cache::has('update_date') ? Cache::get('update_date'): date('Y-m-d');
        $agency_id = Auth::user()->id;
        $attr_max_id = app(\App\Entities\Attribute::class)->orderBy('id','desc')->first()->id;
        $attr_value_max_id = app(\App\Entities\AttributeValue::class)->orderBy('id','desc')->first()->id;
        $category_max_id = app(\App\Entities\Category::class)->orderBy('id','desc')->first()->id;
        $product_max_id = app(\App\Entities\Product::class)->orderBy('id','desc')->first()->id;
        $favorite_max_id = app(\App\Entities\Favorite::class)->orderBy('id','desc')->first()->id;
        $sku_max_id = app(\App\Entities\ProductsSku::class)->orderBy('id','desc')->first()->id;
        $product_attr_max_id = app(\App\Entities\ProductAttributes::class)->orderBy('id','desc')->first()->id;

        $post_data = [
            'agency_id' => $agency_id,
            'attr_max_id' => $attr_max_id,
            'attr_value_max_id' => $attr_value_max_id,
            'category_max_id' => $category_max_id,
            'product_max_id' => $product_max_id,
            'update_date' => $update_date,
        ];
        $response = $this->httpPost("http://www.taizicasa.com/api/getUpdate",$post_data);
        $data = json_decode($response,true);
        if($data['error']==0){
            Cache::forever('update_date', date('Y-m-d'));
            //update Agency
            $agency = $data['data']['agency'];
            if(!empty($agency)){
                unset($agency['id']);
                app(\App\Repositories\AgencyRepositoryEloquent::class)->update($agency,$agency_id);
            }
            $attribute = $data['data']['new_attr'];
            if(!empty($attribute)){
                foreach($attribute as $attr){
                    $id = $attr['id'];

                    if($id>$attr_max_id){
                        app(\App\Repositories\AttributeRepositoryEloquent::class)->create($attr);
                    }else{
                        unset($attr['id']);
                        app(\App\Repositories\AttributeRepositoryEloquent::class)->update($attr,$id);
                    }

                }
            }
            $attribute_value = $data['data']['new_attr_value'];
            if(!empty($attribute_value)){
                foreach($attribute_value as $attr){
                    $id = $attr['id'];
                    if($id>$attr_value_max_id){
                        app(\App\Repositories\AttributeValueRepositoryEloquent::class)->create($attr);
                    }else{
                        unset($attr['id']);
                        app(\App\Repositories\AttributeValueRepositoryEloquent::class)->update($attr,$id);
                    }
                }
            }
            $category = $data['data']['new_category'];
            if(!empty($category)){
                foreach($category as $attr){
                    $id = $attr['id'];
                    if($id>$category_max_id){
                        app(\App\Repositories\CategoryRepositoryEloquent::class)->create($attr);
                    }else{
                        unset($attr['id']);
                        app(\App\Repositories\CategoryRepositoryEloquent::class)->update($attr,$id);
                    }
                }
            }
            $favorite = $data['data']['favorite'];
            if(!empty($favorite)){
                foreach($favorite as $attr){
                    $id = $attr['id'];
                    if($id>$favorite_max_id){
                        app(\App\Repositories\FavoriteRepositoryEloquent::class)->create($attr);
                    }else{
                        unset($attr['id']);
                        app(\App\Repositories\FavoriteRepositoryEloquent::class)->update($attr,$id);
                    }
                }
            }
            $product = $data['data']['new_product'];
            if(!empty($product)){


                foreach($product as $attr){
                    $sku = $attr['sku'];
                    $attributes = $attr['attributes'];
                    unset($attr['sku']);
                    unset($attr['attributes']);
                    $id = $attr['id'];
                    if($id>$product_max_id){
                        app(\App\Repositories\ProductRepositoryEloquent::class)->create($attr);
                    }else{
                        unset($attr['id']);
                        app(\App\Repositories\ProductRepositoryEloquent::class)->update($attr,$id);
                    }
                    if(!empty($sku)){
                        foreach($sku as $s){
                            if($s['id'] > $sku_max_id){
                                app(\App\Repositories\ProductsSkuRepositoryEloquent::class)->create($s);
                            }else{
                                $id = $s['id'];
                                unset($s['id']);
                                app(\App\Repositories\ProductsSkuRepositoryEloquent::class)->update($s,$id);
                            }
                        }
                    }

                    if(!empty($attributes)){
                        foreach ($attributes as $s){
                            if($s['id'] > $product_attr_max_id){
                                app(\App\Repositories\ProductAttributesRepositoryEloquent::class)->create($s);
                            }else{
                                $id = $s['id'];
                                unset($s['id']);
                                app(\App\Repositories\ProductAttributesRepositoryEloquent::class)->update($s,$id);
                            }
                        }
                    }

                }
            }

        }


        return 0;
    }
    private function httpGet($uri){
        $curl = curl_init();
        //设置抓取的url
        curl_setopt($curl, CURLOPT_URL, $uri);
        //设置头文件的信息作为数据流输出
        curl_setopt($curl, CURLOPT_HEADER, 1);
        //设置获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        //执行命令
        $data = curl_exec($curl);
        $headerSize = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
        $body = substr($data, $headerSize);
        //关闭URL请求
        curl_close($curl);
        //显示获得的数据
        return $body;
    }
    private function httpPost($uri, $post_data){
        //初始化
        $curl = curl_init();
        //设置抓取的url
        curl_setopt($curl, CURLOPT_URL, $uri);
        //设置头文件的信息作为数据流输出
        curl_setopt($curl, CURLOPT_HEADER, 1);
        //设置获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        //设置post方式提交
        curl_setopt($curl, CURLOPT_POST, 1);
        //设置post数据
        /*$post_data = array(
            "username" => "coder",
            "password" => "12345"
        );*/
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($post_data));
        //执行命令
        $data = curl_exec($curl);
        $headerSize = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
        $body = substr($data, $headerSize);

        //关闭URL请求
        curl_close($curl);
        //显示获得的数据
        return $body;

    }
    private function read_all ($dir)
    {
        if (!is_dir($dir)) return [];
        $imgs = [];
        $handle = opendir($dir);
        if ($handle) {
            while (($fl = readdir($handle)) !== false) {
                $temp = $dir . DIRECTORY_SEPARATOR . $fl;
                //如果不加  $fl!='.' && $fl != '..'  则会造成把$dir的父级目录也读取出来
                if (is_dir($temp) && $fl != '.' && $fl != '..' && $fl != 'files') {
                    $imgs =array_merge($imgs,$this->read_all($temp)) ;
                } else {
                    if ($fl != '.' && $fl != '..'&& $fl != 'files') {
                        if($fl == 'css.php') continue;
                        $imgs[] = explode('public',$temp)[1];
                    }
                }
            }
        }
        return $imgs;
    }
    /**
     * 下载远程图片到本地
     *
     * @param string $url 远程文件地址
     * @param string $filename 保存后的文件名（为空时则为随机生成的文件名，否则为原文件名）
     * @param array $fileType 允许的文件类型
     * @param string $dirName 文件保存的路径（路径其余部分根据时间系统自动生成）
     * @param int $type 远程获取文件的方式
     * @return json 返回文件名、文件的保存路径
     * @author blog.snsgou.com
     */
    private function download_image($file, $fileType = array('jpg', 'gif', 'png'))
    {
        $url = "https://taizicasabeifen.oss-cn-shenzhen.aliyuncs.com".$file;
        if ($url == '')
        {
            return false;
        }

        // 获取文件原文件名
        $defaultFileName = basename($url);

        // 获取文件类型
        $suffix = substr(strrchr($url, '.'), 1);
        /*if (!in_array($suffix, $fileType))
        {
            return false;
        }*/


        $fileName = $defaultFileName;
        $fileName = str_replace('uploads/','',$file);
        try{
            $content = file_get_contents($url);
        }catch (\Exception $e){
            $content = file_get_contents("http://www.taizicasa.com".$file);
        }
        $file_arr = explode("/",$url);
        unset($file_arr[count($file_arr)-1]);
        $dirName = join('/',$file_arr);
        if (!file_exists($dirName))
        {
            mkdir($dirName, 0777, true);
        }
        $storage = \Storage::disk('admin');
        $storage->put($fileName,$content);
        return array(
            'fileName' => $fileName,
            'saveDir' => $dirName
        );
    }

}
