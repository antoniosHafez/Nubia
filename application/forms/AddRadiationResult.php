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

        $radiationResult = new Zend_Form_Element_Text('data');
        $radiationResult->setLabel('Radiation Result :')->setAttrib("placeholder", "Enter Result");

        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('Add');

        $this->addElements(array($requestId, $vitalId, $radiationResult, $submit));
    }
}


