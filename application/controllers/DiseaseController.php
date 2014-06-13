<?php

class DiseaseController extends Zend_Controller_Action
{
    protected $diseaseModel = null;
    protected $countItems = 10;
    protected $key = NULL;

    public function init()
    {
        /* Initialize action controller here */
        $this->diseaseModel = new Application_Model_Disease();
    }

    public function indexAction()
    {
        $diseaseStatistics = $this->diseaseModel->getDiseaseStatistics();
        $this->view->diseaseStatistics = $diseaseStatistics;
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
                $this->_forward("list");
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
                $this->_forward("list");
            }
        }
        else
        {
            if($this->_request->getParam("id"))
            {
                $ID = $this->_request->getParam("id");
                $diseases = $this->diseaseModel->getDiseaseByID($ID);
                if(count($diseases) > 0)
                {
                    $values = array("diseaseName" => $diseases["name"],
                        "diseaseID" => $diseases["id"]);
                    $editDiseaseForm->populate($values);
                }
            }
            else
                $this->render("search");
        }
        
        $this->view->editDiseaseForm = $editDiseaseForm;
    }

    public function deleteAction()
    {
        $id = $this->_request->getParam("id");
        
        if ($id) {
            $this->diseaseModel->deleteDisease($id);
            $this->_forward("list");
        }
        else {
            $this->render("search");
        }
    }

    public function searchAction()
    {
        if($this->getRequest()->isGet() && $this->_request->getParam("key"))
        {
                $this->key = $this->_request->getParam("key");
                $diseases = $this->diseaseModel->searchDisease($this->key);
                
                $paginator = Zend_Paginator::factory($diseases);
                $paginator->setItemCountPerPage($this->countItems);
                $pageNumber = $this->getRequest()->getParam("page");
                $paginator->setCurrentPageNumber($pageNumber);

                $this->view->paginator = $paginator;
                $this->view->diseases = $diseases;
                $this->view->key = $this->key;
        }
    }

    public function listAction()
    {
        $allDiseases = $this->diseaseModel->getAllDisease();  
        
        $paginator = Zend_Paginator::factory($allDiseases);
        $paginator->setItemCountPerPage($this->countItems);
        $pageNumber = $this->getRequest()->getParam("page");
        $paginator->setCurrentPageNumber($pageNumber);

        $this->view->paginator = $paginator;
        $this->view->diseases = $allDiseases;
    }


}











