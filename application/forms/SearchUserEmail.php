<?php

class Application_Form_SearchUserEmail extends Zend_Form
{

    public function init()
    {
        $rolesModel = new Application_Model_Role();
        $rolesTypes = $rolesModel ->getRolesNames();
        
        $userEmail = new Zend_Form_Element_Text("email");
        $userEmail -> addValidator(new Zend_Validate_EmailAddress());
        $userEmail ->setLabel("User's Email")->setAttrib("class", "form-control");;
        
        $role = new Zend_Form_Element_Radio("role");
        $role ->setLabel("Filter By Account Type");
        
        $role ->addMultiOption("all", "All");
        foreach ($rolesTypes as $type) {
            if($type['name'] == "physician" || $type['name'] == "clinician"){
                $role -> addMultiOption($type['id'], $type['name']);
            }
        } 
        $role ->setValue("all");
        
        $button = new Zend_Form_Element_Submit("btn");
        $button ->setLabel("Search")->setAttrib("class", "btn btn-primary");
        
        $this->addElements(array($userEmail, $role, $button));
    }


}

