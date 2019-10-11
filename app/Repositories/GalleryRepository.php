<?php

namespace App\Repositories;

use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface GalleryRepository
 * @package namespace App\Repositories;
 */
interface GalleryRepository extends RepositoryInterface
{
    public function getGalleriesData($start,$limit);
    public function getGalleryData($id, $start,$limit);
    public function getGalleryDataIptc($id, $mode);
}
