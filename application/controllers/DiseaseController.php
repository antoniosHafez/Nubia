<?php

class DiseaseController extends Zend_Controller_Action
{
    protected $diseaseModel = null;

    public function init()
    {
        /* Initialize action controller here */
        $this->diseaseModel = new Application_Model_Disease();
    }

    public function indexAction()
    {
        // action body
    }

    public function addAction()
    {
        // action body
        $addDiseaseForm = new Application_Form_Disease();
        
        if($this->getRequest()->isPost())
        {
            $data = $this->getRequest()->getParams();
            if($addDiseaseForm->isValid($data))
            {
                $this->diseaseModel->addDisease($data["diseaseName"]);
            }
        }
        
        $this->view->addDiseaseForm = $addDiseaseForm;
    }

    public function editAction()
    {
        // action body
        $editDiseaseForm = new Application_Form_Disease();
        
        if($this->getRequest()->isPost())
        {
            $data = $this->getRequest()->getParams();
            if($editDiseaseForm->isValid($data))
            {
                $this->diseaseModel->editDisease($data["diseaseName"], $data["diseaseID"]);
                $this->redirect("disease/search");
            }
        }
        else
        {
            $diseaseID = $this->_request->getParam("id");
            $diseases = $this->diseaseModel->getDiseaseByID($diseaseID);
            if(count($diseases) > 0)
            {
                $values = array("diseaseName" => $diseases["name"],
                    "diseaseID" => $diseases["id"]);
                $editDiseaseForm->populate($values);
            }
        }
        
        $this->view->editDiseaseForm = $editDiseaseForm;
    }

    public function deleteAction()
    {
        // action body
        $diseaseID = $this->getRequest()->getParam("id");
        if($diseaseID)
        {
            $this->diseaseModel->deleteDisease($diseaseID);
            $this->render("search");
        }
    }

    public function searchAction()
    {
        // action body
        if($this->getRequest()->isPost())
        {
            $data = $this->getRequest()->getParams();
            $disease = $this->diseaseModel->searchDisease($data["diseaseName"]);
            $this->view->diseases = $disease;
        }
    }

    public function listAction()
    {
        // action body
    }


}











