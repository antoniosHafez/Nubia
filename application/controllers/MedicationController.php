<?php

class MedicationController extends Zend_Controller_Action
{

    protected $medicationModel = null;

    public function init()
    {
        /* Initialize action controller here */
        $this->medicationModel = new Application_Model_Medication();
    }

    public function indexAction()
    {
        // action body
    }

    public function addAction()
    {
        // action body
        $addMedicationForm = new Application_Form_Medication();
        
        if($this->getRequest()->isPost())
        {
            $data = $this->getRequest()->getParams();
            if($addMedicationForm->isValid($data))
            {
                $this->medicationModel->addMedication($data["medicationName"]);
            }
        }
        
        $this->view->addMedicationForm = $addMedicationForm;
    }

    public function editAction()
    {
        $editMedicationForm = new Application_Form_Medication();
        
        if($this->getRequest()->isPost())
        {
            $data = $this->getRequest()->getParams();
            if($editMedicationForm->isValid($data))
            {
                $this->medicationModel->editMedication($data["medicationName"], $data["medicationID"]);
                $this->redirect("medication/search");
            }
        }
        else
        {
            $medicationID = $this->_request->getParam("id");
            $medications = $this->medicationModel->getMedicationByID($medicationID);
            if(count($medications) > 0)
            {
                $values = array("medicationName" => $medications["name"],
                    "medicationID" => $medications["id"]);
                $editMedicationForm->populate($values);
            }
        }
        
        $this->view->editMedicationForm = $editMedicationForm;
    }

    public function deleteAction()
    {
        $medicationID = $this->getRequest()->getParam("id");
        if($medicationID)
        {
            $this->medicationModel->deleteMedication($medicationID);
            $this->render("search");
        }
    }

    public function updateAction()
    {
        
    }

    public function listAction()
    {
        // action body
        $allMedication = $this->medicationModel->getAllMedication();
        
        $this->view->medications = $allMedication;
    }

    public function searchAction()
    {
        
        if($this->getRequest()->isPost())
        {
            $data = $this->getRequest()->getParams();
            $medications = $this->medicationModel->searchMedication($data["medicationName"]);
            $this->view->medications = $medications;
        }
    }

}













