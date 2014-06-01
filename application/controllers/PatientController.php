<?php

class PatientController extends Zend_Controller_Action
{

    protected $patientModel = null;


    public function init()
    {
        /* Initialize action controller here */
        $this->patientModel = new Application_Model_Patient();
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
                    
                    $this->view->patientId = $lastId;
                   
                    $this->render("history-info");  
                }
            }
        }
    }

    public function searchAction()
    {
        $gp = 2;
        /*
        $authorization = Zend_Auth::getInstance();
        if(!$authorization->hasIdentity())
            //$this->redirect ; 
            echo "test";
        else
        
            $storge = $authorization->getStorage();
            if($storge->read()->type == "gp")
            {}
        }
        
        */
        if($gp == 1)
        {
            if($this->getRequest()->isPost())
            {
                $patients = $this->patientModel->searchPatientByName($this->getRequest()->getParam("name"));
                $this->view->patients = $patients;
            }
        }
        else
        {
            $this->view->form = new Application_Form_SearchPatientId();
            if ($this->getRequest()->isPost()){
                $patientIDN = $this->getParam("IDNumber");
                $patientModel = new Application_Model_Patient();
                $patientId = $patientModel ->searchPatientByIDN($patientIDN);
                //echo $patientIDN;
                //echo $patientId["id"];
                $this->redirect("/patient/showprofile/patientId/".$patientId["id"]."");
            }       
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
            if ($patientForm->isValid($this     ->getRequest()->getParams())) {
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
    

    public function showprofileAction()
    {
        $patientModel = new Application_Model_Patient();
        $personModel = new Application_Model_Person();
        $addressModel = new Application_Model_Address();
        $visitModel = new Application_Model_Visit();
        
        if($this->getRequest()-> isGet()){
            if($this->hasParam("patientId")){
                $patientId = $this->getParam("patientId");
                $this->view->patientId = $patientId;
                $fullData = $patientModel->getPatientFullDataById($patientId);
                $this->view->fullData = $fullData;
            }
            if($this->hasParam("showPatientHistory")){
                $patientId = $this->getParam("showPatientHistory");
                $medicationModel = new Application_Model_MedicationHistory();
                $this->view->medicationData = $medicationModel -> getMedicationByPatientID($patientId);
                $diseaseModel = new Application_Model_DiseaseHistory();
                $this->view->diseaseData = $diseaseModel ->getDiseaseHistoryByPatientID($patientId); 
                $surgeryModel = new Application_Model_SugeryHistory();
                $this->view->surgeryData = $surgeryModel->getSugeryHistoryByPatientID($patientId);
                $this->view->patientId = $patientId;
                $this->render("list-patient-medical-history");
            }
            if($this->hasParam("previousVisits")){
                $patientId = $this->getParam("previousVisits");
                $this->view->patientId = $patientId;
                $this->view->previousVisits = $visitModel->getPreviousVisits($patientId);
                $this->render("list-previous-visits");
            }
            if($this->hasParam("pendingVisits")){
                $patientId = $this->getParam("pendingVisits");
                $this->view->patientId = $patientId;
                $this->view->pendingVisits = $visitModel->getPendingVisits($patientId);
                $this->render("list-pending-visits");
            }
            if($this->hasParam("acceptedVisits")){
                $patientId = $this->getParam("acceptedVisits");
                $this->view->patientId = $patientId;
                $this->view->acceptedVisits = $visitModel->getAcceptedVisits($patientId);
                $this->render("list-accepted-visits");
            }            
        }
    }


}













