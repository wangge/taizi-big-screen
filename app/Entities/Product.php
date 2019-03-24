<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use App\Entities\Attribute;
use App\Entities\Category;
use App\Entities\ProductAttributes;
use App\Entities\ProductsSku;

/**
 * Class Product.
 *
 * @package namespace App\Entities;
 */
class Product extends Model implements Transformable
{
    use TransformableTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];
    protected $table = 'product';

    public function attribute()
    {
        return $this->belongsToMany(Attribute::class, 'product_attribute','product_id', 'attr_id');
    }
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id','id');
    }
    public function attributes()
    {
        return $this->hasMany(ProductAttributes::class, 'product_id');
    }
    // 与商品SKU关联
    public function sku()
    {
        return $this->hasMany(ProductsSku::class,'product_id');
    }
}
