<?php

class Application_Form_Physiciangroup extends Zend_Form
{

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
    
        
                
        
        $this->setName('Add Pysiciangroup');
 $validator = new Zend_Validate_Db_NoRecordExists(
                array(
            'table' => 'group',
            'field' => 'name',
             
        ));
       

         $this->addElement("text","id",array('label'=>'Physician id :','required'=>true,'class'=>"form-control"));
      
        $this->addElement("text","name",array('label'=>'physician name:','required'=>TRUE,'class'=>"form-control",
              'validators' => array(
                'name', array($validator, true, array(
                        'table' => 'group',
                        'field' => 'name',
                        'messages' => array(
                            'recordFound' => 'Name already taken'
                        )
                 )   ))
            
            ));
      
        
        #$physiciangroupName = new Zend_Form_Element_Text('physiciangroup');
        #$physiciangroupName->setLabel('Physiciangroup Name :')->setAttrib("placeholder", "Type Name")
        #->setRequired(true)->addPhysiciangroup('NotEmpty');

       # $submit = new Zend_Form_Element_Submit('submit');
       # $submit->setLabel('Add');

        
        $this->addElement("submit","btn",array('label'=>'Add','class'=>"btn btn-primary"));
      
        
        //$this->addElements(array($physiciangroupName, $submit));
   
        
        
        
    }


}

