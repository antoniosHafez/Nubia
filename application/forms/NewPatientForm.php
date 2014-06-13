<?php

class Application_Form_NewPatientForm extends Zend_Form
{
    private $patient_id;
    public function __construct($patientId, $options = null) {
        parent::__construct($options);
        $this->patient_id = $patientId;

        $this->init();
    }

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */

        $name = new Zend_Form_Element_Text("name");
        $name -> setRequired();
        $name -> addValidator(new Zend_Validate_Alpha($allowWhiteSpace = TRUE)); 
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
        
        
        $ssn = new Zend_Form_Element_Text("IDNumber");
        $ssn -> addValidator(new Zend_Validate_Digits());
        $ssn-> setRequired();
        $ssn ->setLabel("SSN")->setAttrib("class", "form-control");
        if($this->patient_id != 0)
            $ssnValidator = new Zend_Validate_Db_NoRecordExists(array('table'=>'patient','field'=>'IDNumber','exclude'=>array('field' => 'id','value' => $this->patient_id)));
        else
            $ssnValidator = new Zend_Validate_Db_NoRecordExists(array('table'=>'patient','field'=>'IDNumber')); 
        $ssn->addValidator($ssnValidator);
        
        //lsa msh kamel
        $region = new Zend_Form_Element_Text("region");
        $region -> addValidator(new Zend_Validate_Alpha($allowWhiteSpace = TRUE));
        $region -> setLabel("Region")->setAttrib("class", "form-control");
        
        $city = new Zend_Form_Element_Text("city");
        $city -> addValidator(new Zend_Validate_Alpha($allowWhiteSpace = TRUE));
        $city -> setLabel("City")->setAttrib("class", "form-control");
        
        $street = new Zend_Form_Element_Text("street");
        $street -> addValidator(new Zend_Validate_Alnum($allowWhiteSpace = TRUE));
        $street -> setLabel("Street")->setAttrib("class", "form-control");
        
        $postal = new Zend_Form_Element_Text("postal");
        $postal -> addValidator(new Zend_Validate_Digits());
        $postal -> setLabel("Postal Code")->setAttrib("class", "form-control");        
                
        $telephone = new Zend_Form_Element_Text("telephone");
        $telephone -> addValidator(new Zend_Validate_Digits());
        $telephone -> setLabel("Telephone")->setAttrib("class", "form-control");
        
        $mobile = new Zend_Form_Element_Text("mobile");
        $mobile -> addValidator(new Zend_Validate_Digits());
        $mobile -> setLabel("Mobile")->setAttrib("class", "form-control");
        
        $dob = new Zend_Form_Element_Text("DOB");
        $dob-> setRequired();
        $dob -> addValidator(new Zend_Validate_Date('yyyy-MM-dd'));
        $dob -> setLabel("Date of Birth")->setAttrib("class", "form-control");
        
        //should be enum
        $maritalStatus = new Zend_Form_Element_Select("martial_status");        
        $maritalStatus -> setLabel("Marital Status")->setRequired();   
        $maritalStatus->addMultiOptions(array("Choose"=>"Choose","Single"=>"Single","Engaged"=>"Engaged","Married"=>"Married"));
        $maritalStatus->setValue("Choose");
        $maritalStatus->setAttrib('disable',array("Choose"))->setAttrib("class", "form-control");
        
        $job = new Zend_Form_Element_Text("job");
        $job -> addValidator(new Zend_Validate_Alpha());
        $job -> setLabel("Job")->setAttrib("class", "form-control");
        
        $insuranceNum = new Zend_Form_Element_Text("ins_no");
        $insuranceNum -> addValidator(new Zend_Validate_Digits());
        $insuranceNum ->setLabel("Insurance Number")->setAttrib("class", "form-control");
        
        $button = new Zend_Form_Element_Submit("btn");
        $button ->setLabel("Save")->setAttrib("class", "btn btn-primary");
        
        $this->addElements(array($name, $gender, $ssn, $region, $city, $street, $postal, $telephone, $mobile, $dob, $maritalStatus, $job, $insuranceNum, $button));
        
        
        
    }


}

