<?php 

namespace App\Service;

class CheckingBooks {
    
    public function isFree($book){
        if($book->isFree()){
            return true;
        }else{
            return false;
        }
    }
	
}