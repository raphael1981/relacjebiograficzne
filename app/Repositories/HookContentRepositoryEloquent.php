<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\HookContentRepository;
use App\Entities\HookContent;
use App\Validators\HookContentValidator;

/**
 * Class HookContentRepositoryEloquent
 * @package namespace App\Repositories;
 */
class HookContentRepositoryEloquent extends BaseRepository implements HookContentRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return HookContent::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }


    public function renderHookContentDataHtml($slug){

        $html = '';

        $contents = $this->findWhere(['slug'=>$slug]);


        foreach($contents as $key=>$content){

            if($content->show_title==1){
                $html .= '<h2 class="section-title">'.$content->title.'</h2>';
            }

            $html .= $content->content;
        }



        return $html;

    }


}
