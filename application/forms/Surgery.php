<?php

class Application_Form_Surgery extends Zend_Form
{

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
        $surgeryID = new Zend_Form_Element_Hidden("surgeryID");
        
        $surgeryName = new Zend_Form_Element_Text("operation");
        $surgeryName ->setRequired();
        $surgeryName ->addValidator(new Zend_Validate_Alnum(TRUE));
        $surgeryName ->addValidator('Db_NoRecordExists', TRUE, array('table' => 'surgery', 'field' => 'operation'));
        $surgeryName ->setLabel("Surgery Name");
        
        $submit_add = new Zend_Form_Element_Submit("add");
        $submit_update = new Zend_Form_Element_Submit("update");
        
        $elements = array($surgeryID, $surgeryName, $submit_add, $submit_update);
        $this->addElements($elements);
    }


}

