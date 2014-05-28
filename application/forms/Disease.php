<?php

class Application_Form_Disease extends Zend_Form
{

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
        
        $diseaseID = new Zend_Form_Element_Hidden("diseaseID");
        
        $diseaseName = new Zend_Form_Element_Text("diseaseName");
        $diseaseName ->setRequired();
        $diseaseName ->addValidator(new Zend_Validate_Alnum(TRUE));
        $diseaseName ->addValidator('Db_NoRecordExists', TRUE, array('table' => 'disease', 'field' => 'name'));
        $diseaseName ->setLabel("Disease Name");
        
        $submit_add = new Zend_Form_Element_Submit("add");
        $submit_update = new Zend_Form_Element_Submit("update");
        
        $elements = array($diseaseID, $diseaseName, $submit_add, $submit_update);
        $this->addElements($elements);
        
    }


}

