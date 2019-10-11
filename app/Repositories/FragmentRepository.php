<?php

namespace App\Repositories;

use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface FragmentRepository
 * @package namespace App\Repositories;
 */
interface FragmentRepository extends RepositoryInterface
{
    public function clearFragmentsCreateNewByXML($data);
}
