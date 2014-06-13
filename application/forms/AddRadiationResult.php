<?php

class Application_Form_AddRadiationResult extends Zend_Form
{
    private $type;

    public function __construct($param = false,$options = null) {
        parent::__construct($options);
        $this->type = $param;
        $this->init();
    }

    public function init()
    {
        $this->setName('Add Radiation Result');
        
        $this->setEnctype("multipart/form-data");
        $id = new Zend_Form_Element_Hidden('resultId');
        
        if($this->type)
        {            
            $requestId = new Zend_Form_Element_Select('requestId');
            $requestId->setLabel('Visit Request : ')
            ->setAttrib("class", "form-control")
            ->setRequired(true)->addValidator('NotEmpty', true)
            ->setRegisterInArrayValidator(false);
        }
        else
        {
            $requestId = new Zend_Form_Element_Hidden('requestId');
        }
        
        $vitalId = new Zend_Form_Element_Select('radiationId');
        $vitalId->setLabel('Radiation Name : ')
        ->setAttrib("class", "form-control")
        ->setRequired(true)->addValidator('NotEmpty', true)
        ->setRegisterInArrayValidator(false);
        
        $radiationImages = new Zend_Form_Element_File("file");
        $radiationImages->setMultiFile(3);
        $radiationImages->setLabel("Choose Radiations Images : (Mark All Radiations)")
        ->setAttrib("class", "form-control");
        
        $add = new Zend_Form_Element_Button("add");
        $add->setAttrib("onclick", "addFile();");
        $add->setAttrib("class", "lightButton");
        $add->setLabel("Add Another Image");

        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('Add')
        ->setAttrib("class", "btn btn-primary");

        $this->addElements(array($id, $requestId, $vitalId, $radiationImages, $add, $submit));
    }
}


