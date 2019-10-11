<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\FragmentRepository;
use App\Entities\Fragment;
use App\Validators\FragmentValidator;
use App\Helpers\XMLHelper;
use App\Helpers\PolimorficHelper;
/**
 * Class FragmentRepositoryEloquent
 * @package namespace App\Repositories;
 */
class FragmentRepositoryEloquent extends BaseRepository implements FragmentRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Fragment::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }


    public function clearFragmentsCreateNewByXML($data){

        foreach($this->findWhere(['record_id'=>$data['id']]) as $key=>$value){

            $f = Fragment::find($value->id);
            PolimorficHelper::removePolimorficRelations($f);
            $f->delete();

        }

        foreach($data['xmltrans']['xmldata'] as $key=>$fragment){

            $this->create([
                'record_id'=>$data['id'],
                'content'=>$fragment['content'],
                'start'=>$fragment['time'],
                'ord'=>$key
            ]);

        }


    }


}
