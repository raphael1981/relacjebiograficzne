<?php

namespace App\Helpers;

class SearchHelp{

    public static function makeRegexSearch($frase){

        $limits = [[3,0],[4,1],[7,2],[9,3],[12,4],[14,5]];

        $frase = trim(preg_replace('/[\s]+/', ' ', $frase));


        $ar = preg_split('/\sdo\s|\sna\s|\si\s|\sz\s|\sod\s|\sw\s|\.\s|\,\s|\s/', $frase);		
		
		$cutarr = [];
/*
        foreach($limits as $l){

            foreach($ar as $k=>$a){

                if(strlen($a)<=$l[0]){
                    $cutarr[$k] = substr($a, 0, (strlen($a)-$l[1]));
                }elseif(strlen($a)>12){
                    $cutarr[$k] = substr($a, 0, (strlen($a)-6));
                }

            }

        }        
*/

        //foreach($limits as $l){

            foreach($ar as $a){
				if(strlen($a) < 6)
					array_push($cutarr, $a);
			    elseif(strlen($a) < 8)
					array_push($cutarr,substr($a, 0, (strlen($a)-1)));
				elseif(strlen($a) < 10)	
				    array_push($cutarr,substr($a, 0, (strlen($a)-2)));
                elseif(strlen($a) < 12)						
				    array_push($cutarr,substr($a, 0, (strlen($a)-3)));
                else  
				   array_push($cutarr,substr($a, 0, (strlen($a)-4)));					
            }

        //}        


        $narr = [];
        foreach($cutarr as $a){
            array_push($narr,'('.$a.')');
        }

        //$frase = implode('.{0,10}', $narr);
        //$frase = join(".{0,10}", $narr).'([^,\.\? ]+)';
		$frase = " ".join(".{0,10}", $narr);
		//[a-z0-9]{5,15}$

        return $frase;

    }

}