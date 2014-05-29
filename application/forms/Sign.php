<?php

class Application_Form_Sign extends Zend_Form
{

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
        
                /* Form Elements & Other Definitions Here ... */
        $email = new Zend_Form_Element_Text("email");
        $email ->setRequired();
        $email ->addValidator(new Zend_Validate_EmailAddress);
        $email ->setLabel("Email Address");
        
         //$this->addElement("email","email",Zend_Validate_EmailAddress::INVALID_FORMAT,array('label'=>'type email:','required'=>TRUE , ));
         
         
        
        $this->addElements(array($email));
        $this->addElement("password","password",array('label'=>'type password:','required'=>TRUE ,));
        $this->addElement("submit","submit",array('label'=>'login'));

        
        
    }


}

