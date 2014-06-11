<?php

class Application_Form_UserProfile extends Zend_Form
{

    public function init()
    {
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
        $email -> setLabel("Email");
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
                
        $button = new Zend_Form_Element_Submit("btn");
        $button ->setLabel("Save");
        
        $this->addElements(array($name, $gender, $email, $password, $telephone, $mobile, $button));
    }


}

