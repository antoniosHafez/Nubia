<?php

class Application_Form_AddType extends Zend_Form
{

    public function init()
    {
        $typeId = new Zend_Form_Element_Hidden("typeId");

        $typeName = new Zend_Form_Element_Text('typeName');
        $typeName->setLabel('Type Name :')->setAttrib("placeholder", "Type Name")
        ->setRequired(true)->addValidator('NotEmpty')->setAttrib("class", "form-control");

        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('Add')->setAttrib("class", "btn btn-primary");

        $this->addElements(array($typeId, $typeName, $submit));
    }


}

