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

            $fengge = app(Attribute::class)->find(5);
            $data = [];
            foreach($fengge->values as $value){
                if($value->id == 10) continue;//去掉田园
                $xilie = app(AttributeValue::class)->where('parent_value_id',$value->id)->get()->toArray();
                $value->xilie = $xilie;
                $data[] = $value->toArray();
            }

        }
        return $data;
    }
}
