<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\products_skuRepository;
use App\Entities\ProductsSku;
use App\Validators\ProductsSkuValidator;

/**
 * Class ProductsSkuRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class ProductsSkuRepositoryEloquent extends BaseRepository implements ProductsSkuRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return ProductsSku::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
}
