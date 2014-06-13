<?php

class MedicationController extends Zend_Controller_Action
{

    protected $medicationModel = null;
    protected $countItems = 10;
    protected $key = NULL;

    public function init()
    {
        /* Initialize action controller here */
        $this->medicationModel = new Application_Model_Medication();
    }

    public function indexAction()
    {
        $medicationStatistics = $this->medicationModel->getMedicationStatistics();
        $this->view->medicationStatistics = $medicationStatistics;
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
                $this->_forward("list");
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
                $this->_forward("list");
            }
        }
        else
        {   
            if($this->_request->getParam("id"))
            {
                $ID = $this->_request->getParam("id");
                $medications = $this->medicationModel->getMedicationByID($ID);
                if(count($medications) > 0)
                {
                    $values = array("medicationName" => $medications["name"],
                    "medicationID" => $medications["id"]);
                    $editMedicationForm->populate($values);
                }
            }
            else
                $this->render("search");
            
        }
        
        $this->view->editMedicationForm = $editMedicationForm;
    }

    public function deleteAction()
    {
        $id = $this->_request->getParam("id");
        
        if ($id) {
            $this->medicationModel->deleteMedication($id);
            $this->_forward("list");
        }
        else {
            $this->render("search");
        }
    }

    public function updateAction()
    {
        
    }

    public function listAction()
    {
        
        $allMedication = $this->medicationModel->getAllMedication();
        
        $paginator = Zend_Paginator::factory($allMedication);
        $paginator->setItemCountPerPage($this->countItems);
        $pageNumber = $this->getRequest()->getParam("page");
        $paginator->setCurrentPageNumber($pageNumber);

        $this->view->paginator = $paginator;
        $this->view->medications = $allMedication;
    }

    public function searchAction()
    {
        
        if($this->getRequest()->isGet() && $this->_request->getParam("key"))
        {
                $this->key = $this->_request->getParam("key");
                $medications = $this->medicationModel->searchMedication($this->key);
                
                $paginator = Zend_Paginator::factory($medications);
                $paginator->setItemCountPerPage($this->countItems);
                $pageNumber = $this->getRequest()->getParam("page");
                $paginator->setCurrentPageNumber($pageNumber);

                $this->view->paginator = $paginator;
                $this->view->medications = $medications;
                $this->view->key = $this->key;
        }
    }

}













