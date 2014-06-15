<?php

class VisitController extends Zend_Controller_Action
{

    protected $visitModel = null;

    protected $userInfo = null;

    protected $countItems = 10;

    public function init()
    {
        /* Initialize action controller here */
        $this->visitModel = new Application_Model_Visit();
        $this->auth = Zend_Auth::getInstance();
        $this->userInfo = $this->auth->getIdentity();
    }

    public function indexAction()
    {
       
    }

    public function addAction()
    {
        $param = array("action" => "add");
        if ($this->_request->getParam("id"))
            $param = array("action" => "add", "patientGP" => "add-gp");

        $VisitForm = new Application_Form_Visit($param);
        $request = $this->getRequest();

        if ($this->getRequest()->isPost()) {

            if ($VisitForm->isValid($request->getParams())) {
                $date = $this->_request->getParam("date");
                $description = $this->_request->getParam("description");
                $patient = $this->_request->getParam("patient_id");
                //return error
                $physican = $this->_request->getParam("physican_id");
                $type = $this->_request->getParam("type");

                $group_id = $this->_request->getParam("group_id");
                //$notes = "hh";//$this->_request->getParam("notes"); //has no input field
                //BySession =======>  $Gp = $this->_request->getParam("Gp");
                $depandency = $this->_request->getParam("depandency");
                //$user_modified_id = $this->userInfo['userId'];
                $visit_model = new Application_Model_Visit();

                $id = $visit_model->addVisit($date, $description, NULL, $group_id, $patient, $type, $notes, $this->userInfo['userId'], $depandency,$this->userInfo['userId']);
                if ($depandency) {
                    
                    $this->view->visitId = $id;
                    $this->render("add-dependency");
                }else{
                    $this->redirect("visit/view/id/" . $id);
                }
            }
        } else {
            /*$patientID = $this->_request->getParam("id");
            $values = array(
                "patient_id" => $patientID
            );
            $VisitForm->populate($values);*/
        }
        $this->view->visitform = $VisitForm;
    }

    public function listAction()
    {
        
        $fullBaseUrl = $this->view->serverUrl() . $this->view->baseUrl();
        $visits = $this->visitModel->getAllVisit();
        
        foreach ($visits as $visit) {
            $array_feed_item['id'] = $visit['id'];
            $array_feed_item['title'] = $visit["patname"];
            $array_feed_item['start'] = $visit["created_date"]; //Y-m-d H:i:s format
            //$array_feed_item['end'] = $array_event['end']; //Y-m-d H:i:s format
            $array_feed_item['allDay'] = 0;
            $array_feed_item['color'] = '#22475E'; 
            //You can also a CSS class: 
            $array_feed_item['className'] = 'pl_act_rood';

            $array_feed_item['url'] = $fullBaseUrl."/visit/view?id=".$visit['id'];

            //Add this event to the full list of events:
            $array_feed_items[] = $array_feed_item;
        }

        $allVisitsJson = json_encode($array_feed_items);

        $this->view->allVisitsJson = $allVisitsJson;
        $this->view->allVisits = $visits;
        $this->_helper->viewRenderer('calender');
    }

    public function editAction()
    {
        $action = array("action" => "edit");
        $VisitForm = new Application_Form_Visit($action);

        $id = $this->_request->getParam("id");
        $visit_model = new Application_Model_Visit();
        $data = $visit_model->selectVisitById($id);
        $VisitForm->populate($data);
        
        if ($this->getRequest()->isPost()) {
            $formData = $this->_request->getPost();
            if ($VisitForm->isValid($formData))
            {
                
                
                 $visit_model = new Application_Model_Visit();
                 unset($formData['submit']);
                 $formData['user_modified_id'] = $this->userInfo['userId'];
                 $visit_model->editVisit($formData,$id);
                       $this->redirect("visit/view/id/".$id);     
       
               
            }
        }
        $this->view->visitform = $VisitForm;
    }

    public function deleteAction()
    {
        $visit_model = new Application_Model_Visit();
        $id = $this->_request->getParam("id");
        $visit_model->deleteVisit($id);
        
    }

