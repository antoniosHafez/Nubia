<?php

class Application_Form_Sign extends Zend_Form
{
    public function init()
    {
        $email = new Zend_Form_Element_Text("email");
        $email ->setRequired()->addValidator(new Zend_Validate_EmailAddress)
        ->setLabel("Email Address")
        ->setAttrib("class", "form-control");
        
        $password = new Zend_Form_Element_Password("password");
        $password->setRequired()->addValidator('NotEmpty')
        ->setLabel("Password ")
        ->setAttrib("class", "form-control");
        
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('Login');
        $submit->setAttrib("class", "btn btn-primary");
         
        $this->addElements(array($email, $password, $submit));
    }
}

