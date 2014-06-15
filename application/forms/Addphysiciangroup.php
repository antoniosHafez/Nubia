<?php

class Application_Form_Addphysiciangroup extends Zend_Form
{

    public function init()
    {
         

        $this->addElement("text","name",array('label'=>'Group Name','required'=>true,'class'=>'form-control'));
        $this->addElement("hidden","id");
         $this->addElement("submit","submit",array('label'=>'Add','class'=>'btn btn-primary'));
        
     
        
        
     
    }


}

