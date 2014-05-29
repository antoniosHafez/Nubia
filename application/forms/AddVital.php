<?php

class Application_Form_AddVital extends Zend_Form
{

    public function init()
    {
        $this->setName('Add Vital');


        $vitalName = new Zend_Form_Element_Text('name');
        $vitalName->setLabel('Vital Name :')->setAttrib("placeholder", "Type Name")
        ->setRequired(true)->addValidator('NotEmpty');

        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('Add');

        $this->addElements(array($vitalName, $submit));
    }
}

