<?php

class Application_Form_MedicationHistory extends Zend_Form
{

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
        $medicationModel = new Application_Model_Medication();
        $medicationOptions = $medicationModel->getMedicationInHashArray();
        
        $id = new Zend_Form_Element_Hidden("id");
        
        $medication = new Zend_Form_Element_Select("medication");
        $medication ->setRequired();
        $medication ->addMultiOption($medicationOptions);
        $medication ->setLabel("Medication");
        
        $patient = new Zend_Form_Element_Select("patient");
        $patient ->setRequired();
        $patient ->addMultiOption($medicationOptions);
        $patient ->setLabel("Patient");
        
        $physician = new Zend_Form_Element_Select("physician");
        $physician ->setRequired();
        $physician ->addMultiOption($medicationOptions);
        $physician ->setLabel("Physician");
        
        $visit = new Zend_Form_Element_Select("visit");
        $visit ->setRequired();
        $visit ->addMultiOption($medicationOptions);
        $visit ->setLabel("Visit");
        
        $submit_add = new Zend_Form_Element_Submit("add");
        $submit_update = new Zend_Form_Element_Submit("update");
        
        $elements = array($id, $medication, $patient, $physician, $visit, $submit_add, $submit_update);
        $this->addElements($elements);
    }


}

