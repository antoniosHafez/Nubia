<?php

class Application_Form_NewUserForm extends Zend_Form
{
    private $user_id;
    public function __construct($userId, $options = null) {
        parent::__construct($options);
        $this->user_id = $userId;

        $this->init();
    }

    public function init()
    {
        $rolesModel = new Application_Model_Role();
        $rolesTypes = $rolesModel ->getRolesNames();
        
        $name = new Zend_Form_Element_Text("name");
        $name -> setRequired();
        $name -> addValidator(new Zend_Validate_Alpha()); 
        //$name ->setAttrib("class", "form-control");
        $name -> setLabel("Name");
        
        $gender = new Zend_Form_Element_Radio("sex");
        $gender -> setRequired();
        //$gender ->setAttrib("class", "btn btn-primary");
        $gender -> setLabel("Gender");
        $gender -> addMultiOptions(array(
            'M' => 'Male',
            'F' => 'Female'));
        $gender -> setSeparator('<br>'); 
        
        $email = new Zend_Form_Element_Text("email");
        $email ->addValidator(new Zend_Validate_EmailAddress());
        $email->setRequired();
        $email -> setLabel("Email");
        /*$emailValidator = new Zend_Validate_Db_NoRecordExists(array('table'=>'user','field'=>'email'));
        $email->addValidator($emailValidator);*/       
        if($this->user_id != 0)
            $emailValidator = new Zend_Validate_Db_NoRecordExists(array('table'=>'user','field'=>'email','exclude'=>array('field' => 'id','value' => $this->user_id)));
        else
            $emailValidator = new Zend_Validate_Db_NoRecordExists(array('table'=>'user','field'=>'email')); 
        $email->addValidator($emailValidator);
        
        
        $password = new Zend_Form_Element_Password("password");
        $password -> addValidator(new Zend_Validate_Alnum());
        $password -> setLabel("Password");
        //$password ->setAttrib("style", "display:none;");
                        
        $telephone = new Zend_Form_Element_Text("telephone");
        $telephone -> addValidator(new Zend_Validate_Digits());
        $telephone -> setLabel("Telephone");
        
        $mobile = new Zend_Form_Element_Text("mobile");
        $mobile -> addValidator(new Zend_Validate_Digits());
        $mobile -> setLabel("Mobile");
        
        $type = new Zend_Form_Element_Select("role_id");//,array('onchange'=>'checkSelection();'));
        $type -> setLabel("Account Type");
        $type->setRequired();
        $type ->setAttrib("onchange", "checkSelection();");
        $type ->addMultiOption("0", "Choose");
        foreach ($rolesTypes as $role) {
            //if($role['name'] == "physician" || $role['name'] == "clinician"){
            if($role['name'] != "guest"){
                $type -> addMultiOption($role['id'], $role['name']);
            }
        }
        $type->setValue("0");
        $type->setAttrib('disable',array("0"));
        
        $button = new Zend_Form_Element_Submit("btn");
        $button ->setLabel("Save");
        
        $this->addElements(array($name, $gender, $email, $password, $telephone, $mobile, $type, $button));
    }


}

