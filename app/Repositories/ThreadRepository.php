<?php

namespace App\Repositories;

use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface ThreadRepository
 * @package namespace App\Repositories;
 */
interface ThreadRepository extends RepositoryInterface
{
    public function searchByCriteria($data);
    public function getFullThreadData($id);
    public function getThredsAndRecordsCount();
}
