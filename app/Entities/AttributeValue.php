<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class AttributeValue.
 *
 * @package namespace App\Entities;
 */
class AttributeValue extends Model implements Transformable
{
    use TransformableTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];
    protected $table = 'attribute_value';
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
