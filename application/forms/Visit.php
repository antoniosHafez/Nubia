<?php

class Application_Form_Visit extends Zend_Form {
private $action = NULL;
private $patientGP = NULL;

public function __construct($param,$options = null) {
     parent::__construct($options);
     $this->action=$param["action"];
     if(isset($param["patientGP"]))
        $this->patientGP=$param["patientGP"];
    
     
     $this->init();
 }
    public function init() {
       
                   $this->setMethod('post');
                  if($this->action == "edit"){
                    $this->addElement('Hidden', 'id');
                  }
                 
        $this->addElement('hidden', 'date', array('value'=>Date("Y-m-d")));
        $this->addElement('textarea', 'description', array('label' => 'Description :', 'required' => true, 'filters' => array('StringTrim'), 'class' => 'form-control', 'rows' => '5', 'cols' => '40'));
           if($this->action == "edit" || $this->action == "add"){
             
          /////drop down list and filled with physicians
        
        $physican = new Zend_Form_Element_Select('physican_id', array('multiple' => false));
        $physicanModel = new Application_Model_Physician();
        $physican->addMultiOption(Null, "choose physician");
        foreach ($physicanModel->selectFullPhysician() as $phy) {
            $physican->addMultiOption($phy['id'], $phy['name']);
        }
        $physican->setLabel("Choose physician");
        $physican->setOrder(5);
        $this->addElement($physican);
        ///////

            /////drop down list and filled with patients
        
        
            $patient = new Zend_Form_Element_Select('patient_id', array('multiple' => false,'required'=>true));
            $patientModel = new Application_Model_Patient();
            $patient->addMultiOption(Null, "choose patient");
                    $physican->setOrder(6);

            foreach ($patientModel->listPatients() as $pat) {
                $patient->addMultiOption($pat['id'], $pat['name']);
            }
            $patient->setLabel("Choose patient");
            
            if($this->patientGP == "add-gp")
            {
                $patient = new Zend_Form_Element_Hidden("patient_id");
            }
            
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
        $physican->setOrder(7);
        $this->addElement($group);
        ///////

           
          $this->addElement('radio', "type", array(
            'label' => 'Type',
             'order'=>2, 
            'multiOptions' => array(
                'kashf' => 'kashf',
                'Estshara' => 'Estshara',
            ),
              'Value' => 'kashf'
        ));
              
              $checkbox = new Zend_Form_Element_Checkbox('depandency');
                $checkbox->setLabel('Dependency');
                      $physican->setOrder(8);
                $this->addElement($checkbox);
           }
        
        $this->addElement('submit', 'submit', array('ignore'=> true,'label'=> 'submit','order'=>10));
    

    }

}
