<?php

class Application_Form_Medication extends Zend_Form
{

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
        $medicationID = new Zend_Form_Element_Hidden("medicationID");
        
        $medicationName = new Zend_Form_Element_Text("medicationName");
        $medicationName ->setRequired();
        $medicationName ->addValidator(new Zend_Validate_Alnum(TRUE));
        $medicationName ->addValidator('Db_NoRecordExists', TRUE, array('table' => 'medication', 'field' => 'name'));
        $medicationName ->setLabel("Medication Name")->setAttrib("class", "form-control");
        
        $submit_add = new Zend_Form_Element_Submit("add");
        $submit_add->setAttrib("class", "btn btn-primary");
        $submit_update = new Zend_Form_Element_Submit("update");
        $submit_update->setAttrib("class", "btn btn-primary");
        $submit_search = new Zend_Form_Element_Submit("search");
        $submit_search->setAttrib("class", "btn btn-primary");
        
        $elements = array($medicationName, $medicationID, $submit_add, $submit_update, $submit_search);
        $this->addElements($elements);
    }

}

