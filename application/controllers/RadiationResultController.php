<?php

class RadiationResultController extends Zend_Controller_Action
{

    protected $base = null;

    protected $radiationResultModel = null;

    public function init()
    {
        $this->radiationResultModel = new Application_Model_RadiationResult();
        $base = Zend_Controller_Front::getInstance()->getBaseUrl();
        
        echo "<h4><a href='".$base."/Radiation-Result'>Radiation Result Page </a></h4>";
    }

    public function indexAction()
    {
        $radiationResultsStatistics = $this->radiationResultModel->getradiationResultsStatistics();
        $this->view->radiationResultsStatistics = $radiationResultsStatistics;  
    }

    public function addAction()
    {
        $addRadiationResultForm = new Application_Form_AddRadiationResult();
        
        if ($this->_request->isPost()) {
            $formData = $this->_request->getPost();
            if ($addRadiationResultForm->isValid($formData)) {
                if($this->radiationResultModel->checkDuplication($formData['requestId'],$formData['radiationId'])) {
                    $addRadiationResultForm->populate($formData);
                    $addRadiationResultForm->getElement("radiationId")->addError("Radiation Result is used Before");
                }
                else {
                    if($formData['radiationId'] == 0 || $formData['requestId'] == 0) {
                        $addRadiationResultForm->populate($formData);
                        $addRadiationResultForm->getElement("radiationId")->addError("Please Select Radiation & Request");
                    }
                    else {
                        $this->radiationResultModel->addRadiationResult($formData);
                        $this->_forward("list");
                    }                   
                }
            } else {
                $addRadiationResultForm->populate($formData);
            }
        }
        
        $radiationModel = new Application_Model_Radiation();
        //$requestModel = new Application_Model_Request_Visit();
        
        $this->initForm($addRadiationResultForm);
        
        $this->view->form = $addRadiationResultForm;
    }

    public function editAction()
    {
        $addRadiationResultForm = new Application_Form_AddRadiationResult();
        $radiationId = $this->_request->getParam("radId");
        $requestId = $this->_request->getParam("reqId");
        
        if ($this->_request->isPost()) {
            $formData = $this->_request->getPost();
            
            if ($addRadiationResultForm->isValid($formData)) {
                if($this->radiationResultModel->checkDuplication($formData['requestId'],$formData['radiationId'])) {
                    
                    $this->initForm($addRadiationResultForm);

                    $formData = array('radiationId'=>$radiationId, 'requestId'=> $requestId, 'data'=>$formData['data'], 'submit'=> "Edit");
                    $addRadiationResultForm->setName("Edit Radiation :");

                    $addRadiationResultForm->populate($formData);
                    
                    $addRadiationResultForm->markAsError();
                    $addRadiationResultForm->getElement("radiationId")->addError("Radiation Result is used Before");
                }
                else {
                    $editData = array('visit_request_id'=>$requestId, 'radiation_id'=>$radiationId, 'radiation_data'=>$formData['data']);
                    $this->radiationResultModel->editRadiationResult($radiationId, $requestId, $editData);
                    $this->_forward("list");
                }
            } else {
                    $radiationModel = new Application_Model_Radiation();
                    //$requestModel = new Application_Model_Request_Visit();

                    $this->initForm($addRadiationResultForm);

                    $formData = array('radiationId'=>$radiationId, 'requestId'=> $requestId, 'data'=>$formData['data'], 'submit'=> "Edit");
                    $addRadiationResultForm->setName("Edit Radiation :");

                    $addRadiationResultForm->populate($formData);
            }
        }
        else {    
            if ($radiationId && $requestId) {
                $radiation = $this->radiationResultModel->viewRadiationResult($radiationId, $requestId);
                $radiation = array("radiation_data"=>"");
                if ($radiation) {
                    
                    $this->initForm($addRadiationResultForm);
        
                    $formData = array('radiationId'=>$radiationId, 'requestId'=> $requestId, 'data'=>$radiation['radiation_data'], 'submit'=> "Edit");
                    $addRadiationResultForm->setName("Edit Radiation :");
                    $addRadiationResultForm->populate($formData); 
                }
                else {
                    $this->render("search");
                }
            }
            else {
                $this->render("search");
            }
        }
        
        $this->view->form = $addRadiationResultForm;
    }

    public function deleteAction()
    {
        $radiationId = $this->_request->getParam("radId");
        $requestId = $this->_request->getParam("reqId");
        
        if ( $radiationId && $requestId ) {
            $this->radiationModel->deleteRadiation($radiationId, $requestId);   
            // Check For Error here !!
            $this->_forward("list");
        }
        else {
            $this->render("search");
        }
    }

    public function viewAction()
    {
        $radiationId = $this->_request->getParam("radId");
        $requestId = $this->_request->getParam("reqId");
        
        if ( $radiationId && $requestId ) {
            $radiation = $this->radiationResultModel->viewRadiationResult($radiationId, $requestId);
            $this->view->radiation = $radiation;
        }
        else {
            $this->render("search");
        }    
    }

    public function listAction()
    {
        $this->view->radiationResults = $this->radiationResultModel->getAllradiationResults();
    }

    public function searchAction()
    {
        $radiationId = $this->_request->getParam("radId");
        $requestId = $this->_request->getParam("reqId");
        
        if ($radiationId && $requestId) {
            $this->view->radiationId = $radiationId;
            $this->view->radiations = $this->radiationModel->searchRadiationResults($radiationId, $requestId);
        }
    }
    
    private function initForm($addRadiationResultForm) {
        $radiationModel = new Application_Model_Radiation();
        //$requestModel = new Application_Model_Request_Visit();

        $radiations = $radiationModel->getRadiationsFormated();
        $radiations = array(0=>'Choose Radiation')+$radiations;
        $radiationElement = $addRadiationResultForm->getElement("radiationId");
        $radiationElement->setMultiOptions($radiations);

        $requests = array(1=>'Visit 1', 2=>'Visit 2', 3=>'Visit 3'); //$requestModel=>getRequestsFormated();
        $requests = array(0=>'Choose Visit')+$requests;
        $requestElement = $addRadiationResultForm->getElement("requestId");
        $requestElement->setMultiOptions($requests);
    }


}













