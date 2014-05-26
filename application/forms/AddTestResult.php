<?php

class Application_Form_AddTestResult extends Zend_Form
{

    public function init()
    {
        $this->setName('Add Test Result');
        
        $requestId = new Zend_Form_Element_Select('requestId');
        $requestId->setLabel('Visit Request : ')
        ->setRequired(true)->addValidator('NotEmpty', true)
        ->setRegisterInArrayValidator(false);
        
        $vitalId = new Zend_Form_Element_Select('testId');
        $vitalId->setLabel('Test Name : ')
        ->setRequired(true)->addValidator('NotEmpty', true)
        ->setRegisterInArrayValidator(false);

        $testResult = new Zend_Form_Element_Text('data');
        $testResult->setLabel('Test Result :')->setAttrib("placeholder", "Enter Result");

        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('Add');

        $this->addElements(array($requestId, $vitalId, $testResult, $submit));
    }
}


