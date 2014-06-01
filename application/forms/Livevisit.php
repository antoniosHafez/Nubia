<?php

class Application_Form_Livevisit extends Zend_Form
{

    public function init()
    {
        ////////
        $radiation_id = new Zend_Form_Element_Multiselect('radiation_id');
        $radiation_id->setLabel('Radiation Name : ')
        ->setRequired(false)->addValidator('NotEmpty', true)
        ->setRegisterInArrayValidator(false);
        $radModel = new Application_Model_Radiation();
       
        foreach ($radModel->fetchAll()->toArray() as $rad) {
            $radiation_id->addMultiOption($rad['id'], $rad['name']);
        }
        $this->addElement($radiation_id);
        ////////
        $disease_id = new Zend_Form_Element_MultiSelect('disease_id');
        $disease_id->setLabel('disease Name : ')
        ->setRequired(false)->addValidator('NotEmpty', true)
        ->setRegisterInArrayValidator(false);
        $disModel = new Application_Model_Disease();
     
        foreach ($disModel->fetchAll()->toArray() as $dis) {
            $disease_id->addMultiOption($dis['id'], $dis['name']);
        }
        $this->addElement($disease_id);
        /////
         $medication_id = new Zend_Form_Element_MultiSelect('medication_id');
        $medication_id->setLabel('Medication Name : ')
        ->setRequired(false)->addValidator('NotEmpty', true)
        ->setRegisterInArrayValidator(false);
        $medModel = new Application_Model_Medication();
      
        foreach ($medModel->fetchAll()->toArray() as $med) {
            $medication_id->addMultiOption($med['id'], $med['name']);
        }
        $this->addElement($medication_id);
       /////
         $surgery_id = new Zend_Form_Element_MultiSelect('surgery_id');
        $surgery_id->setLabel('Surgery Name : ')
        ->setRequired(false)->addValidator('NotEmpty', true)
                
        ->setRegisterInArrayValidator(false);
        $surModel = new Application_Model_Surgery();
      
        foreach ($surModel->fetchAll()->toArray() as $sur) {
            $surgery_id->addMultiOption($sur['id'], $sur['operation']);
        }
        $this->addElement($surgery_id);
       /////////
         $vital_id = new Zend_Form_Element_MultiSelect('vital_id');
        $vital_id->setLabel('Vital Name : ')
        ->setRequired(false)->addValidator('NotEmpty', true)
        ->setRegisterInArrayValidator(false);
        $vitModel = new Application_Model_Vital();
      
        foreach ($vitModel->fetchAll()->toArray() as $vit) {
            $vital_id->addMultiOption($vit['id'], $vit['name']);
        }
        $this->addElement($vital_id);
        //////////
         $test_id = new Zend_Form_Element_Multiselect('test_id');
        $test_id->setLabel('Test Name : ')
        ->setRequired(false)->addValidator('NotEmpty', true)
        ->setRegisterInArrayValidator(false);
        $testModel = new Application_Model_Test();
      
        foreach ($testModel->fetchAll()->toArray() as $test) {
            $test_id->addMultiOption($test['id'], $test['name']);
        }
        $this->addElement($test_id);
        
   $this->addElement('textarea', 'notes', array('label' => 'notes :', 'required' => true, 'filters' => array('StringTrim')));
  
         $this->addElement('submit', 'submit', array('ignore'=> true,'label'=> 'submit'));
    }


}

