<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\attributeRepository;
use App\Entities\Attribute;
use App\Validators\AttributeValidator;
use App\Entities\AttributeValue;

/**
 * Class AttributeRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class AttributeRepositoryEloquent extends BaseRepository implements AttributeRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Attribute::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    public function getFengGeList($id)
    {
        $fengge = $this->find($id);
        $data = [];
        foreach($fengge->values as $value){

            $sql = "SELECT DISTINCT(`value_id`) FROM `product_attributes` WHERE `product_id` in (SELECT product_id FROM `product_attributes` WHERE find_in_set($value->id,`value_id`)) and attr_id=1";
            $value_ids = \DB::select($sql);
            $new_ids = [];
            foreach(array_column($value_ids,'value_id') as $id){
                $new_ids = array_unique(array_merge($new_ids,explode(',',$id)));
            }
            $xilie = app(AttributeValue::class)->whereIn('id',$new_ids)->get()->toArray();
            $value->xilie = $xilie;
            $data[] = $value->toArray();
        }
        return $data;
    }
}
