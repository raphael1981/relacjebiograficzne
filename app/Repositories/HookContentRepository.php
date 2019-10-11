<?php

namespace App\Repositories;

use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface HookContentRepository
 * @package namespace App\Repositories;
 */
interface HookContentRepository extends RepositoryInterface
{
    public function renderHookContentDataHtml($slug);
}
