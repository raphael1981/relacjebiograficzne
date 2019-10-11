<?php

namespace App\Repositories;

use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface PlaceRepository
 * @package namespace App\Repositories;
 */
interface PlaceRepository extends RepositoryInterface
{
    public function searchByCriteria($data);
}
