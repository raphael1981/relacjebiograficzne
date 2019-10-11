<?php

namespace App\Repositories;

use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface IntervieweeRepository
 * @package namespace App\Repositories;
 */
interface IntervieweeRepository extends RepositoryInterface
{
    public function searchByCriteria($data);
    public function getIntervieweesByIndex($data);
    public function getAllInterviewees();
	public function getAll();
    public function createIntervieweeGetId($data);
    public function updateIntervieweeData($data);
    public function updateSortString($id);


}
