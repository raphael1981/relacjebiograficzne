<?php

namespace App\Http\Controllers\Super;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories;
use App\Entities\DeleteBackup;
use Illuminate\Support\Facades\Auth;

class EmployeeController extends Controller
{
    private $user;

    public function __construct(Repositories\UserRepositoryEloquent $user){

        $this->middleware('super', ['exept'=>[]]);
        $this->user = $user;

    }

    public function indexAction(){

        $privileges = Auth::user()->privileges;

        if(is_null($privileges)){
            return;
        }
        $privileges_data = \GuzzleHttp\json_decode($privileges);

        if(!$privileges_data->add_user){
            return;
        }


        $content = view('super.employee.content');

        return view('super.masteremployees', [
            'content'=>$content,
            'title'=>'AHM - Super Administrator - Współtwórcy',
            'controller'=>'admin/super/employees.controller.js'
        ]);

    }


    public function getEmployees(Request $request){
        return $this->user->searchByCriteria($request->all());
    }


    public function getFullEmployeeData($id){

        $std = new \stdClass();

        Model::unguard();

        $std->employee  = User::find($id);

        $ids = [];

        foreach($std->employee->records()->get() as $v){
            array_push($ids,$v->id);
        }

        $std->employee->records = $ids;

        return json_encode($std);

    }



    public function createNewEmployee(Request $request){

        Model::unguard();

        $user = $this->user->create([
            'name'=>$request->get('name'),
            'surname'=>$request->get('surname'),
            'email'=>$request->get('email'),
            'password'=>bcrypt($request->get('password')),
            'permission'=>$request->get('permission')['type']
        ]);

        $user->records()->attach($request->get('records'));

        $user->success = true;

        return response(json_encode($user), 200, ['Content-type'=>'application/json']);

    }


    public function updateData(Request $request){

        Model::unguard();

        $this->user->update([$request->get('field')=>$request->get('value')], $request->get('id'));

        return response(null, 200);
    }


    public function checkIsEmailEmployeeExist(Request $request){

        Model::unguard();

        return $this->user->findWhere(['email'=>$request->get('email')])->count();

    }


    public function checkIsEmailEmployeeExistExceptCurrent(Request $request){

        Model::unguard();

        $old_email = User::find($request->get('id'))->email;

        if($old_email==$request->get('email')){

            return 0;

        }else{

            return User::where('email',$request->get('email'))->where('email','!=', $old_email)->count();

        }


    }


    public function updateEmployee(Request $request){

        $user = $this->user->update([
            'name'=>$request->get('name'),
            'surname'=>$request->get('surname'),
            'email'=>$request->get('email'),
            'permission'=>$request->get('permission')['type']
        ], $request->get('id'));

        $user->records()->detach();

        $user->records()->attach($request->get('records'));

        $user->success = true;

        $user->success = true;

        return $user;

    }


    public function getRaportBeforeDelete(Request $request){

        Model::unguard();

        $std = new \stdClass();
        $std->relations = [];

        foreach($request->get('relations') as $key=>$rel){
            array_push($std->relations,[
                'data'=>User::find($request->get('id'))->{$rel['method']}()->get(),
                'name'=>$rel['name']
            ]);
        }

        return \GuzzleHttp\json_encode($std);

    }


    public function deleteEmployee(Request $request){

        $data = $this->createJsonToDeleteArchive($request->all());

        $backup = new DeleteBackup();
        $backup->data = $data;
        $backup->model = $request->get('model');
        $backup->save();

        foreach($request->get('relations') as $key=>$rel){
            User::find($request->get('id'))->{$rel['method']}()->detach();
        }

        User::find($request->get('id'))->delete();

        return $request->all();

    }


    public function changePassword(Request $request){

        Model::unguard();

        $this->user->update(['password'=>bcrypt($request->get('password'))], $request->get('id'));

        return response(null, 200);

    }


    private function createJsonToDeleteArchive($data){

        $std = new \stdClass();
        $std->relations = [];

        foreach($data['relations'] as $key=>$rel){

            array_push($std->relations,[
                'data'=>User::find($data['id'])->{$rel['method']}()->get(),
                'name'=>$rel['method']
            ]);

        }

        $std->element = User::find($data['id']);

        return \GuzzleHttp\json_encode($std);

    }


}
