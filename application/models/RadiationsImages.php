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
        //$select = $this->select()->where("radiation_id=$resultId");
        $select = $this->select()->from("radiation_result",array("pk_radiation_id" => "radiation_id"))
                ->setIntegrityCheck(FALSE)
                ->joinInner($this->_name, "radiation_result.id = radiation_images.radiation_id")
                ->joinInner("radiation", "radiation.id = radiation_result.radiation_id",array("radiation_name" => "name"))
                ->where("radiation_images.radiation_id=$resultId");
        
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

