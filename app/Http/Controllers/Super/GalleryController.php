<?php

namespace App\Http\Controllers\Super;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helpers\MyIPTC;
use App\Helpers\MyJson;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use Intervention\Image\ImageManager;
use App\Repositories;
use CSD\Image\Image as ImageCSD;


class GalleryController extends Controller
{
	private $destinationPublic;
	private $destinationRoot;
	private $gallery;
	private $interviewee;	
	
    public function __construct(Repositories\GalleryRepositoryEloquent $gallery,
	                             Repositories\IntervieweeRepositoryEloquent $interviewee){

       $this->middleware('super', ['exept'=>[]]);
		$this->gallery = $gallery;
		$this->interviewee = $interviewee;		
		$this->destinationPublic = Storage::disk('photos')
												->getDriver()
												->getAdapter()
												->getPathPrefix();
		 $this->destinationRoot = base_path().'/upload/';										

    }

	
   private function getGalleries(){
		$data = $this->gallery->findWhere(['status'=>1]);
       $dat = $data->toArray();
	   //dd($dat);	  
	   $temp = null;	   
	   $arr = [];
	   foreach($dat as $k => $v){
		   $temp = new \stdClass;
		   $temp->id = $v['id'];
		   $temp->name = $v['name'];
		   $temp->alias = $v['alias'];
		   $temp->description = $v['description'];		   
		   array_push($arr,$temp);		   
	   } 
	   
		return json_encode($arr);
	}
		
	
	
    public function indexAction(){
        $galleries = $this->getGalleries();
        $content = view('super.gallery.content',['galleries'=>$galleries]);       
        return view('super.master', [		     
            'content'=>$content,
            'title'=>'AHM - Super Administrator - Galerie',
            'controller'=>'admin/super/gallery.controller.js'
        ]);

    }

	public function loadPhotoGall(Request $request){		
		$this->gallery->update(['photos'=>json_encode($request->get('photos'))],$request->get('id'));
		return response($request->get('photos'));
	}	
	

	public function getIPTCdata(Request $request){									  
		$path = $this->destinationPublic.$request->header('path');		
		if(!File::exists($path)) abort(404);        
		$iptc = new MyIPTC();       
		return response($iptc->output_data($path));	
		
		//if(!File::exists($path)) abort(404);
        //$image = Image::make($path);
		//return response($image->iptc());				
	}

	
	public function getITPCInfo(Request $request){      
		$path = $this->destinationPublic . $request->header('path');		
		//$path = $filename;		
		$jpeg = ImageCSD::fromFile(stripslashes($path));
       $output = new \stdClass();	  	   	   
	    $output->keywords = $jpeg->getAggregate()->getKeywords();		
	    $output->headline = $jpeg->getAggregate()->getHeadline(); //nazwa projektu
	    $output->caption = $jpeg->getAggregate()->getCaption(); //podpis
		$output->source = $jpeg->getAggregate()->getSource(); //Archiwum Historii Mówionej
		$output->credit = $jpeg->getAggregate()->getCredit();//DSH/OK
		$output->photographerName = $jpeg->getAggregate()->getPhotographerName(); //autor relacji
		$output->category =  $jpeg->getAggregate()->getCategory();
		$output->supplementalCategories = $jpeg->getAggregate()->getSupplementalCategories();
		return response(json_encode($output)); 
	}
	
	
	private function setIPTCInfo($iptc,$filepath){      		
		$path = $filepath;	
		$jpeg = ImageCSD::fromFile(stripslashes($path));
		if(is_array($iptc)){
			$jpeg->getAggregate()->setKeywords($iptc['keywords']);		
			$jpeg->getAggregate()->setHeadline($iptc['headline']);		
			$jpeg->getAggregate()->setCaption($iptc['caption']);		
			$jpeg->getAggregate()->setSource($iptc['source']);
			$jpeg->getAggregate()->setCredit($iptc['credit']);
			$jpeg->getAggregate()->setPhotographerName($iptc['photographerName']);			
			$jpeg->getAggregate()->setCategory($iptc['category']);
			$jpeg->getAggregate()->setSupplementalCategories($iptc['supplementalCategories']);
			
		}elseif(is_object($iptc)){
			$jpeg->getAggregate()->setKeywords($iptc->keywords);		
			$jpeg->getAggregate()->setHeadline($iptc->headline);
			$jpeg->getAggregate()->setCaption($iptc->caption);		
			$jpeg->getAggregate()->setSource($iptc->source);
			$jpeg->getAggregate()->setCredit($iptc->credit);
			$jpeg->getAggregate()->setPhotographerName($iptc->photographerName);			
		    $jpeg->getAggregate()->setCategory($iptc->category);
			$jpeg->getAggregate()->setSupplementalCategories($iptc->supplementalCategories);
		}else{
			
		}
		 $jpeg->getAggregate()->setUrgency('0'); 
        $jpeg->save();      
	}	
	
