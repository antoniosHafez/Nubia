<?php

class Application_Form_SearchPatientId extends Zend_Form
{

    public function init()
    {
        $patientId = new Zend_Form_Element_Text("IDNumber");
        $patientId -> addValidator(new Zend_Validate_Digits());
        $patientId ->setLabel("Patient's SSN");
        
        $button = new Zend_Form_Element_Submit("btn");
        $button ->setLabel("Search");
        
        $this->addElements(array($patientId, $button));
    }


}

