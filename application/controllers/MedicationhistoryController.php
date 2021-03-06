<?php

class MedicationHistoryController extends Zend_Controller_Action
{

    protected $medicationHistoryModel = null;
    protected $userInfo;

    public function init()
    {
        /* Initialize action controller here */
        $auth = Zend_Auth::getInstance();
        $this->userInfo = $auth->getIdentity();
        $this->medicationHistoryModel = new Application_Model_MedicationHistory();
    }

    public function indexAction()
    {
        $medicationHistoryCount = $this->medicationHistoryModel->getMedicationHistoryCount();
        $this->view->medicationHistoryCount = $medicationHistoryCount;
    }

    public function addAction()
    {
        // action body
        $patientId = 0;
        if($this->hasParam("patientId")){
            $param = array("not_pat"=>"1");
            $medicationForm = new Application_Form_MedicationHistory($param);
            $this->view->medicationForm = $medicationForm;
            $patientId = $this->getParam("patientId");
            $this->view->patientId = $patientId;
            $this->render('add-p-med-history');
            //echo $param["not_pat"];
        }else{
            $param = array("not_pat"=>"0");
            $medicationForm = new Application_Form_MedicationHistory($param); 
            $this->view->medicationForm = $medicationForm;
            //echo $param["not_pat"];
        }
        
        if($this->getRequest()->isPost())
        {
            //$data = $this->getRequest()->getParams();
            
            if($medicationForm->isValid($this->getRequest()->getParams()))
            {
                $data = array(
                    'medication' => $this->getParam("medication"),
                    'patient' => $this->getParam("patient"),
                    'physician' => $this->getParam("physician"),
                    'visit' => $this->getParam("visit"),
                    'user_modified_id' => $this->userInfo["userId"]
                );
                $this->medicationHistoryModel->addMedicationHistory($data);               
                if($patientId != 0){
                    $this->redirect("/patient/showprofile/patientId/".$patientId."");
                }
                $this->redirect("/Medicationhistory");
            }
        }
    }

    public function editAction()
    {
        $param = array("not_pat"=>"1");
        $medicationHistoryForm = new Application_Form_MedicationHistory($param);
        
        if($this->getRequest()->isPost())
        {
            $data = $this->getRequest()->getParams();
            if($medicationHistoryForm->isValid($data))
            {
                $this->medicationHistoryModel->editMedicationHistory($data);
                if($this->hasParam("patientId")){
                    $patientId = $this->getParam("patientId");
                    $this->redirect("/patient/showprofile/showPatientHistory/".$patientId."");
                }                                
            }
        }
        else
        {
            $medicationHistoryID = $this->_request->getParam("id");
            $medicationHistory = $this->medicationHistoryModel->getMedicationHistoryByID($medicationHistoryID);
            if(count($medicationHistory) > 0)
            {            
                $values = array(
                    "id" => $medicationHistory["id"],
                    "medication" => $medicationHistory["medication_id"],
                    "patient" => $medicationHistory["patient_id"], 
                    "physician" => $medicationHistory["physician_id"],
                    "visit" => $medicationHistory["visit_request_id"]
                        );
                
                $medicationHistoryForm->populate($values);
            }
        }
        
        $this->view->medicationHistoryForm = $medicationHistoryForm;
    }

    public function deleteAction()
    {
        // action body
        $medicationHistory = $this->getRequest()->getParam("id");
        if($medicationHistory)
        {
            $this->medicationHistoryModel->deleteMedicationHistory($medicationHistory);
            if($this->hasParam("patientId")){
                $patientId = $this->getParam("patientId");
                $this->redirect("/patient/showprofile/showPatientHistory/".$patientId."");
            }else{
                $this->render("search");
            }
        }
    }

    public function searchAction()
    {
        // action body
        if($this->getRequest()->isPost())
        {
            $data = $this->getRequest()->getParams();
            $medicationHistory = $this->medicationHistoryModel->getMedicationHistoryByPatientName($data["patient"]);
            $this->view->medicationHistory = $medicationHistory;
        }
    }

    public function addMedicationHistoryAction()
    {
        
    }
    
    public function fullhistoryAction()
    {
        
    }
}
