<?php

class PatientController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
       $db=Zend_Registry::get('db');
        $sql = 'SELECT name FROM vitals';
        $result = $db->fetchAll($sql);
        $dojoData= new Zend_Dojo_Data('name',$result,'id');
        echo $dojoData->toJson();
        exit;
    }

    public function addAction()
    {
        $this->view->form = $this->getForm();
        $this->render("history-info");
        $this->view->action = "/patient/add";
        $patientForm = new Application_Form_NewPatientForm();
        $this->view->form = $patientForm;
        if ($this->getRequest()->isPost()){
            if ($patientForm->isValid($this->getRequest()->getParams())) {
                $personModel = new Application_Model_Person();
                $patientModel = new Application_Model_Patient();
                $addressModel = new Application_Model_Address();
                $personData = array(
                    'name' => $this->getParam("name"),
                    'sex' => $this->getParam("sex"),
                    'telephone' => $this->getParam("telephone"),
                    'mobile' => $this->getParam("mobile"),
                    'join_date' => date("Y-m-d")
                );
                $lastId = $personModel->addPerson($personData); //Person function
                echo $lastId;
                if($lastId != 0){
                    $patientData = array(
                        'IDNumber' => $this->getParam("IDNumber"),
                        'DOB' => $this->getParam("DOB"),
                        'martial_status' => $this->getParam("martial_status"),
                        'job' => $this->getParam("job"),
                        'ins_no' => $this->getParam("ins_no"),
                        'gp_id' => 1,  //lsaaaaaaaaaaaaaaa
                        'id' => $lastId
                    );
                    $lastPId = $patientModel->addPatient($patientData);
                    if($lastPId != 0){
                        $addressData = array(
                            'region' =>  $this->getParam("region"),
                            'city' =>  $this->getParam("city"),
                            'street' =>  $this->getParam("street"),
                            'postal' =>  $this->getParam("postal"),
                            'id' => $lastId
                        );
                       $addressModel ->addAddress($addressData);
                    }
                    
                    $this->view->patientId = 2;
                   
                    $this->render("history-info");
                    //$this->redirect("/patient/list");
                }
            }
        }
    }
    
    public function searchAction()
    {
        $this->view->form = new Application_Form_SearchPatientId();
        if ($this->getRequest()->isPost()){
            $patientIDN = $this->getParam("IDNumber");
            $patientModel = new Application_Model_Patient();
            $patientId = $patientModel ->searchPatientByIDN($patientIDN);
            //echo $patientIDN;
            //echo $patientId["id"];
            $this->redirect("/patient/edit/patientId/".$patientId["id"]."");
        }       
    }

    public function editAction()
    {
        $this->view->action = "/patient/edit";
        $patientModel = new Application_Model_Patient();
        $personModel = new Application_Model_Person();
        $addressModel = new Application_Model_Address();
        $patientForm = new Application_Form_NewPatientForm();
        $this->view->form = $patientForm; 
        
        if($this->getRequest()-> isGet()){
            $patientId = $this->getParam("patientId");
            $this->view->patientId = $patientId;
            $patientData = $patientModel->getPatientById($patientId);
            $personData = $personModel->getPersonById($patientId);  //or by join, make it
            $addressData = $addressModel->getAddressByPId($patientId);
            $fullData = array_merge((array)$patientData, (array)$personData,(array)$addressData);
            $patientForm->populate($fullData);
        }   
        if ($this->getRequest()->isPost()){
            if ($patientForm->isValid($this->getRequest()->getParams())) {
                $patientId = $this->getParam("patientId");
                $personData = array(
                    'name' => $this->getParam("name"),
                    'sex' => $this->getParam("sex"),
                    'telephone' => $this->getParam("telephone"),
                    'mobile' => $this->getParam("mobile"),
                );
                $patientData = array(
                    'IDNumber' => $this->getParam("IDNumber"),
                    'DOB' => $this->getParam("DOB"),
                    'martial_status' => $this->getParam("martial_status"),
                    'job' => $this->getParam("job"),
                    'ins_no' => $this->getParam("ins_no"),
                    'gp_id' => 1
                );
                $addressData = array(
                    'region' =>  $this->getParam("region"),
                    'city' =>  $this->getParam("city"),
                    'street' =>  $this->getParam("street"),
                    'postal' =>  $this->getParam("postal")
                );
                $personModel->editPerson($personData, $patientId);
                $patientModel->editPatient($patientData, $patientId);
                $addressModel->editAddress($addressData, $patientId);
                $this->redirect("/patient/list");
            }
        }       
        $this->render('add');
    }

    public function listAction()
    {
        $patientModel = new Application_Model_Patient();
        $this->view->patients = $patientModel->listPatients();
    }

    public function deleteAction()
    {
        if ($this->getRequest()->isGet()){
            $patientId = $this->getRequest()->getParam("patientId");
            $patientModel = new Application_Model_Patient();
            $personModel = new Application_Model_Person();
            $addressModel = new Application_Model_Address();
            $addressModel ->deleteAddress($patientId);
            $patientModel -> deletePatient($patientId);
            $personModel -> deletePerson($patientId);
            $this->redirect("/patient/list");
        }
    }
    
    public function getForm()
{
    if (null === $this->_form) {
        $this->_form = new Zend_Form();
        $this->_form->setMethod('get')
                ->setAction($this->getRequest()->getBaseUrl() . '/test/process')
                ->addElements(array(
                'test' => array(
                    'type' => 'text', 
                    'options' => array(
                            'filters'        => array('StringTrim'),
                            'dojoType'       => array('dijit.form.ComboBox'),
                            'store'          => 'testStore',
                            'autoComplete'   => 'false',
                            'hasDownArrow'   => 'true',
                            'label' => 'Your input:',
                    )),
                'go' => array('type' => 'submit',
                'options' => array('label' => 'Go!'))
                ));
        }
    return $this->_form;
}



}











