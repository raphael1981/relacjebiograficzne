<?php

namespace App\Http\Controllers;

use App\Entities\Category;
use App\Entities\Customer;
use App\Entities\Fragment;
use App\Entities\Interviewee;
use App\Entities\Record;
use App\Helpers\RecordLinkHelp;
use App\Repositories\CustomerRepositoryEloquent;
use Barryvdh\Debugbar\LaravelDebugbar;
use Barryvdh\Debugbar\Facade as Debugbar;
use Carbon\Carbon;
use FFMpeg\FFMpeg;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;

use App\Helpers\MyIPTC;
use App\Helpers\MyJson;

use App\Http\Requests;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use App\Helpers\XMLHelper;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Mmanos\Search\Facade as Search;
use Collective\Remote\RemoteFacade as SSH;
use CSD\Image\Image as ImageCSD;

class TestController extends Controller
{
    private $customer;
    private $letters = ['ą','ę','ć','ś','ł','ń','ź','ż'];
    private $letters_to_replace = ['a','e','c','s','l','n','z','z'];

    public function __construct($variableName, CustomerRepositoryEloquent $customer){

        //$variableName - bind in ServicesProviders
//
//        dd($variableName);

        $this->customer = $customer;

    }

    public function indexAdmin(){

//        Auth::guard()->logout();
//        dd(config('services'));
//        Auth::guard('customer')->logout();


//        Auth::guard('customer')->attempt(['email'=>'raphaelmaj@gmail.com', 'password'=>'wiliak100']);

//        Auth::guard('customer')->attempt(['email' => 'raphaelmaj@gmail.com', 'password' => 'wiliak']);
//        Auth::guard('customer')->attempt(['email' => 'raphaelmaj@gmail.com', 'password' => 'wiliak'], true);
//        Auth::guard('customer')->login(Auth::guard('customer')->user());
//        dd(Auth::guard('customer')->user());
//        dd( Auth::user());
//        dd( Auth::guard('customer')->user());


//        dd(Search::index('photos')->search('description', 'Hello')->get());


        $transform = '';

        $arr = $seed = preg_split('//u', mb_strtolower('Świerczłowiek'), -1, PREG_SPLIT_NO_EMPTY);


        foreach($arr as $wl){

            foreach($this->letters as $k=>$l){

                if($wl==$l){
                    $wl = $this->letters_to_replace[$k].'zz';
                }

            }

            $transform .= $wl;

        }

        dd($transform);

    }

