<?php

class Application_Form_AddPhysician extends Zend_Form
{

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
        
      

        
        $this->addElement("hidden","id");
      
        
        $this->addElement("text","name",array('label'=>'physician name:','required'=>TRUE));
      
        $this->addElement("text","title",array('label'=>'physician title:','required'=>TRUE));
        
        $this->addElement("text","sex",array('label'=>'physician Gender:',  'required'=> TRUE));
        
        $this->addElement("text","telephone",array('label'=>'physician telephone:',  'required'=> TRUE));
        
        $this->addElement("text","mobile",array('label'=>'physician mobile:',  'required'=> TRUE));
        
        $this->addElement("text","Group name",array('label'=>'physician Group:',  'required'=> TRUE));
        
        $this->addElement("text","sex",array('label'=>'physician Gender:',  'required'=> TRUE));
         
        $this->addElement("submit","submitbtn",array('label'=>'Add'));
      
        
        
    }


}

