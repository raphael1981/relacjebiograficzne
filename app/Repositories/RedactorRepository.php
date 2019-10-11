<?php

namespace App\Repositories;

use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface RedactorRepository
 * @package namespace App\Repositories;
 */
interface RedactorRepository extends RepositoryInterface
{
    public function searchByCriteria($data);
}
