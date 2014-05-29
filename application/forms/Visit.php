<?php

class Application_Form_Visit extends Zend_Form {
private $action;

public function __construct($param,$options = null) {
     parent::__construct($options);
     $this->action=$param["action"];
    
     
     $this->init();
 }
    public function init() {
       
                   $this->setMethod('post');
                  if($this->action == "edit"){
                    $this->addElement('Hidden', 'id');
                  }
                 
             $this->addElement('text', 'date', array('label' => 'visit request date :', 'required' => true));
        $this->addElement('textarea', 'description', array('label' => 'description :', 'required' => true, 'filters' => array('StringTrim')));
         
          /////drop down list and filled with physicians
        
        $physican = new Zend_Form_Element_Select('physican_id', array('multiple' => false));
        $physicanModel = new Application_Model_Physician();
        $physican->addMultiOption(Null, "choose physician");
        foreach ($physicanModel->selectFullPhysician() as $phy) {
            $physican->addMultiOption($phy['id'], $phy['name']);
        }
        $physican->setLabel("Choose physician");
        $this->addElement($physican);
        ///////

            /////drop down list and filled with patients
        
        $patient = new Zend_Form_Element_Select('patient_id', array('multiple' => false,'required'=>true));
        $patientModel = new Application_Model_Patient();
        $patient->addMultiOption(Null, "choose patient");
        foreach ($patientModel->listPatients() as $pat) {
            $patient->addMultiOption($pat['id'], $pat['name']);
        }
        $patient->setLabel("Choose patient");
        $this->addElement($patient);
        ///////
        
          /////drop down list and filled with patients
        
        $group = new Zend_Form_Element_Select('group_id', array('multiple' => false,'required'=>true));
        $groupModel = new Application_Model_Physiciangroup();
        $group->addMultiOption(Null, "choose group");
        foreach ($groupModel->fetchAll()->toArray() as $grp) {
            $group->addMultiOption($grp['id'], $grp['name']);
        }
        $group->setLabel("Choose Group");
        $this->addElement($group);
        ///////

           
          $this->addElement('radio', "type", array(
            'label' => 'Type',
              
            'multiOptions' => array(
                'kashf' => 'kashf',
                'Estshara' => 'Estshara',
            ),
              'Value' => 'kashf'
        ));
            $this->addElement('textarea', 'notes', array('label' => 'notes :', 'required' => true, 'filters' => array('StringTrim')));
              
              $checkbox = new Zend_Form_Element_Checkbox('depandency');
                $checkbox->setLabel('Depandency');
                $checkbox->setUncheckedValue("A");
                $this->addElement($checkbox);
              
        $this->addElement('submit', 'submit', array('ignore'=> true,'label'=> 'submit'));
    

    }

}
