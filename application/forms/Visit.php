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
             
       
        $patient = new Zend_Form_Element_Select('patient_id', array('multiple' => false,'required'=>true));
        $patientModel = new Application_Model_Patient();
        $patient->setAttrib("class", "form-control");
        $patient->addMultiOption(Null, "choose patient");

        foreach ($patientModel->listPatients() as $pat) {
            $patient->addMultiOption($pat['id'], $pat['name']);
        }
        $patient->setLabel("Choose patient");

        if($this->patientGP == "add-gp")
        {
            $patient = new Zend_Form_Element_Hidden("patient_id");
        }

        $this->addElement($patient);
        
        $group = new Zend_Form_Element_Select('group_id', array('multiple' => false,'required'=>true));
        $groupModel = new Application_Model_Physiciangroup();
        $group->setAttrib("class", "form-control");
        $group->addMultiOption(Null, "choose group");
        foreach ($groupModel->fetchAll()->toArray() as $grp) {
            $group->addMultiOption($grp['id'], $grp['name']);
        }
        $group->setLabel("Choose Group");
        $this->addElement($group);
        
        $physican = new Zend_Form_Element_Select('physican_id', array('multiple' => false));
        $physicanModel = new Application_Model_Physician();
        $physican->setRegisterInArrayValidator(false);      
        $physican->setAttrib("class", "form-control");
        $physican->addMultiOption(Null, "choose physician");
        $physican->setAttrib("disabled", "");
        $physican->setLabel("Choose physician");
        $this->addElement($physican);

           
          $this->addElement('radio', "type", array(
            'label' => 'Type',
            'multiOptions' => array(
            'kashf' => 'kashf',
            'Estshara' => 'Estshara',
            ),
              'Value' => 'kashf'
        ));
              
              $checkbox = new Zend_Form_Element_Checkbox('depandency');
                $checkbox->setLabel('Dependency');
                $this->addElement($checkbox);
           }
        
        $this->addElement('submit', 'submit', array('ignore'=> true,'label'=> 'submit','order'=>10,"class" => "btn btn-primary"));
    }
}
