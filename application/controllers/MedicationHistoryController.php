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
        $med = new Application_Model_MedicationHistory();
        $medData = $med -> getMedicationByPatientID(16);
        $dis = new Application_Model_DiseaseHistory();
        $disData = $dis ->getDiseaseHistoryByPatientID(16); 
        $sur = new Application_Model_SurgeryHistory();
        //$sur ->
        $fullData = array_merge((array)$dis,(array)$med);
        print_r($fullData);
    }

    public function addAction()
    {
        // action body
        $medicationForm = new Application_Form_MedicationHistory();
        
        if($this->getRequest()->isPost())
        {
            $data = $this->getRequest()->getParams();
            
            if($medicationForm->isValid($data))
            {
                $this->medicationHistoryModel->addMedicationHistory($data);
            }
        }
        
        $this->view->medicationForm = $medicationForm;
    }

    public function editAction()
    {
        // action body
        $medicationHistoryForm = new Application_Form_MedicationHistory();
        
        if($this->getRequest()->isPost())
        {
            $data = $this->getRequest()->getParams();
            if($medicationHistoryForm->isValid($data))
            {
                $this->medicationHistoryModel->editMedicationHistory($data);
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
            $this->render("search");
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









