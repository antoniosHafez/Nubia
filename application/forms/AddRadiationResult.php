<?php

class Application_Form_AddRadiationResult extends Zend_Form
{
    private $type;

    public function __construct($param,$options = null) {
        parent::__construct($options);
        $this->type = $param["type"];
        $this->init();
    }

    public function init()
    {
        $this->setName('Add Radiation Result');
        
        $this->setEnctype("multipart/form-data");
        $id = new Zend_Form_Element_Hidden('resultId');
        
        if($this->type == "patient")
        {
            $requestId = new Zend_Form_Element_Hidden('requestId');
        }
        else
        {
            $requestId = new Zend_Form_Element_Select('requestId');
            $requestId->setLabel('Visit Request : ')
            ->setRequired(true)->addValidator('NotEmpty', true)
            ->setRegisterInArrayValidator(false);
        }
        
        $vitalId = new Zend_Form_Element_Select('radiationId');
        $vitalId->setLabel('Radiation Name : ')
        ->setRequired(true)->addValidator('NotEmpty', true)
        ->setRegisterInArrayValidator(false);
        
        $radiationImages = new Zend_Form_Element_File("file[]");
        $radiationImages->setAttrib("multiple", "");
        $radiationImages->setLabel("Choose Radiations Images : (Mark All Radiations)");

        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('Add');

        $this->addElements(array($requestId, $vitalId, $radiationImages, $submit));
    }
}


