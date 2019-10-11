<?php
namespace App\Helpers;


class MyIPTC {
	
  private $headers = [    
    '2#005'=>'DocumentTitle',
    '2#010'=>'Urgency',
    '2#015'=>'Category',
    '2#020'=>'Subcategories',
	'2#025'=>'Keywords',
    '2#040'=>'SpecialInstructions',
    '2#055'=>'CreationDate',
    '2#080'=>'AuthorByline',
    '2#085'=>'AuthorTitle',
    '2#090'=>'City',
    '2#095'=>'State',
    '2#101'=>'Country',
    '2#103'=>'OTR',
    '2#105'=>'Headline',
    '2#110'=>'Source',
    '2#115'=>'PhotoSource',
    '2#116'=>'Copyright',
    '2#120'=>'Caption',
    '2#122'=>'CaptionWriter'
    ];
	
  public function output_data( $image_path ) {
   $headers = $this->headers;	      
	
    $tab = new \stdClass();		

   if(\File::exists($image_path)){ 
	$size = getimagesize ($image_path, $info);

	 if(is_array($info) && isset($info["APP13"])){    	
        $iptc = iptcparse($info["APP13"]);		 
        foreach (array_keys($iptc) as $s) {              
            $c = count ($iptc[$s]);
           if(isset($headers[$s])){
   		     $key = $headers[$s];
			 if($headers[$s] === 'Keywords'){				       
						$tab->$key = [];
					}	 			 				
			 for ($i=0; $i <$c; $i++) 
            {				
				if(isset($key) && $key !== 'Keywords'){					
					$tab->$key = iconv('CP1250','UTF-8',$iptc[$s][$i]);
				}elseif($key){			
					array_push($tab->$key,iconv('CP1250','UTF-8',$iptc[$s][$i]));
				}			   
            }
          } 
		  }
       }
      } 	   
      return json_encode($info);	
     }	   
}


