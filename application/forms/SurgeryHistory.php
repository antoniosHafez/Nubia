<?php

class Application_Form_SurgeryHistory extends Zend_Form
{

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
        $surgeryModel = new Application_Model_Surgery();
        $surgeryOptions = $surgeryModel->getSurgeryInHashArray();
        
        $physicianModel = new Application_Model_Physician();
        $physicianOptions = $physicianModel->getPhysicianInHashArray();
        
        $patientModel = new Application_Model_Patient();
        $patientOptions = $patientModel->getPatientInHashArray();
        
        $id = new Zend_Form_Element_Hidden("id");
        
        $surgery = new Zend_Form_Element_Select("surgery");
        $surgery ->setRequired();
        $surgery ->addMultiOptions($surgeryOptions);
        $surgery ->setLabel("Surgery");
        
        $patient = new Zend_Form_Element_Select("patient");
        $patient ->setRequired();
        $patient ->addMultiOptions($patientOptions);
        $patient ->setLabel("Patient");
        
        $physician = new Zend_Form_Element_Select("physician");
        $physician ->setRequired();
        $physician ->addMultiOptions($physicianOptions);
        $physician ->setLabel("Physician");
        
        $date = new Zend_Form_Element_Text("date");
        $date ->setRequired();
        $date ->addValidator(new Zend_Validate_Date(array('format' => 'yyyy-mm-dd')));
        $date ->setLabel("Date");
        
        $submit_add = new Zend_Form_Element_Submit("add");
        $submit_update = new Zend_Form_Element_Submit("update");
        
        $elements = array($id, $surgery, $patient, $physician, $date, $submit_add, $submit_update);
        $this->addElements($elements);
    }


}

