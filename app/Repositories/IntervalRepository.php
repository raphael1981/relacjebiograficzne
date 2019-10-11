<?php

namespace App\Repositories;

use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface IntervalRepository
 * @package namespace App\Repositories;
 */
interface IntervalRepository extends RepositoryInterface
{
    public function searchByCriteria($data);
    public function checkIsInterval($data);
    public function createIntervalFromForData($data);
}
