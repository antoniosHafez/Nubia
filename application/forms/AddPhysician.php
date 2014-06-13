<?php

class Application_Form_AddPhysician extends Zend_Form {

    private $action;
    private $user_id;

    public function __construct($param, $options = null) {
        parent::__construct($options);
        $this->action = $param["action"];
        
        if(isset($param["user"])) $this->user_id = $param["user"];

        $this->init();
    }

    public function init() {
        /* Form Elements & Other Definitions Here ... */


      /*  $validator = new Zend_Validate_Db_NoRecordExists(
                array(
            'table' => 'user',
            'field' => 'email',
        ));
        if ($this->action == "edit") {
            $validator->setExclude("id != $this->user_id");
        }

        $this->addElement("hidden", "id");

        if ($this->action == "edit") {
            $this->addElement("text", "name", array('label' => 'physician name:', 'required' => TRUE
            ));
        } else {

            $this->addElement("text", "name", array('label' => 'physician name:', 'required' => TRUE));
        }

        $this->addElement("text", "email", array('label' => 'physician Email:', 'required' => TRUE,
            'validators' => array(
                'EmailAddress', array($validator, true, array(
                        'table' => 'user',
                        'field' => 'email',
                        'messages' => array(
                            'recordFound' => 'Email already taken'
                        )
                    ))
            )
        ));
        if ($this->action == "edit") {
            $this->addElement("password", "password", array('label' => 'physician password:'));
        } else {
            $this->addElement("password", "password", array('label' => 'physician password:', 'required' => TRUE));
        }
*/
        $this->addElement("text", "title", array('label' => 'physician title:', 'required' => TRUE, 'class'=>'form-control'));

 /*       $this->addElement('radio', "sex", array(
            'label' => 'Gender',
            'multiOptions' => array(
                'M' => 'Male',
                'F' => 'Female',
            ),
        ));

        $this->addElement("text", "telephone", array('label' => 'physician telephone:', 'required' => TRUE));

        $this->addElement("text", "mobile", array('label' => 'physician mobile:', 'required' => TRUE));
        
  * /////drop down list and filled with groupNames
  */
        //$this->addElement("text", "group_id", array('label' => 'physician Group:', 'required' => TRUE));
        $groups = new Zend_Form_Element_Select('group_id', array('multiple' => false));
        $groupModel = new Application_Model_Physiciangroup();
        $groups->addMultiOption("Choose","Choose");
        foreach ($groupModel->fetchAll() as $group) {
            $groups->addMultiOption($group['id'], $group['name']);
        }
        $groups->setLabel("Choose Group")->setAttrib("class", "form-control");
        $groups->setRequired();
        $groups->setValue("Choose");
        $groups->setAttrib('disable',array("Choose"));
        $this->addElement($groups);
        ///////


        $this->addElement("submit", "submitbtn", array('label' => 'Submit'));
    }

}
