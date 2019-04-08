<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class ProductAttributes.
 *
 * @package namespace App\Entities;
 */
class ProductAttributes extends Model implements Transformable
{
    use TransformableTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];
    protected $table = 'product_attributes';
    public $timestamps = false;
    /**
     * 定义访问器
     * @param $value
     * @return array
     */
    public function getValueIdAttribute($value){
        return explode(',',$value);
    }

    /**
     * 定义修改器
     * @param $value
     */
    public function setValueIdAttribute($value){
        $this->attributes['value_id'] = join(',',$value);
    }
}
