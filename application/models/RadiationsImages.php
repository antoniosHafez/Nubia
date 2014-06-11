<?php

class Application_Model_RadiationsImages extends Zend_Db_Table_Abstract
{
    protected $_name = "radiation_images";
    
    public function addRadiationImage($radiationId, $timeStamp, $extension) {
        $row = $this->createRow();
        
        $row->radiation_id = $radiationId;
        $row->title = $timeStamp.".".$extension;
        
        return $row->save();
    }
    
    public function getRadiationImages($resultId) {
        $select = $this->select()->where("radiation_id=$resultId");
        
        return $this->fetchAll($select)->toArray();
    }
    
    public function removeImage($imageId) {
        $this->delete("id=$imageId");
    }
    
    public function getRadImgByVisitID($visitID)
    {
         $select = $this->select()
        ->setIntegrityCheck(false)
        ->from(array('images' => 'radiation_images',))
        ->join(array('result' => 'radiation_result'),'images.radiation_id = result.id',array("visitid" => "result.visit_request_id"))
       ->where("result.visit_request_id = $visitID");
        $row =  $this->fetchAll($select);
        
        if($row) {
            return $row->toArray();
        }
        else {
            return NULL;
        }
    }

}

