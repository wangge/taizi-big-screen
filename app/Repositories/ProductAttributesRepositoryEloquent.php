<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\ProductAttributesRepository;
use App\Entities\ProductAttributes;
use App\Validators\ProductAttributesValidator;

/**
 * Class ProductAttributesRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class ProductAttributesRepositoryEloquent extends BaseRepository implements ProductAttributesRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return ProductAttributes::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
}
