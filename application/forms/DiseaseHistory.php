<?php

class Application_Form_DiseaseHistory extends Zend_Form
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
        $diseaseModel = new Application_Model_Disease();
        $diseaseOptions = $diseaseModel->getDiseaseInHashArray();
        
        $patientModel = new Application_Model_Patient();
        $patientOptions = $patientModel->getPatientInHashArray();
        
        $id = new Zend_Form_Element_Hidden("id");
        
        $disease = new Zend_Form_Element_Select("disease");
        $disease ->setRequired();
        $disease ->addMultiOptions($diseaseOptions);
        $disease ->setLabel("Disease");
        
        if($this->not_pat == "1"){
             $patient = new Zend_Form_Element_Hidden("patient");
         }  else {
            $patient = new Zend_Form_Element_Select("patient");
            $patient ->setRequired();
            $patient ->addMultiOptions($patientOptions);
            $patient ->setLabel("Patient");        
         }        
        
        $date = new Zend_Form_Element_Text("date");
        $date ->setRequired();
        $date ->addValidator(new Zend_Validate_Date(array('format' => 'yyyy-mm-dd')));
        $date ->setLabel("Date");
        
        $submit_add = new Zend_Form_Element_Submit("add");
        $submit_update = new Zend_Form_Element_Submit("update");
        
        $elements = array($id, $disease, $patient, $date, $submit_add, $submit_update);
        $this->addElements($elements);
    }


}

