<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\favoriteRepository;
use App\Entities\Favorite;
use App\Validators\FavoriteValidator;

/**
 * Class FavoriteRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class FavoriteRepositoryEloquent extends BaseRepository implements FavoriteRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Favorite::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
}
