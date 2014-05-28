<?php

class Application_Form_AddPhysician extends Zend_Form {
private $action;

public function __construct($param,$options = null) {
     parent::__construct($options);
     $this->action=$param["action"];
     
     $this->init();
 }

    public function init() {
        /* Form Elements & Other Definitions Here ... */



        $this->addElement("hidden", "id");


        $this->addElement("text", "name", array('label' => 'physician name:', 'required' => TRUE));
        $this->addElement("text", "email", array('label' => 'physician Email:', 'required' => TRUE));
        if($this->action=="edit"){
          $this->addElement("password", "password", array('label' => 'physician password:'));  
        }else
        {
           $this->addElement("password", "password", array('label' => 'physician password:', 'required' => TRUE)); 
        }
        
        $this->addElement("text", "title", array('label' => 'physician title:', 'required' => TRUE));

       $this->addElement('radio', "sex", array(
            'label' => 'Gender',
            'multiOptions' => array(
                'M' => 'Male',
                'F' => 'Female',
            ),
        ));

        $this->addElement("text", "telephone", array('label' => 'physician telephone:', 'required' => TRUE));

        $this->addElement("text", "mobile", array('label' => 'physician mobile:', 'required' => TRUE));
        /////drop down list and filled with groupNames
        $this->addElement("text", "group_id", array('label' => 'physician Group:', 'required' => TRUE));
        $groups = new Zend_Form_Element_Select('group_id', array('multiple' => false));
        $groupModel = new Application_Model_Physiciangroup();
        foreach ($groupModel->fetchAll() as $group) {
            $groups->addMultiOption($group['id'], $group['name']);
        }
        $groups->setLabel("Choose Group");
        $this->addElement($groups);
        ///////

        
        $this->addElement("submit", "submitbtn", array('label' => 'Add'));
    }

}
