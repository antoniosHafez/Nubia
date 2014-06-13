<?php

class Application_Form_UserProfile extends Zend_Form
{
    protected $user_id;
    public function __construct($userId, $options = null) {
        parent::__construct($options);
        $this->user_id = $userId;
        $this->init();
    }    

    public function init()
    {
        $name = new Zend_Form_Element_Text("name");
        $name -> setRequired();
        $name -> addValidator(new Zend_Validate_Alpha()); 
        $name ->setAttrib("class", "form-control");
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
        $email -> setLabel("Email")->setAttrib("class", "form-control");
        $email->setRequired();
        if($this->user_id != 0){
            //echo $this->user_id;
            //exit;
            $emailValidator = new Zend_Validate_Db_NoRecordExists(array('table'=>'user','field'=>'email','exclude'=>array('field' => 'id','value' => $this->user_id)));
        }else
            $emailValidator = new Zend_Validate_Db_NoRecordExists(array('table'=>'user','field'=>'email')); 
        $email->addValidator($emailValidator);
        
        $password = new Zend_Form_Element_Password("password");
        $password -> addValidator(new Zend_Validate_Alnum());
        $password -> setLabel("Password")->setAttrib("class", "form-control")->setRequired();
        //$password ->setAttrib("style", "display:none;");
                        
        $telephone = new Zend_Form_Element_Text("telephone");
        $telephone -> addValidator(new Zend_Validate_Digits());
        $telephone -> setLabel("Telephone")->setAttrib("class", "form-control");
        
        $mobile = new Zend_Form_Element_Text("mobile");
        $mobile -> addValidator(new Zend_Validate_Digits());
        $mobile -> setLabel("Mobile")->setAttrib("class", "form-control");
                
        $button = new Zend_Form_Element_Submit("btn");
        $button ->setLabel("Save")->setAttrib("class", "btn btn-primary");
        
        $this->addElements(array($name, $gender, $email, $password, $telephone, $mobile, $button));
    }


}