    public function indexCustomers(){

//        Debugbar::info('test');

//        dd(Cache::put('lsd', 25, Carbon::now()->addMinutes(30)));
//        dd((bool)random_int(0, 1));

//          $image = ImageCSD::fromFile(public_path().'/images/lupka.png');
//          $headline = $image->getXmp();
//          dd($headline);
//        $path = storage_path().'/app/pictures/';
//        $array = glob($path.'*',GLOB_BRACE);
//        $images = [];
//        foreach($array as $key=>$value){
//
//            $images[$key] = new \stdClass();
//            $images[$key]->fullpath = $value;
//            $images[$key]->filename = str_replace($path,'',$value);
//
//        }
//
//        dd($images);

//        $array = range('A','Z');
//        $to_json = [];
//
//        foreach($array as $k=>$l){
//            $to_json[$l] = [];
//
//            if($l=='A'){
//                $to_json[$l] = ['A','Ą'];
//            }
//
//            elseif($l=='C'){
//                $to_json[$l] = ['C','Ć'];
//            }
//
//            elseif($l=='E'){
//                $to_json[$l] = ['E','Ę'];
//            }
//
//
//            elseif($l=='L'){
//                $to_json[$l] = ['L','Ł'];
//            }
//
//            elseif($l=='N'){
//                $to_json[$l] = ['N','Ń'];
//            }
//
//            elseif($l=='O'){
//                $to_json[$l] = ['O','Ó'];
//            }
//
//            elseif($l=='S'){
//                $to_json[$l] = ['S','Ś'];
//            }
//
//            elseif($l=='Z'){
//                $to_json[$l] = ['Z','Ź','Ż'];
//            }
//
//            else{
//                $to_json[$l] = [$l];
//            }
//
//
//        }
//
//        $json = json_encode($to_json);
//
//
//        file_put_contents(public_path().'/json/alphabet.json',$json);

//        $path = Storage::disk('photos')
//            ->getDriver()
//            ->getAdapter()
//            ->getPathPrefix();
//
//        $files = Storage::disk('photos')->files();
//
//
//        $array = [];
//
//        foreach($files as $key=>$value){
//            $img = ImageCSD::fromFile($path . $value);
//            $array[] = $img->getAggregate()->getPhotographerName();
//        }
//
//
//        dd($array);



//        dd(config('services'));

//        Auth::guard('customer')->attempt(['email' => 'raphaelmaj@gmail.com', 'password' => 'wiliak']);
//        Auth::guard('customer')->attempt(['email' => 'raphaelmaj@gmail.com', 'password' => 'wiliak'], true);
//        Auth::guard('customer')->login(Auth::guard('customer')->user());
//        dd(Auth::guard('customer')->user());

//        $next = Carbon::now()->addMinutes(10);
//
//        $create = Carbon::create($next->year,$next->month,$next->day,$next->hour,$next->minute,$next->second, 'Europe/Warsaw');
//
//
//        echo $this->customer->find(32)->status;
//
//
//        if(Carbon::now()->lte($create)){
//            echo 'end';
//        }
//
//        echo '<pre>';
//        echo print_r($next);
//        echo print_r(Carbon::now());
//        echo '</pre>';



//        $re = '/\<time\>([0-9]*)\<\/time\>/';
//
//        $str = '<time>333</time>';
//
//        preg_match_all($re, $str, $matches);
//
//        echo preg_replace($re,'',$str);
//
//        print_r($matches);
//
//
//
//
//        function readRecursively($reader, $array = [])
//        {
//
//            $re = '/\<time\>([0-9]*)\<\/time\>/';
//
//            $i = 0;
//
//            $s = false;
//            $t = false;
//
//            while ($reader->read()) {
//
//
//                if($s && $t){
//                    $s = false;
//                    $t = false;
//                }
//
//                if ($reader->nodeType == \XMLReader::ELEMENT) {
//
//                    if($reader->name == 'section'){
//
//                        $string = $reader->readInnerXML();
//
//                        $array[$i] = [
//                            'content' => trim(preg_replace($re,'',$string)),
//                        ];
//
//                        $s=true;
//
//                    }
//
//                    if($reader->name == 'time'){
//
//                        $array[$i]['time'] = $reader->readString();
//
//                        $t=true;
//
//                    }
//
//
//                    if($s && $t){
//                        $i++;
//                    }
//
//
//
//                }
//
//
//            }
//
//            return $array;
//
//        }
//
//
//        $reader = new \XMLReader;
//        $reader->open(url('/xml/latuchowski_good_old.xml'));
//
//        echo '<pre>';
//        print_r(readRecursively($reader));
//        echo '</pre>';
//        echo '<pre>';
//        print_r(XMLHelper::readTranscriptionXML('/xml/test.xml'));
//        echo '<pre>';

//        $rec = Record::find(1);
//        $int = Interviewee::create([
//                'name'=>'Jan',
//                'surname'=>'Kowalski',
//                'biography'=>'tajny agent',
//                'status'=>1
//            ]);
//
//        $rec->interviewees()->save($int);


//        SSH::run([
//            'cd /usr/home/Wiliak/domains/videohistoria.zbiglem.pl/public_html',
//            'php artisan search:index --action=index',
//        ]);
//
//        return 'lsd';

//        dd(Search::index('records')->search('frase', 'a')->get());

//        $title = 'Aktualności';
//
//        Category::create([
//                'name'=>$title,
//                'alias'=>str_slug($title,'-'),
//                'description'=>'Lorem ipsum dolor sit amet enim. Etiam ullamcorper. Suspendisse a pellentesque dui, non felis. ',
//                'status'=>1
//        ]);


//        SSH::run([
//            'cd /usr/home/DSH/domains/relacjebiograficzne.dsh.waw.pl/public_html',
//            'php artisan view:clear',
//        ]);
//
//
//        return 'html';

//        echo file_get_contents(public_path().'/biography/Latuchowski_Eugeniusz_biogram.html');


//        $narra = [];
//        $array = glob(public_path().'/seedimages/news1/*', GLOB_BRACE);
//
//        foreach($array as $key=>$value){
//            array_push($narra, str_replace(public_path().'/seedimages/news1/','', $value));
//        }

//        return file_put_contents(public_path().'/seedimages//news1/news1.json',json_encode($narra));


//        return json_decode(file_get_contents(public_path().'/seedimages//news1/news1.json'),true);

//        $pr = explode('<hr />', file_get_contents(storage_path().'/app/data/about.html'));
//        echo '<pre>';
//        print_r($pr);
//        echo '</pre>';

//        $r = Record::find(1);
//
//        $r2 = Record::find(2);
//        $r3 = Record::find(3);
//
//        $r->recordsMorphedByMany()->save($r2);
//        $r->recordsMorphedByMany()->save($r3);


//        $array = [];
//
//        foreach(Record::all() as $key=>$rec){
//
//            $ex = explode(' ', $rec->title);
//
////            echo $rec->id .'  =>  '.$ex[1].' '.$ex[0];
////            echo '<br>';
//
//            array_push($array, $ex[1].' '.$ex[0].' => '.$rec->id);
//
//        }
//
//
//        asort($array);
//
//
//
//        foreach($array as $key=>$rec){
//
//            echo $rec.'<br>';
//
//        }




//
//        $c = Customer::find(1);
//
//        return view('emails.customer.tocustomer.linkverify', ['customer'=>$c]);
//        return view('emails.customer.tocustomer.linkverifyremeber', ['customer'=>$c]);

//        dd(Record::find(21)->recordsMorphedByMany()->get());

//        $array = [21,26,2];
//        RecordLinkHelp::getInstance()->linkAllToAll($array);
//        RecordLinkHelp::getInstance()->checkAndRemoveDouble();

//        Schema::connection('mysqlbackup')->create('customers', function(Blueprint $table) {
//            $table->increments('id');
//            $table->string('name',100);
//            $table->string('surname',100);
//            $table->string('email')->unique();
//            $table->string('password');
//            $table->string('phone',100);
//            $table->enum('customer_type', ['osoba prywatna','instytucja'])->default('osoba prywatna');
//            $table->string('institution_name',150);
//            $table->string('register_target',255);
//            $table->tinyInteger('status')->default(-1);
//            $table->string('verification_token',255)->nullable();
//            $table->string('remember_token',255)->nullable();
//            $table->dateTime('expire_remember_token')->nullable();
//            $table->softDeletes();
//            $table->timestamps();
//        });
/*
     Record::create([
                'title'=>'Maria Stypułkowska-Chojecka',
                'alias'=>str_slug('Maria Stypułkowska-Chojecka'),
                'signature'=>'AHM_453',
                'source'=>'Stypulkowska_Chojecka_Maria_Kama_AHM_453.mp3',
                'xmltrans'=>'stypulkowska-chojecka.xml',
                'description'=>'Maria Stypułkowska-Chojecka',
                'summary'=>'Ola ma psa',
                'duration'=>15855,
                'type'=>'audio',
                'status'=>1,
                'published_at'=>Carbon::now()
            ]);
			
     Record::create([
                'title'=>'Wanda Hiszpańska',
                'alias'=>str_slug('Wanda Hiszpańska'),
                'signature'=>'AHM_327',
                'source'=>'Hiszpanska_Wanda_AHM_327.mp3',
                'xmltrans'=>'hiszpanska.xml',
                'description'=>'Wanda Hiszpańska',
                'summary'=>'Ola ma psa',
                'duration'=>14310,
                'type'=>'audio',
                'status'=>1,
                'published_at'=>Carbon::now()
            ]);			


        $rec = Record::find(32); 
        $int = Interviewee::find(32); 		
        $rec->interviewees()->save($int);

        $rec = Record::find(33); 
        $int = Interviewee::find(33); 		
        $rec->interviewees()->save($int);		

  */

//        Search::index('places')->deleteIndex();


//        Search::insert(
//            "post-1",
//            array(
//                'id'=>11,
//                'title' => 'My title',
//                'content' => 'The quick brown fox...',
//                'status' => 'published',
//            ),
//            array(
//                'created_at' => time(),
//                'creator_id' => 5,
//            )
//        );

//        dd(Search::select('id', 11)->get());

//        dd(Search::index('places')->search('title','Maria Nowicka')->get());
//        dd(Search::index('records')->search('frase', 'Warszawa')->get());
//
//        $ar = [];
//
//        foreach (Cache::get(2) as $key=>$item){
//
//            foreach ($item['fragments'] as $k=>$f){
//
//            }
//
//        }
//
//        dd($ar);

//        dd(Cache::get(2));

    }


