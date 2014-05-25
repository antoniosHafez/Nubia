<?php

class Application_Form_AddTestResult extends Zend_Form
{

    public function init()
    {
        $this->setName('Add Test Result');
        
        $requestId = new Zend_Form_Element_Select('requestId');
        $requestId->setLabel('Visit Request : ')
        ->setRequired(true)->addValidator('NotEmpty', true);
        
        $vitalId = new Zend_Form_Element_Select('vitalId');
        $vitalId->setLabel('Vital Name : ')
        ->setRequired(true)->addValidator('NotEmpty', true);

        $testResult = new Zend_Form_Element_Text('restResult');
        $testResult->setLabel('Test Result :')->setAttrib("placeholder", "Enter Result");

        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('Add');

        $this->addElements(array($requestId, $vitalId, $testResult, $submit));
    }
}


