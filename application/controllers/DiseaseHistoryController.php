<?php

class DiseaseHistoryController extends Zend_Controller_Action
{
    protected $diseaseHistoryModel = null;

    public function init()
    {
        /* Initialize action controller here */
        $this->diseaseHistoryModel = new Application_Model_DiseaseHistory();
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
        $diseaseForm = new Application_Form_DiseaseHistory($param);
        if($this->hasParam("patientId")){
            $this->view->diseaseForm = $diseaseForm;
            $patientId = $this->getParam("patientId");
            $this->view->patientId = $patientId;
            $this->render('add-p-dis-history');
        }       
        if($this->getRequest()->isPost())
        {
            $data = $this->getRequest()->getParams();
            
            if($diseaseForm->isValid($data))
            {
                $this->diseaseHistoryModel->addDiseaseHistory($data);
                $this->redirect("/patient/showprofile/patientId/".$patientId."");
            }
        }
        
        $this->view->diseaseForm = $diseaseForm;
    }

    public function editAction()
    {
        // action body
        
        $param = array("not_pat"=>"1");
        $diseaseHistoryForm = new Application_Form_DiseaseHistory($param);
        
        if($this->getRequest()->isPost())
        {
            $data = $this->getRequest()->getParams();
            if($diseaseHistoryForm->isValid($data))
            {
                $this->diseaseHistoryModel->editDiseaseHistory($data);
                
            }
        }
        else
        {
            $diseaseHistoryID = $this->_request->getParam("id");
            $diseaseHistory = $this->diseaseHistoryModel->getDiseaseHistoryByID($diseaseHistoryID);
            if(count($diseaseHistory) > 0)
            {
                $values = array(
                    "id" => $diseaseHistory["id"],
                    "disease" => $diseaseHistory["disease_id"],
                    "patient" => $diseaseHistory["patient_id"], 
                    "date" => $diseaseHistory["date"]
                        );
                $diseaseHistoryForm->populate($values);
            }
        }
        
        $this->view->diseaseHistoryForm = $diseaseHistoryForm;
    }

    public function deleteAction()
    {
        // action body
        $diseaseHistory = $this->getRequest()->getParam("id");
        if($diseaseHistory)
        {
            $this->diseaseHistoryModel->deleteDiseaseHistory($diseaseHistory);
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
            $diseaseHistory = $this->diseaseHistoryModel->getDiseaseHistoryByPatientName($data["patient"]);
            $this->view->diseaseHistory = $diseaseHistory;
        }
    }


}









