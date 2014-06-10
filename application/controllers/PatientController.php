<?php

class PatientController extends Zend_Controller_Action
{

    protected $patientModel = null;

    protected $auth = null;

    protected $userInfo = null;

    public function init()
    {
        /* Initialize action controller here */
        $this->patientModel = new Application_Model_Patient();
        $this->auth = Zend_Auth::getInstance();
        $this->userInfo = $this->auth->getIdentity();
    }

    public function indexAction()
    {
       /* $auth = Zend_Auth::getInstance();
        $id = $auth->getIdentity();
        echo $id['userId'];*/
        echo $this->userInfo['userId'];
        //lazem tt7at hena??
        /*$db=Zend_Registry::get('db');
        $sql = 'SELECT name FROM vitals';
        $result = $db->fetchAll($sql);
        $dojoData= new Zend_Dojo_Data('name',$result,'id');
        echo $dojoData->toJson();
        exit;*/

    }

    public function addAction()
    {
        $this->view->action = "/patient/add";
        $patientForm = new Application_Form_NewPatientForm(0);
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
                    'join_date' => date("Y-m-d"),
                    'status' => 'Active',
                    'type' => 'Patient'
                );
                $lastId = $personModel->addPerson($personData); //Person function
                
                if($lastId != 0){
                    $patientData = array(
                        'IDNumber' => $this->getParam("IDNumber"),
                        'DOB' => $this->getParam("DOB"),
                        'martial_status' => $this->getParam("martial_status"),
                        'job' => $this->getParam("job"),
                        'ins_no' => $this->getParam("ins_no"),
                        'gp_id' => $this->userInfo['userId'],
                        'user_modified_id' => $this->userInfo['userId'],//lsaaaaaaaaaaaaaaa
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
                   
                    $this->_redirect("patient/update-History?id=$lastId");
                }
            }
        }
    }

    public function searchAction()
    {
        $gp = 1;
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
                if($this->getRequest()->getParam("name"))
                {
                    $patients = $this->patientModel->searchPatientByName($this->getRequest()->getParam("name"));
                    $this->view->patients = $patients;
                }
            }
        }
        else
        {
            $this->view->form = new Application_Form_SearchPatientId();
            if ($this->getRequest()->isPost()){
                $patientIDN = $this->getParam("IDNumber");
                $patientId = $this->patientModel->searchPatientByIDN($patientIDN);;
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
        
        //if($this->getRequest()-> isGet()){
            $patientId = $this->getParam("patientId");
            $this->view->patientId = $patientId;
            $patientForm = new Application_Form_NewPatientForm($patientId);
            $this->view->form = $patientForm;
            $fullData = $patientModel->getPatientFullDataById($patientId);
            $patientForm->populate($fullData);
        //}   
        if ($this->getRequest()->isPost()){
            if ($patientForm->isValid($this->getRequest()->getParams())) {
                $patientId = $this->getParam("patientId");
                $personData = array(
                    'name' => $this->getParam("name"),
                    'sex' => $this->getParam("sex"),
                    'telephone' => $this->getParam("telephone"),
                    'mobile' => $this->getParam("mobile")                    
                );
                $patientData = array(
                    'IDNumber' => $this->getParam("IDNumber"),
                    'DOB' => $this->getParam("DOB"),
                    'martial_status' => $this->getParam("martial_status"),
                    'job' => $this->getParam("job"),
                    'ins_no' => $this->getParam("ins_no"),
                    'user_modified_id' => $this->userInfo['userId']
                     /* 'gp_id' => $this->userInfo['userId']*/
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
            $addressModel = new Application_Model_Address();
            $addressModel ->deleteAddress($patientId);
            $patientModel -> deletePatient($patientId);
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

    public function addHistoryAction()
    {
        // action body
        $patientId = $this->getRequest()->getParam("patientId");
        if($patientId){
            $this->view->patientId = $patientId;
            $this->render("history-info");
        }else{
            $this->_forward("search");
        }
    }

    public function updateHistoryAction()
    {
        $patientId = $this->getRequest()->getParam("id");
        
        if($patientId) {
            $this->view->patientId = $patientId;
        }
        else {
            $this->_forward("search");
        }
    }

    public function updateResultAction()
    {
        $patientId = $this->getRequest()->getParam("id");
        
        if($patientId) {
            $this->view->patientId = $patientId;
        }
        else {
            $this->_forward("search");
        }
    }


}



















