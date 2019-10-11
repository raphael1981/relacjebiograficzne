<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\MonitorIndxRepository;
use App\Entities\MonitorIndx;
use App\Validators\MonitorIndxValidator;

/**
 * Class MonitorIndxRepositoryEloquent
 * @package namespace App\Repositories;
 */
class MonitorIndxRepositoryEloquent extends BaseRepository implements MonitorIndxRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return MonitorIndx::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}
