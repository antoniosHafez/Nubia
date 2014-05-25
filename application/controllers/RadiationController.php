<?php

class RadiationController extends Zend_Controller_Action
{

    protected $radiationModel = null;

    protected $base = null;

    public function init()
    {
        $this->radiationModel = new Application_Model_Radiation();
        $base = Zend_Controller_Front::getInstance()->getBaseUrl();
        
        echo "<h4><a href='".$base."/Radiation'>Radiation Page </a></h4>";
    }

    public function indexAction()
    {
        $radiationsStatistics = $this->radiationModel->getRadiationsStatistics();
        $this->view->radiationsStatistics = $radiationsStatistics;  
    }

    public function addAction()
    {
        $addRadiationForm = new Application_Form_AddRadiation();
        
        if ($this->_request->isPost()) {
            $formData = $this->_request->getPost();
            if ($addRadiationForm->isValid($formData)) {
                if($this->radiationModel->checkDuplication($formData['name'])) {
                    $addRadiationForm->populate($formData);
                    $addRadiationForm->markAsError();
                    $addRadiationForm->getElement("name")->addError("Name is used Before");
                }
                else {
                    $this->radiationModel->addRadiation($formData);
                    $this->_forward("list");
                }
            } else {
                $addRadiationForm->populate($formData);
            }
        }
        
        $this->view->form = $addRadiationForm;
    }

    public function deleteAction()
    {
        $id = $this->_request->getParam("id");
        
        if ($id) {
            $this->radiationModel->deleteRadiation($id);        
            $this->_forward("list");
        }
        else {
            $this->render("search");
        }
    }

    public function listAction()
    {
        $this->view->radiations = $this->radiationModel->getAllRadiations();  
    }

    public function editAction()
    {
        $addRadiationForm = new Application_Form_AddRadiation();
        $id = $this->_request->getParam("id");
        
        if ($this->_request->isPost()) {
            $formData = $this->_request->getPost();
            
            if ($addRadiationForm->isValid($formData)) {
                if($this->radiationModel->checkDuplication($formData['name'])) {
                    $addRadiationForm->populate($formData);
                    $addRadiationForm->markAsError();
                    $addRadiationForm->getElement("name")->addError("Name is used Before");
                }
                else {
                    $editData = array('name'=>$formData['name']);
                    $this->radiationModel->editRadiation($id,$editData);
                    $this->_forward("list");
                }
            } else {
                $addRadiationForm->populate($formData);
            }
        }
        else {    
            if ($id) {
                $radiation = $this->radiationModel->viewRadiation($id);
                
                if ($radiation) {
                    $formData = array('radiationId'=>$radiation[0]['id'], 'name'=> $radiation[0]['name'], 'submit'=> "Edit");
                    $addRadiationForm->setName("Edit Radiation :");
                    $addRadiationForm->populate($formData); 
                }
                else {
                    $this->render("search");
                }
            }
            else {
                $this->render("search");
            }
        }
        
        $this->view->form = $addRadiationForm;
    }

    public function viewAction()
    {
        $id = $this->_request->getParam("id");
        
        if ( $id ) {
            $radiation = $this->radiationModel->viewRadiation($id);
            $this->view->radiation = $radiation;
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
            $this->view->radiations = $this->radiationModel->searchByName("%".$key."%");
        }
    }


}













