<?php

namespace App\Repositories;

use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface PeriodRepository
 * @package namespace App\Repositories;
 */
interface PeriodRepository extends RepositoryInterface
{
    public function searchByCriteria($data);
    public function getFullPeriodData($id);
}
