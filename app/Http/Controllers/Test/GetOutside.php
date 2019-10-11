<?php
/**
 * Created by PhpStorm.
 * User: Robert
 * Date: 27.02.2018
 * Time: 14:45
 */

namespace App\Http\Controllers\Test;

use App\Repositories;

class GetOutside
{
    public function __construct(Repositories\ArticleRepositoryEloquent $article)
    {
        $this->article = $article;
    }

    public function getArticles(){
        return json_encode($this->article->getAllArticlesData());
    }
}