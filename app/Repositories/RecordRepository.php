<?php

namespace App\Repositories;

use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface RecordRepository
 * @package namespace App\Repositories;
 */
interface RecordRepository extends RepositoryInterface
{
    public function getLastRecords($start, $limit);
    public function searchByCriteria($data);
    public function getFullRecordDataById($id);
    public function createNewRecordGetId($data);
    public function updateSortString($id);

}
