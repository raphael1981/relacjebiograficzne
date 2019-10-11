<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\SondaRepository;
use App\Entities\Sonda;
use App\Validators\SondaValidator;

/**
 * Class SondaRepositoryEloquent
 * @package namespace App\Repositories;
 */
class SondaRepositoryEloquent extends BaseRepository implements SondaRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Sonda::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}
