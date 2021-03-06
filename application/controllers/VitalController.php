<?php

class VitalController extends Zend_Controller_Action
{

    protected $vitalModel = null;

    protected $base = null;
    protected $countItems = 10;

    public function init()
    {
        $this->vitalModel = new Application_Model_Vital();
        $base = Zend_Controller_Front::getInstance()->getBaseUrl();
    }

    public function indexAction()
    {
        $vitalsStatistics = $this->vitalModel->getVitalsStatistics();
        $this->view->vitalsStatistics = $vitalsStatistics;  
    }

    public function addAction()
    {
        $addVitalForm = new Application_Form_AddType();
        
        if ($this->_request->isPost()) {
            $formData = $this->_request->getPost();
            if ($addVitalForm->isValid($formData)) {
                if($this->vitalModel->checkDuplication(0,$formData['typeName'])) {
                    $addVitalForm->populate($formData);
                    $addVitalForm->markAsError();
                    $addVitalForm->getElement("typeName")->addError("Name is used Before");
                }
                else {
                    $this->vitalModel->addVital($formData);
                    
                    $this->_forward("list");
                }
            } else {
                $addVitalForm->populate($formData);
            }
        }
        
        $this->view->form = $addVitalForm;
    }

    public function deleteAction()
    {
        $id = $this->_request->getParam("id");
        
        if ($id) {
            $this->vitalModel->deleteVital($id);        
            $this->_forward("list");
        }
        else {
            $this->render("search");
        }
    }

    public function listAction()
    {
        $allVitals = $this->vitalModel->getAllVitals();  
        
        $paginator = Zend_Paginator::factory($allVitals);
        $paginator->setItemCountPerPage($this->countItems);
        $pageNumber = $this->getRequest()->getParam("page");
        $paginator->setCurrentPageNumber($pageNumber);

        $this->view->paginator = $paginator;
        $this->view->vitals = $allVitals;
    }

    public function editAction()
    {
        $addVitalForm = new Application_Form_AddType();
        $id = $this->_request->getParam("id");
        
        if ($this->_request->isPost()) {
            $formData = $this->_request->getPost();
            
            if ($addVitalForm->isValid($formData)) {
                if($this->vitalModel->checkDuplication($id, $formData['typeName'])) {
                    $addVitalForm->populate($formData);
                    $addVitalForm->markAsError();
                    $addVitalForm->getElement("typeName")->addError("Name is used Before");
                }
                else {
                    $editData = array('name'=>$formData['typeName']);
                    $this->vitalModel->editVital($id,$editData);
                    $this->_forward("list");
                }
            } else {
                $addVitalForm->populate($formData);
            }
        }
        else {    
            if ($id) {
                $vital = $this->vitalModel->viewVital($id);
                
                if ($vital) {
                    $formData = array('typeId'=>$vital[0]['id'], 'typeName'=> $vital[0]['name'], 'submit'=> "Edit");
                    $addVitalForm->setName("Edit Vital :");
                    $addVitalForm->populate($formData); 
                }
                else {
                    $this->render("search");
                }
            }
            else {
                $this->render("search");
            }
        }
        
        $this->view->form = $addVitalForm;
    }

    public function viewAction()
    {
        $id = $this->_request->getParam("id");
        
        if ( $id ) {
            $vital = $this->vitalModel->viewVital($id);
            $this->view->vital = $vital;
        }
        else {
            $this->render("search");
        }    
    }

    public function searchAction()
    {
        $key = $this->_request->getParam("key");
        
        if ($key) {
            $this->view->key = $key;
            $this->view->vitals = $this->vitalModel->searchByName("%".$key."%");
        }
    }


}













