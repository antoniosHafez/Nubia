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
        $diseaseName ->setLabel("Disease Name")->setAttrib("class", "form-control");
        
        $submit_add = new Zend_Form_Element_Submit("add");
        $submit_add->setAttrib("class", "btn btn-primary");
        $submit_update = new Zend_Form_Element_Submit("update");
        $submit_update->setAttrib("class", "btn btn-primary");
        
        $elements = array($diseaseID, $diseaseName, $submit_add, $submit_update);
        $this->addElements($elements);
        
    }


}