	private function setQueue($imgPath,$index){
			$jpeg = ImageCSD::fromFile(stripslashes($imgPath));
			$jpeg->getAggregate()->setUrgency($index);
           $jpeg->save(); 			
	}
	
	
	public function getExif($filename){	
       $disk = 'photos';	
		if(!File::exists(storage_path().'/app/'.$disk.'/'.$filename)) abort(404);        		
        $path = storage_path().'/app/'.$disk.'/'.$filename;	   	  
	    $file  = ImageCSD::fromFile(stripslashes($path));
		$output = $file->getExif();
		return response(json_encode($output));		
		
		//return response($filename);		
	}
	
	
	private function getIPTC($filename,$pathfile){
		$path = $pathfile . $filename;		
		$jpeg = ImageCSD::fromFile(stripslashes($path));		
		$output = new \stdClass();	  	   	   
	    $output->keywords = $jpeg->getAggregate()->getKeywords();		
	    $output->headline = $jpeg->getAggregate()->getHeadline(); //nazwa projektu
	    $output->caption = $jpeg->getAggregate()->getCaption(); //podpis
		$output->source = $jpeg->getAggregate()->getSource(); //Archiwum Historii Mówionej
		$output->credit = $jpeg->getAggregate()->getCredit();//DSH/OK
		$output->photographerName = $jpeg->getAggregate()->getPhotographerName(); //autor relacji
		$output->category =  $jpeg->getAggregate()->getCategory();
		$output->supplementalCategories = $jpeg->getAggregate()->getSupplementalCategories();
		$output->urgency = $jpeg->getAggregate()->getUrgency();		
		return response(json_encode($output));
	}
	
	public function getPictures(Request $request){
		$person = $request->person;
		$subject = $request->subject;
		$images = array_filter(Storage::disk('photos')->files(), 
		                             function ($item) {return strpos($item, 'jpg');});
		    		
		//return response($images);	
		 $pathes = [];
        $res = null;
		 $index = 1;
        foreach($images as $key =>$val){
			           $res = new \stdClass();                     
					    $iptc = $this->getIPTC($val,$this->destinationPublic);					    
						$res->name = $val;
						$res->iptc = json_decode($iptc->original,true);
						if($person == null && $subject == null){
							array_push($pathes,$res);							
						  }else{
							if($person !== null){  
								if($res->iptc['photographerName'] === $person){
									//$this->setQueue($this->destinationPublic.$res->name,$index++);
									array_push($pathes,$res);	
								}			
							}elseif($subject !== null && $res->iptc['supplementalCategories'] !== null){
									foreach($res->iptc['supplementalCategories'] as $k => $v){   
									   if(trim($subject) == strtolower(trim($v))){									       
										   array_push($pathes,$res);
                                           break;										   
										   //continue;
									   }
									   //array_push($pathes,$res);
								   }
								}
								
							}
				   } 		 
				   
		$result = new \stdClass();
        $result->path = $this->destinationPublic;		
		 $result->pathes = $pathes;		 
         $result->person = $person;		 		 
		 $result->subject = $subject;
		 
	   return response(json_encode($result));
	}
	
	
	public function getImageFront($filename,$disk,$basesize=null){
		
		if(!File::exists(storage_path().'/app/'.$disk.'/'.$filename)) abort(404);        
		//$iptc = new MyIPTC();       
		//$iptc = $iptc->output_data(storage_path().'/app/'.$disk.'/'.$filename);			
        $ctype = Storage::disk($disk)->mimeType($filename);
        if(is_null($basesize)){
            $img = Storage::disk($disk)->get($filename);
            return response($img,200)
                ->header('Content-Type',  $ctype);
        }else{
            $img = Image::make(storage_path().'/app/'.$disk.'/'.$filename)
                ->resize(null, $basesize, function ($constraint) {
                    $constraint->aspectRatio();
                });
            return $img->response($ctype)
                ->header('Content-Type',  $ctype);
				//->header('iptc', $iptc);
        }
    }
	
	public function orderPictures(Request $request){
		//$input = json_decode(json_encode($request->all()),true);
		$input = $request->all();
		$index = 1;
       foreach($input as $key => $val){
			$this->setQueue($this->destinationPublic.$val['name'],$index++);
		 }
		return $input;
	}
	
	
	public function changeDescript(Request $request){
		$input = json_decode(json_encode($request->all()),true);	
		$jpeg = ImageCSD::fromFile($this->destinationPublic.$input['name']);
		$jpeg->getAggregate()->setCaption($input['descript']);
		$jpeg->save();
		return response($input['descript']);
	}
	
