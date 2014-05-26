<?php

class Application_Form_SearchUserEmail extends Zend_Form
{

    public function init()
    {
        $userEmail = new Zend_Form_Element_Text("email");
        $userEmail -> addValidator(new Zend_Validate_EmailAddress());
        $userEmail ->setLabel("User's Email");
        
        $button = new Zend_Form_Element_Submit("btn");
        $button ->setLabel("Search");
        
        $this->addElements(array($userEmail, $button));
    }


}

