<?php

class Application_Form_AddVitalResult extends Zend_Form
{

    public function init()
    {
        $this->setName('Add Vital Result');
        
        $requestId = new Zend_Form_Element_Select('requestId');
        $requestId->setLabel('Visit Request : ')
        ->setRequired(true)->addValidator('NotEmpty', true);
        
        $vitalId = new Zend_Form_Element_Select('vitalId');
        $vitalId->setLabel('Vital Name : ')
        ->setRequired(true)->addValidator('NotEmpty', true);

        $vitalResult = new Zend_Form_Element_Text('restResult');
        $vitalResult->setLabel('Vital Result :')->setAttrib("placeholder", "Enter Result");

        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('Add');

        $this->addElements(array($requestId, $vitalId, $vitalResult, $submit));
    }
}


