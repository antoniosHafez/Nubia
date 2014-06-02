<?php

class Application_Form_MedicationHistory extends Zend_Form
{

    private $not_pat;
    public function __construct($param, $options = null) {
        parent::__construct($options);
        $this->not_pat = $param["not_pat"];

        $this->init();
    }

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
        $medicationModel = new Application_Model_Medication();
        $medicationOptions = $medicationModel->getMedicationInHashArray();
        
        $visitModel = new Application_Model_Visit();
        $visitOptions = $visitModel->getVisitsInHashArray();
        
        $physicianModel = new Application_Model_Physician();
        $physicianOptions = $physicianModel->getPhysicianInHashArray();
        
        $patientModel = new Application_Model_Patient();
        $patientOptions = $patientModel->getPatientInHashArray();
        
        $id = new Zend_Form_Element_Hidden("id");
        
        $medication = new Zend_Form_Element_Select("medication");
        $medication ->setRequired();
        $medication ->addMultiOptions($medicationOptions);
        $medication ->setLabel("Medication");
        
         if($this->not_pat == "1"){
            $patient = new Zend_Form_Element_Hidden("patient");
            $physician = new Zend_Form_Element_Hidden("physician");
            $physician -> setValue(NULL);
            $visit = new Zend_Form_Element_Hidden("visit");
            $visit -> setValue(NULL);
         }  else {
            $patient = new Zend_Form_Element_Select("patient");
            $patient ->setRequired();
            $patient ->addMultiOptions($patientOptions);
            $patient ->setLabel("Patient");
                
            $physician = new Zend_Form_Element_Select("physician");
            //$physician ->setRequired();
            $physician ->addMultiOptions($physicianOptions);
            $physician ->setLabel("Physician");

            $visit = new Zend_Form_Element_Select("visit");
            //$visit ->setRequired();
            $visit ->addMultiOptions($visitOptions);
            $visit ->setLabel("Visit");
         }

       $submit_add = new Zend_Form_Element_Submit("add");
       $submit_update = new Zend_Form_Element_Submit("update");

       $elements = array($id, $medication, $patient, $physician, $visit, $submit_add, $submit_update);
       
       $this->addElements($elements);
    }


}

