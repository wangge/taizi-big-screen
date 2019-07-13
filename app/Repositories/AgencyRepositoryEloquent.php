<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\AgencyRepository;
use App\Entities\Agency;
use App\Validators\AgencyValidator;

/**
 * Class AgencyRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class AgencyRepositoryEloquent extends BaseRepository implements AgencyRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Agency::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
}
