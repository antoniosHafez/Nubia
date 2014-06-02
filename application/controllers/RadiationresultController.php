<?php

class RadiationResultController extends Zend_Controller_Action
{

    protected $base = null;

    protected $radiationResultModel = null;

    public function init()
    {
        $this->radiationResultModel = new Application_Model_RadiationResult();
        $base = Zend_Controller_Front::getInstance()->getBaseUrl();
        
        echo "<h4><a href='".$base."/Radiationresult'>Radiation Result Page </a></h4>";
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
                if($this->radiationResultModel->checkDuplication(0, $formData['requestId'],$formData['radiationId'])) {
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
        else
        {
            $data = $this->_request->getParams();
            if(isset($data["raqid"]))
            {
                $array = array("requestId" => $data["raqid"]);
                $addRadiationResultForm->populate($array);
            }
        }
        
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
                if($this->radiationResultModel->checkDuplication($formData['resultId'], $formData['requestId'],$formData['radiationId'])) {
                    
                    $this->initForm($addRadiationResultForm);

                    $formData = array('resultId'=>$formData['resultId'], 'radiationId'=>$radiationId, 'requestId'=> $requestId, 'data'=>$formData['data'], 'submit'=> "Edit");
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

                    $this->initForm($addRadiationResultForm);

                    $formData = array('resultId'=>$formData['resultId'], 'radiationId'=>$radiationId, 'requestId'=> $requestId, 'data'=>$formData['data'], 'submit'=> "Edit");
                    $addRadiationResultForm->setName("Edit Radiation :");

                    $addRadiationResultForm->populate($formData);
            }
        }
        else {    
            if ($radiationId && $requestId) {
                $radiation = $this->radiationResultModel->viewRadiationResult($radiationId, $requestId);
                if ($radiation) {
                    
                    $this->initForm($addRadiationResultForm);
                    $formData = array('resultId'=>$radiation[0]['id'], 'radiationId'=>$radiationId, 'requestId'=> $requestId, 'data'=>$radiation[0]['radiation_data'], 'submit'=> "Edit");
                    $addRadiationResultForm->setName("Edit Radiation :");
                    $addRadiationResultForm->populate($formData); 
                }
                else {
                    $this->_forward("search");
                }
            }
            else {
                $this->_forward("search");
            }
        }
        
        $this->view->form = $addRadiationResultForm;
    }

    public function deleteAction()
    {
        $radiationId = $this->_request->getParam("radId");
        $requestId = $this->_request->getParam("reqId");
        
        if ( $radiationId && $requestId ) {
            $this->radiationResultModel->deleteRadiationResult($radiationId, $requestId);   
            // Check For Error here !!
            $this->_forward("list");
        }
        else {
            $this->_forward("search");
        }
    }

    public function viewAction()
    {
        $radiationId = $this->_request->getParam("radId");
        $requestId = $this->_request->getParam("reqId");
        
        if ( $radiationId && $requestId ) {
            $radiation = $this->radiationResultModel->viewRadiationResult($radiationId, $requestId);
            $this->view->radiationResult = $radiation;
        }
        else {
            $this->_forward("search");
        }    
    }

    public function listAction()
    {
        $this->view->radiationResults = $this->radiationResultModel->getAllradiationResults();
    }

    public function searchAction()
    {
        $requestId = $this->_request->getParam("requestId");
        
        $requestModel = new Application_Model_Visit();
        
        $this->view->requests = $requestModel->listVisit();
        
        if ($requestId) {
            $this->view->requestId = $requestId;
            $this->view->radiationResults = $this->radiationResultModel->searchRadiationResults($requestId);
        }
    }
    
    private function initForm($addRadiationResultForm) {
        $radiationModel = new Application_Model_Radiation();
        $requestModel = new Application_Model_Visit();

        $radiations = $radiationModel->getRadiationsFormated();
        $radiations = array(0=>'Choose Radiation')+$radiations;
        $radiationElement = $addRadiationResultForm->getElement("radiationId");
        $radiationElement->setMultiOptions($radiations);

        $requests = $requestModel->getRequestsFormated();
        $requests = array(0=>'Choose Visit')+$requests;
        $requestElement = $addRadiationResultForm->getElement("requestId");
        $requestElement->setMultiOptions($requests);
    }


}













