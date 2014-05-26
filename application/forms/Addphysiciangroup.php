<?php

class Application_Form_Addphysiciangroup extends Zend_Form
{

    public function init()
    {
         

        $this->addElement("text","name",array('label'=>'Add Group','required'=>true));
        $this->addElement("hidden","id");
         $this->addElement("submit","submit",array('label'=>'Add'));
        
     
        
        
     
    }


}

