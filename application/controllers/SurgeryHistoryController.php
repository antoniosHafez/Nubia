<?php

class SurgeryHistoryController extends Zend_Controller_Action
{
    
    protected $surgeryHistoryModel = null;

    public function init()
    {
        /* Initialize action controller here */
        $this->surgeryHistoryModel = new Application_Model_SugeryHistory();
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
        $surgeryForm = new Application_Form_SurgeryHistory($param);
        if($this->hasParam("patientId")){
            $this->view->surgeryForm = $surgeryForm;
            $patientId = $this->getParam("patientId");
            $this->view->patientId = $patientId;
            $this->render('add-p-surg-history');
        } 
        if($this->getRequest()->isPost())
        {
            $data = $this->getRequest()->getParams();            
            if($surgeryForm->isValid($data))
            {
                $this->surgeryHistoryModel->addSurgeryHistory($data);
                $this->redirect("/patient/showprofile/patientId/".$patientId."");
            }
        }
        
        $this->view->surgeryForm = $surgeryForm;
    }

    public function editAction()
    {
        // action body
        $param = array("not_pat"=>"1");
        $surgeryHistoryForm = new Application_Form_SurgeryHistory($param);
        
        if($this->getRequest()->isPost())
        {
            $data = $this->getRequest()->getParams();
            if($surgeryHistoryForm->isValid($data))
            {
                $this->surgeryHistoryModel->editSurgeryHistory($data);
                if($this->hasParam("patientId")){
                    $patientId = $this->getParam("patientId");
                    $this->redirect("/patient/showprofile/showPatientHistory/".$patientId."");
                }                
            }
        }
        else
        {
            $surgeryHistoryID = $this->_request->getParam("id");
            $surgeryHistory = $this->surgeryHistoryModel->getsurgeryHistoryByID($surgeryHistoryID);
            if(count($surgeryHistory) > 0)
            {
                $values = array(
                    "id" => $surgeryHistory["id"],
                    "surgery" => $surgeryHistory["surgery_id"],
                    "patient" => $surgeryHistory["patient_id"], 
                    "physician" => $surgeryHistory["physician_id"],
                    "date" => $surgeryHistory["date"]
                        );
                $surgeryHistoryForm->populate($values);
            }
        }
        
        $this->view->surgeryHistoryForm = $surgeryHistoryForm;
    }

    public function deleteAction()
    {
        // action body
        $surgeryHistory = $this->getRequest()->getParam("id");
        if($surgeryHistory)
        {
            $this->surgeryHistoryModel->deleteSurgeryHistory($surgeryHistory);
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
            $surgeryHistory = $this->surgeryHistoryModel->getSurgeryHistoryByPatientName($data["patient"]);
            $this->view->surgeryHistory = $surgeryHistory;
        }
    }


}









