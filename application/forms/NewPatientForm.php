<?php

class Application_Form_NewPatientForm extends Zend_Form
{

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */

        $name = new Zend_Form_Element_Text("name");
        $name -> setRequired();
        $name -> addValidator(new Zend_Validate_Alpha($allowWhiteSpace = TRUE)); 
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
        
        $ssn = new Zend_Form_Element_Text("IDNumber");
        $ssn -> addValidator(new Zend_Validate_Digits());
        $ssn ->setLabel("SSN");
        $ssnValidator = new Zend_Validate_Db_NoRecordExists(array('table'=>'patient','field'=>'IDNumber'));
        $ssn->addValidator($ssnValidator);
        
        //lsa msh kamel
        $region = new Zend_Form_Element_Text("region");
        $region -> addValidator(new Zend_Validate_Alpha($allowWhiteSpace = TRUE));
        $region -> setLabel("Region");
        
        $city = new Zend_Form_Element_Text("city");
        $city -> addValidator(new Zend_Validate_Alpha($allowWhiteSpace = TRUE));
        $city -> setLabel("City");
        
        $street = new Zend_Form_Element_Text("street");
        $street -> addValidator(new Zend_Validate_Alnum($allowWhiteSpace = TRUE));
        $street -> setLabel("Street");
        
        $postal = new Zend_Form_Element_Text("postal");
        $postal -> addValidator(new Zend_Validate_Digits());
        $postal -> setLabel("Postal Code");        
                
        $telephone = new Zend_Form_Element_Text("telephone");
        $telephone -> addValidator(new Zend_Validate_Digits());
        $telephone -> setLabel("Telephone");
        
        $mobile = new Zend_Form_Element_Text("mobile");
        $mobile -> addValidator(new Zend_Validate_Digits());
        $mobile -> setLabel("Mobile");
        
        $dob = new Zend_Form_Element_Text("DOB");
        $dob -> addValidator(new Zend_Validate_Date('yyyy-MM-dd'));
        $dob -> setLabel("Date of Birth");
        
        //should be enum
        $maritalStatus = new Zend_Form_Element_Text("martial_status");
        $maritalStatus -> addValidator(new Zend_Validate_Alpha());
        $maritalStatus -> setLabel("Marital Status");
        
        $job = new Zend_Form_Element_Text("job");
        $job -> addValidator(new Zend_Validate_Alpha());
        $job -> setLabel("Job");
        
        $insuranceNum = new Zend_Form_Element_Text("ins_no");
        $insuranceNum -> addValidator(new Zend_Validate_Digits());
        $insuranceNum ->setLabel("Insurance Number");
        
        $button = new Zend_Form_Element_Submit("btn");
        $button ->setLabel("Save");
        
        $this->addElements(array($name, $gender, $ssn, $region, $city, $street, $postal, $telephone, $mobile, $dob, $maritalStatus, $job, $insuranceNum, $button));
        
        
        
    }


}