    public function viewAction()
    {
        $data = $this->_request->getParams();
        //if($data["id"])  //gives error change it to if hasParam
        if($this->hasParam("id"))
        {
            $data["id"] = $this->getParam("id");
            $this->view->visit= $this->visitModel->selectVisitById($data["id"]);
            $this->view->userType = $this->userInfo['userType'];
        }
        else if($this->hasParam("patientid"))
            //if($data["patientid"])
            {
                $data["patientid"] = $this->getParam("patientid");
                $allVisits = $this->visitModel->selectVisitByPatientID($data["patientid"]);
                
                $paginator = Zend_Paginator::factory($allVisits);
                $paginator->setItemCountPerPage($this->countItems);
                $pageNumber = $this->getRequest()->getParam("page");
                $paginator->setCurrentPageNumber($pageNumber);

                $this->view->paginator = $paginator;
                $this->view->visits = $allVisits;
                $this->view->patID = $data["patientid"];
                $this->view->userType = $this->userInfo['userType'];
            }

     //   $this->redirect('visit/list/');
    }

    public function liveAction()
    {
        if($this->hasParam("patid") && $this->hasParam("visid")){
            $patientModel = new Application_Model_Patient();
            $patientId = $this->getParam("patid");
            $this->view->patientId = $patientId;
            $fullData = $patientModel->getPatientFullDataById($patientId);
            $this->view->fullData = $fullData;
            $this->view->visitid = $this->getParam("visid");
            $p = new Application_Model_PhysicianNotification();
            $p->addVisitID($this->getParam("visid"));
        }
    }

    public function prescriptionAction()
    {
        // action body
        if($this->getRequest()->getParam("visitid"))
        {
            $visitID = $this->getRequest()->getParam("visitid");
            $medicationHistoryModel = new Application_Model_MedicationHistory();
            $surgeryHistoryModel = new Application_Model_SugeryHistory();
            $diseaseHistoryModel = new Application_Model_DiseaseHistory();
            $radiationResultModel = new Application_Model_RadiationResult();
            $vitalResultModel = new Application_Model_VitalResult();
            $testResultModel = new Application_Model_TestResult();
            
            $visitData = $this->visitModel->selectVisitById($visitID);
            $this->view->visitData = $visitData;
            
            $medicationData = $medicationHistoryModel->getMedicationByVisitID($visitID);
            $this->view->medicationData = $medicationData;
            
            $surgeryData = $surgeryHistoryModel->getSugeryHistoryByVisitID($visitID);
            $this->view->surgeryData = $surgeryData;
            
            $diseaseData = $diseaseHistoryModel->getDiseaseHistoryByVisitID($visitID);
            $this->view->diseaseData = $diseaseData;
            
            $radiationData = $radiationResultModel->getRadiationResultByVisitIDAndType($visitID);
            $this->view->radiationData = $radiationData;
            
            $vitalData = $vitalResultModel->getVitalResultByVisitIDAndType($visitID);
            $this->view->vitalData = $vitalData;
            
            $testData = $testResultModel->getTestResultByVisitIDAndType($visitID);
            $this->view->testData = $testData;
        }
    }

    public function searchAction()
    {
        if($this->getRequest()->isPost()){
            if($this->hasParam("date")){
                $date = $this->getParam("date");
                $visits = $this->visitModel->selectVisitsByDate($date);
                if($visits){
                    $this->view->visits = $visits;
                }else{
                    $this->view->dataNotFound = 1;
                }

            }
        }
    }

    public function dependancyAction()
    {
        $data = $this->_request->getParams();
        
        if($data["dep"] && $data["reqid"])
        {
            $radiationResultModel = new Application_Model_RadiationResult();
            $vitalResultModel = new Application_Model_VitalResult();
            $testResultModel = new Application_Model_TestResult();
            
            $testImagesModel = new Application_Model_TestImages();
            $radiationImagesModel = new Application_Model_RadiationsImages();
            
            $testImages = $testImagesModel->getTestImgByVisitID($data["reqid"]);
            $radiationImages = $radiationImagesModel->getRadImgByVisitID($data["reqid"]);
            
            $this->view->testImages = $testImages;
            $this->view->radiationImages = $radiationImages;
            
           // $this->_helper->viewRenderer('depandancy');
            
            $this->view->tests = $testResultModel->viewAllTestResult($data["reqid"]);
            $this->view->radiations = $radiationResultModel->viewAllRadiationResult($data["reqid"]);
            $this->view->vitals = $vitalResultModel->viewAllVitalResult($data["reqid"]);
            $this->view->reqid = $data["reqid"];
        }
        else
            $this->_forward("search");
    }


}


