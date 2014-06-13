<?php

class SurgeryController extends Zend_Controller_Action
{
     protected $surgeryModel = null;
     protected $countItems = 10;
     protected $key;

    public function init()
    {
        /* Initialize action controller here */
        $this->surgeryModel = new Application_Model_Surgery();
    }

    public function indexAction()
    {
        $surgeryStatistics = $this->surgeryModel->getSurgeryStatistics();
        $this->view->surgeryStatistics = $surgeryStatistics;
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
                $this->_forward("list");
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
                $this->_forward("list");
            }
        }
        else
        {
            if($this->_request->getParam("id"))
            {
                $ID = $this->_request->getParam("id");
                $surgries = $this->surgeryModel->getSurgeryByID($ID);
                if(count($surgries) > 0)
                {
                    $values = array("operation" => $surgries["operation"],
                        "surgeryID" => $surgries["id"]);
                    $editSurgeryForm->populate($values);
                }
            }
            else
                $this->render("search");
        }
        
        $this->view->editSurgeryForm = $editSurgeryForm;
    }

    public function deleteAction()
    {
        $id = $this->_request->getParam("id");
        
        if ($id) {
            $this->surgeryModel->deleteSurgery($id);
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
                $surgeries = $this->surgeryModel->searchSurgery($this->key);
                
                $paginator = Zend_Paginator::factory($surgeries);
                $paginator->setItemCountPerPage($this->countItems);
                $pageNumber = $this->getRequest()->getParam("page");
                $paginator->setCurrentPageNumber($pageNumber);

                $this->view->paginator = $paginator;
                $this->view->surgeries = $surgeries;
                $this->view->key = $this->key;
        }
    }

    public function listAction()
    {
        $allSurgeries = $this->surgeryModel->getAllSurgery();  
        
        $paginator = Zend_Paginator::factory($allSurgeries);
        $paginator->setItemCountPerPage($this->countItems);
        $pageNumber = $this->getRequest()->getParam("page");
        $paginator->setCurrentPageNumber($pageNumber);

        $this->view->paginator = $paginator;
        $this->view->surgeries = $allSurgeries;
    }


}