	/*
	public function getIPTC(Request $request){
		$path = $request->header('path');
		//$iptc = new MyIPTC();	
      //Image::make()		
			//return response($iptc->output_data($path));	
			return response('');	
	}
	*/
    public function store(Request $request) {	        
        if ($request->hasFile('file'))		
        {
			//$file = $request->input('file');			
			$file = $request->file('file');
			$filename = $file->getClientOriginalName();			

			
		
            if(!is_file($this->destinationRoot.$filename)){
               $uploadSuccessRoot =
				$request->file('file')->move($this->destinationRoot, $filename);
               $tmp = $this->getIPTC($filename,$this->destinationRoot);
			    $iptc = json_decode($tmp->original,true);                 
			    $temp = explode('.',$filename);
               $ext = $temp[count($temp)-1];
			    $newfilename = (microtime(true)*10000).'.'.strtolower($ext);	                 				
				if( $uploadSuccessRoot ) {
				 
                  $img = Image::make($this->destinationRoot.$filename);                  			  
					$iptc['photographerName'] = $request->input('person');					
					//$iptc['urgency'] = 0;
					if(!is_array($iptc['supplementalCategories'])){
						$iptc['supplementalCategories'] = [];
					}
					array_push($iptc['supplementalCategories'],$request->input('subject'));										
					$img->save($this->destinationPublic.$newfilename);
                    $gall = $this->gallery->findWhere(['name'=>$request->input('subject')]);
                    $photos = json_decode($gall[0]->photos,true);
                    array_unshift($photos,$newfilename);
                    $this->gallery->update(['photos'=>json_encode($photos)],$gall[0]->id);
                    //$this->gallery
					/*
					->resize(null, 1024, function ($constraint) {
                  $constraint->aspectRatio();
                  });
*/				  
					$fromset = $this->setIPTCInfo($iptc,$this->destinationPublic.$newfilename);
					
					File::delete($this->destinationRoot.$filename);
					//$this->addToBase($newfilename,$idgall);					                    
					//return response($this->destinationPublic.$newfilename);		
                  return response($fromset);			                 
				}
             return response($iptc);				
		    }
		}
	return response(0);
	}

	
	public function removePicture(Request $request){
		$input = json_decode(json_encode($request->all()),true);
		$filename = $input['name'];
        $iptc = json_decode($this->getIPTC($filename,$this->destinationPublic)->original);
        unlink($this->destinationPublic.$filename);
        $photos = [];
        if($iptc->supplementalCategories != null && count($iptc->supplementalCategories)>0){
            foreach($iptc->supplementalCategories as $key => $value){
                $gall = json_decode($this->gallery->findWhere(['name'=>trim($value)]),true)[0];
                $photos = json_decode($gall['photos']);
                  foreach($photos as $k => $v){
                      if(is_array($photos) && $photos[$k] === $filename) {
                          unset($photos[$k]);
                      }
                  }
                  $this->gallery->update(['photos'=>json_encode($photos)],$gall['id']);
              }
            return response(json_encode($photos));
          }
        return '';
	}
	
	public function getPersons(){
		$data = [];
		$all = $this->interviewee->getAll();
		$counter = 0;
		foreach($all as $key => $value){			
			array_push($data, $value->name.' '.$value->surname);
			$counter ++;
		}
		return array_unique($data);
	}

	public function getSubjects(){
		$data = [];
		$all = $this->gallery->findWhere(['status'=>1,'mode'=>'iptccategory']);
		$counter = 0;
		foreach($all as $key => $value){			
			array_push($data, $value);
			$counter ++;
		}
		return array_unique($data);
	}

	public function getSubjectGalleryPhotos(Request $request, $id){
		$gall = $this->gallery->find($id);
		$photos = json_decode($gall->photos);

		$res = new \stdClass();
		$res->gallery = $gall;
		$res->images = [];

		if($photos) {
            foreach ($photos as $key => $value) {
                $iptc = $this->getIPTC($value, $this->destinationPublic);
                $ar = ['iptc' => json_decode($iptc->original, true), 'name' => $value];
                array_push($res->images, $ar);
            }
        }
		return response(json_encode($res));
	}
	
	
	public function test(Request $request){
		return $this->getPersons();
		//return $this->interviewee->getAll();
	}
	
	
	public function setPhotographerName(Request $request){
		$impath = $request->get('image');
		$photoName = $request->get('photographerName');
		$jpeg = ImageCSD::fromFile(stripslashes($this->destinationPublic.$impath));
		$jpeg->getAggregate()->setPhotographerName($photoName);
		$jpeg->save();
		return response($photoName);
	}
	
   public function setCaption(Request $request){
		$impath = $request->get('image');
		$caption = $request->get('caption');
		$jpeg = ImageCSD::fromFile(stripslashes($this->destinationPublic.$impath));
		$jpeg->getAggregate()->setCaption($caption);
		$jpeg->save();
		return response($caption);
	}
	
	public function setGallTitles(Request $request){
		//$output = $request->all();
		$impath = $request->get('image');
		$data = $request->get('supplementalCategories');
       $tab = []; 
		foreach($data as $k =>$v){
			array_push($tab,$v['name']);
		}
		
		
		$jpeg = ImageCSD::fromFile(stripslashes($this->destinationPublic.$impath));
		$jpeg->getAggregate()->setSupplementalCategories($tab);
		$jpeg->save();
		return response($tab[0]);
	}
	
}


