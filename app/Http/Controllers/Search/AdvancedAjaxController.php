<?php

namespace App\Http\Controllers\Search;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\AdvancedSearch\AdvancedSearchRepository;

class AdvancedAjaxController extends Controller
{

    private $search;

    public function __construct(AdvancedSearchRepository $search)
    {
        $this->search = $search;
    }


    public function searchByCriteria(Request $request){

        return $this->search->advancedSearchExecute($request->all());

    }

    public function searchByCriteriaAll(Request $request){

        return $this->search->advancedSearchExecuteAll($request->all());

    }

}
