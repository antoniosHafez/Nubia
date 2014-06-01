<?php

class MedicationHistoryController extends Zend_Controller_Action
{

    protected $medicationHistoryModel = null;

    public function init()
    {
        /* Initialize action controller here */
        $this->medicationHistoryModel = new Application_Model_MedicationHistory();
    }

    public function indexAction()
    {
        // action body          
    }

    public function addAction()
    {
        // action body
        $patientId;
        $param = array("not_pat"=>"1");
        $medicationForm = new Application_Form_MedicationHistory($param);
        if($this->hasParam("patientId")){
            $this->view->medicationForm = $medicationForm;
            $patientId = $this->getParam("patientId");
            $this->view->patientId = $patientId;
            $this->render('add-p-med-history');
        }
        if($this->getRequest()->isPost())
        {
            $data = $this->getRequest()->getParams();
            
            if($medicationForm->isValid($data))
            {
                $this->medicationHistoryModel->addMedicationHistory($data);
                $this->redirect("/patient/showprofile/patientId/".$patientId."");
            }
        }
        
        $this->view->medicationForm = $medicationForm;
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
}