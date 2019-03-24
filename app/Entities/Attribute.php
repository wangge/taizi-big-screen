<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use App\Entities\AttributeValue;

/**
 * Class Attribute.
 *
 * @package namespace App\Entities;
 */
class Attribute extends Model implements Transformable
{
    use TransformableTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];
    protected $table = 'attribute';

    public function values()
    {
        return $this->hasMany(AttributeValue::class, 'attr_id');
    }
}
