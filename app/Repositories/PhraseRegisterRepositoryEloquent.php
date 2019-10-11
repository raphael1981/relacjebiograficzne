<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\PhraseRegisterRepository;
use App\Entities\PhraseRegister;
use App\Validators\PhraseRegisterValidator;

/**
 * Class PhraseRegisterRepositoryEloquent
 * @package namespace App\Repositories;
 */
class PhraseRegisterRepositoryEloquent extends BaseRepository implements PhraseRegisterRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return PhraseRegister::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}
