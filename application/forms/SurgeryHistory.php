<?php

class Application_Form_SurgeryHistory extends Zend_Form
{

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
        $surgeryModel = new Application_Model_Surgery();
        $surgeryOptions = $surgeryModel->getSurgeryInHashArray();
        
        $id = new Zend_Form_Element_Hidden("id");
        
        $surgery = new Zend_Form_Element_Select("surgery");
        $surgery ->setRequired();
        $surgery ->addMultiOption($surgeryOptions);
        $surgery ->setLabel("Surgery");
        
        $patient = new Zend_Form_Element_Select("patient");
        $patient ->setRequired();
        $patient ->addMultiOption($surgeryOptions);
        $patient ->setLabel("Patient");
        
        $physician = new Zend_Form_Element_Select("physician");
        $physician ->setRequired();
        $physician ->addMultiOption($surgeryOptions);
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