    private function checkIsCustomerInBackUp($c){

        return DB::connection('mysqlbackup')
            ->table('customers')
            ->select('email', $c->email)
            ->count();
    }


    public function testThree($id){

        $xmlhelp = XMLHelper::xmlCreatorInstance($id);
        $xmlhelp->prepareTranscription();
        $xmlhelp->prepareTranscriptionFragments();
        return response($xmlhelp->createXMLString(), 200)
                ->header('Content-Type', 'application/xml')
                ->header('Content-Length', $xmlhelp->getXMLStringLength());

//        dd($xmlhelp);

    }


    public function postTest(Request $request){

        dd($request);

        return response($request->all(),200);


    }


    public function tagsFromFile(){

        $json = file_get_contents(public_path().'/json/tags.json');
        dd(\GuzzleHttp\json_decode($json));

    }



    public function indexTest(){

//        Search::index('1945')->deleteIndex();
        dd(Search::index('1945')->where('id',1)->get());

    }


    public function indexTest2(){

//        foreach(Record::all() as $r){
//
//
//
//            foreach($r->fragments()->get() as $k=>$f) {
//
//                if(!is_array($r)) {
//                    $r = $r->toArray();
//                }
//
//                $r['frgs'] = [];
//                if ($f->intervals()->where('begin', '>', '1944-12-31')->orWhere('end', '<=', '1945-12-31')->count() > 0) {
//
//                    array_push($r['frgs'], $f);
//
//                }
//
//                $r['frgs'] = json_encode($r['frgs']);
//                Search::index('1945')->insert($r['id'], $r);
//
//
//            }
//
//
//
//        }


        dd(Search::index('place:2:interval:1')->search("type","record")->get());


    }




    public function postCzasooznaczacz(Request $request){

//        if($_SERVER['REQUEST_METHOD'] == 'POST') {
//
//            $body = file_get_contents('php://input');
//            file_put_contents(public_path().'/test/text.txt',json_encode($body));
//
//        }

//        return response($body,200);

    }

}
