<?php

class SurgeryController extends Zend_Controller_Action
{
     protected $surgeryModel = null;

    public function init()
    {
        /* Initialize action controller here */
        $this->surgeryModel = new Application_Model_Surgery();
    }

    public function indexAction()
    {
        // action body
    }

    public function addAction()
    {
        // action body
        $addSurgeryForm = new Application_Form_Surgery();
        
        if($this->getRequest()->isPost())
        {
            $data = $this->getRequest()->getParams();
            if($addSurgeryForm->isValid($data))
            {
                $this->surgeryModel->addSurgery($data["operation"]);
            }
        }
        
        $this->view->addSurgeryForm = $addSurgeryForm;
    }

    public function editAction()
    {
        // action body
        $editSurgeryForm = new Application_Form_Surgery();
        
        if($this->getRequest()->isPost())
        {
            $data = $this->getRequest()->getParams();
            if($editSurgeryForm->isValid($data))
            {
                $this->surgeryModel->editSurgery($data["operation"], $data["surgeryID"]);
                $this->redirect("surgery/search");
            }
        }
        else
        {
            $ID = $this->_request->getParam("id");
            $surgries = $this->surgryModel->getSurgeryByID($surgryID);
            if(count($surgries) > 0)
            {
                $values = array("operation" => $surgries["name"],
                    "surgeryID" => $surgries["id"]);
                $editSurgeryForm->populate($values);
            }
        }
        
        $this->view->editSurgeryForm = $editSurgeryForm;
    }

    public function deleteAction()
    {
        // action body
        $surgeryID = $this->getRequest()->getParam("id");
        if($surgeryID)
        {
            $this->surgeryModel->deleteMedication($surgeryID);
            $this->render("search");
        }
    }

    public function searchAction()
    {
        // action body
        if($this->getRequest()->isPost())
        {
            $data = $this->getRequest()->getParams();
            $surgeries = $this->surgeryModel->searchSurgery($data["operation"]);
            $this->view->surgeries = $surgeries;
        }
    }

    public function listAction()
    {
        // action body
    }


}











