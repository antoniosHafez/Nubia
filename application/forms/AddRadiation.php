<?php

class Application_Form_AddRadiation extends Zend_Form
{

    public function init()
    {
        $this->setName('Add Radiation');

        $radiationId = new Zend_Form_Element_Hidden("id");
    
        $radiationName = new Zend_Form_Element_Text('name');
        $radiationName->setLabel('Radiation Name :')->setAttrib("placeholder", "Type Name")
        ->setRequired(true)->addValidator('NotEmpty');

        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('Add');

        $this->addElements(array($radiationId, $radiationName, $submit));
    }
}

