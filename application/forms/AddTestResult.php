<?php

class Application_Form_AddTestResult extends Zend_Form
{
    private $type;

    public function __construct($param = false,$options = null) {
        parent::__construct($options);
        $this->type = $param;
        $this->init();
    }

    public function init()
    {
        $this->setName('Add Test Result');
        
        $this->setEnctype("multipart/form-data");
        $id = new Zend_Form_Element_Hidden('resultId');
        
        if($this->type)
        {            
            $requestId = new Zend_Form_Element_Select('requestId');
            $requestId->setLabel('Visit Request : ')
            ->setRequired(true)->addValidator('NotEmpty', true)
            ->setRegisterInArrayValidator(false);
        }
        else
        {
            $requestId = new Zend_Form_Element_Hidden('requestId');
        }
        
        $vitalId = new Zend_Form_Element_Select('testId');
        $vitalId->setLabel('Test Name : ')
        ->setRequired(true)->addValidator('NotEmpty', true)
        ->setRegisterInArrayValidator(false);
        
        $testImages = new Zend_Form_Element_File("file");
        $testImages->setMultiFile(3);
        $testImages->setLabel("Choose Tests Images : (Mark All Tests)");
        
        $add = new Zend_Form_Element_Button("add");
        $add->setAttrib("onclick", "addFile();");
        $add->setAttrib("class", "lightButton");
        $add->setLabel("Add Another Image");

        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('Add');

        $this->addElements(array($id, $requestId, $vitalId, $testImages, $add, $submit));
    }
}


