<?php

class Application_Form_AddTest extends Zend_Form
{

    public function init()
    {
        $this->setName('Add Test');

        $testName = new Zend_Form_Element_Text('name');
        $testName->setLabel('Test Name :')->setAttrib("placeholder", "Type Name")
        ->setRequired(true)->addValidator('NotEmpty');

        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('Add');

        $this->addElements(array($testName, $submit));
    }
}


