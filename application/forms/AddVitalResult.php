<?php

class Application_Form_AddVitalResult extends Zend_Form
{
    private $type;

    public function __construct($param,$options = null) {
        parent::__construct($options);
        $this->type = $param["type"];
        $this->init();
    }

    public function init()
    {
        $this->setName('Add Vital Result');
        
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
        
        $vitalId = new Zend_Form_Element_Select('vitalId');
        $vitalId->setLabel('Vital Name : ')
        ->setRequired(true)->addValidator('NotEmpty', true)
        ->setRegisterInArrayValidator(false);

        $vitalResult = new Zend_Form_Element_Text('data');
        $vitalResult->setLabel('Vital Result :')->setAttrib("placeholder", "Enter Result");

        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('Add');

        $this->addElements(array($id, $requestId, $vitalId, $vitalResult, $submit));
    }
}


