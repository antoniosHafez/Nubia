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

        if($this->hasParam("patientId")){
            $param = array("not_pat"=>"1");
            $medicationForm = new Application_Form_MedicationHistory($param);
            $this->view->medicationForm = $medicationForm;
            $patientId = $this->getParam("patientId");
            $this->view->patientId = $patientId;
            $this->render('add-p-med-history');
        }else{
            $param = array("not_pat"=>"0");
            $medicationForm = new Application_Form_MedicationHistory($param); 
            $this->view->medicationForm = $medicationForm;
        }
        
        if($this->getRequest()->isPost())
        {
            //$data = $this->getRequest()->getParams();
            
            if($medicationForm->isValid($this->getRequest()->getParams()))
            {
                $data = array(
                    'medication_id' => $this->getParam("medication"),
                    'patient_id' => $this->getParam("patient"),
                    'physician_id' => $this->getParam("physician"),
                    'visit_request_id' => $this->getParam("visit")                  
                );
                $this->medicationHistoryModel->addMedicationHistory($data);               
                if($this->hasParam("patientId")){
                    $this->redirect("/patient/showprofile/patientId/".$patientId."");
                }
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











