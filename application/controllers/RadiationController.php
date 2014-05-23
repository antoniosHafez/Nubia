<?php

class RadiationController extends Zend_Controller_Action
{
    protected $radiationModel;
    
    public function init()
    {
        $this->radiationModel = new Application_Model_Radiation();
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
                    echo "Success";
                    exit;
                }
            } else {
                $addRadiationForm->populate($formData);
            }
        }
        
        $this->view->form = $addRadiationForm;
    }

    public function deleteAction()
    {
        // action body
    }

    public function listAction()
    {
        $radiations = $this->radiationModel->getAllRadiations();
        foreach ($radiations as $radiation) {
            echo $radiation['name'];
        }
    }

    public function editAction()
    {
        // action body
    }

    public function viewAction()
    {
        // action body
    }


}











