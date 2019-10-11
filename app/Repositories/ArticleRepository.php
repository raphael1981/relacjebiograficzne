<?php

namespace App\Repositories;

use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface ArticleRepository
 * @package namespace App\Repositories;
 */
interface ArticleRepository extends RepositoryInterface
{
    public function searchByCriteria($data);
    public function getAriclesData($catid,$start,$limit);
    public function getAriclesDataMainMark($catid,$start,$limit);
    public function getAllArticlesData();
    public function getArticleFullData($aid);
    public function parseSlugGetData($slug);
    public function parseSlugGetDataOnlyArticle($slug);
    public function createNewSiteArticle($data);
    public function createNewExternalArticle($data);
    public function updateSiteArticle($data);
    public function updateExternalArticle($data);
}
