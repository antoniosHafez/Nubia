<?php

class VitalResultController extends Zend_Controller_Action
{

    protected $base = null;

    protected $vitalResultModel = null;

    public function init()
    {
        $this->vitalResultModel = new Application_Model_VitalResult();
        $base = Zend_Controller_Front::getInstance()->getBaseUrl();
        
        echo "<h4><a href='".$base."/Vital-Result'>Vital Result Page </a></h4>";
    }

    public function indexAction()
    {
        $vitalResultsStatistics = $this->vitalResultModel->getvitalResultsStatistics();
        $this->view->vitalResultsStatistics = $vitalResultsStatistics;  
    }

    public function addAction()
    {
        $addVitalResultForm = new Application_Form_AddVitalResult();
        
        if ($this->_request->isPost()) {
            $formData = $this->_request->getPost();
            if ($addVitalResultForm->isValid($formData)) {
                if($this->vitalResultModel->checkDuplication($formData['requestId'],$formData['vitalId'])) {
                    $addVitalResultForm->populate($formData);
                    $addVitalResultForm->getElement("vitalId")->addError("Vital Result is used Before");
                }
                else {
                    if($formData['vitalId'] == 0 || $formData['requestId'] == 0) {
                        $addVitalResultForm->populate($formData);
                        $addVitalResultForm->getElement("vitalId")->addError("Please Select Vital & Request");
                    }
                    else {
                        $this->vitalResultModel->addVitalResult($formData);
                        $this->_forward("list");
                    }                   
                }
            } else {
                $addVitalResultForm->populate($formData);
            }
        }
        
        $vitalModel = new Application_Model_Vital();
        //$requestModel = new Application_Model_Request_Visit();
        
        $this->initForm($addVitalResultForm);
        
        $this->view->form = $addVitalResultForm;
    }

    public function editAction()
    {
        $addVitalResultForm = new Application_Form_AddVitalResult();
        $vitalId = $this->_request->getParam("radId");
        $requestId = $this->_request->getParam("reqId");
        
        if ($this->_request->isPost()) {
            $formData = $this->_request->getPost();
            
            if ($addVitalResultForm->isValid($formData)) {
                if($this->vitalResultModel->checkDuplication($formData['requestId'],$formData['vitalId'])) {
                    
                    $this->initForm($addVitalResultForm);

                    $formData = array('vitalId'=>$vitalId, 'requestId'=> $requestId, 'data'=>$formData['data'], 'submit'=> "Edit");
                    $addVitalResultForm->setName("Edit Vital :");

                    $addVitalResultForm->populate($formData);
                    
                    $addVitalResultForm->markAsError();
                    $addVitalResultForm->getElement("vitalId")->addError("Vital Result is used Before");
                }
                else {
                    $editData = array('visit_request_id'=>$requestId, 'vital_id'=>$vitalId, 'vital_data'=>$formData['data']);
                    $this->vitalResultModel->editVitalResult($vitalId, $requestId, $editData);
                    $this->_forward("list");
                }
            } else {
                    $vitalModel = new Application_Model_Vital();
                    //$requestModel = new Application_Model_Request_Visit();

                    $this->initForm($addVitalResultForm);

                    $formData = array('vitalId'=>$vitalId, 'requestId'=> $requestId, 'data'=>$formData['data'], 'submit'=> "Edit");
                    $addVitalResultForm->setName("Edit Vital :");

                    $addVitalResultForm->populate($formData);
            }
        }
        else {    
            if ($vitalId && $requestId) {
                $vital = $this->vitalResultModel->viewVitalResult($vitalId, $requestId);
                $vital = array("vital_data"=>"");
                if ($vital) {
                    
                    $this->initForm($addVitalResultForm);
        
                    $formData = array('vitalId'=>$vitalId, 'requestId'=> $requestId, 'data'=>$vital['vital_data'], 'submit'=> "Edit");
                    $addVitalResultForm->setName("Edit Vital :");
                    $addVitalResultForm->populate($formData); 
                }
                else {
                    $this->render("search");
                }
            }
            else {
                $this->render("search");
            }
        }
        
        $this->view->form = $addVitalResultForm;
    }

    public function deleteAction()
    {
        $vitalId = $this->_request->getParam("radId");
        $requestId = $this->_request->getParam("reqId");
        
        if ( $vitalId && $requestId ) {
            $this->vitalModel->deleteVital($vitalId, $requestId);   
            // Check For Error here !!
            $this->_forward("list");
        }
        else {
            $this->render("search");
        }
    }

    public function viewAction()
    {
        $vitalId = $this->_request->getParam("radId");
        $requestId = $this->_request->getParam("reqId");
        
        if ( $vitalId && $requestId ) {
            $vital = $this->vitalResultModel->viewVitalResult($vitalId, $requestId);
            $this->view->vital = $vital;
        }
        else {
            $this->render("search");
        }    
    }

    public function listAction()
    {
        $this->view->vitalResults = $this->vitalResultModel->getAllvitalResults();
    }

    public function searchAction()
    {
        $requestId = $this->_request->getParam("requestId");
        
        //$requestModel = new Application_Model_Request_Visit();
        
        $this->view->requests = array(array('id'=>1,'name'=>'Visit 1'),array('id'=>2,'name'=>'Visit 2'),array('id'=>3,'name'=>'Visit 3')); // $requestModel=>getRequestsFormated();
        
        if ($requestId) {
            $this->view->requestId = $requestId;
            $this->view->vitalResults = $this->vitalResultModel->searchVitalResults($requestId);
        }
    }
    
    private function initForm($addVitalResultForm) {
        $vitalModel = new Application_Model_Vital();
        //$requestModel = new Application_Model_Request_Visit();

        $vitals = $vitalModel->getVitalsFormated();
        $vitals = array(0=>'Choose Vital')+$vitals;
        $vitalElement = $addVitalResultForm->getElement("vitalId");
        $vitalElement->setMultiOptions($vitals);

        $requests = array(1=>'Visit 1', 2=>'Visit 2', 3=>'Visit 3'); //$requestModel=>getRequestsFormated();
        $requests = array(0=>'Choose Visit')+$requests;
        $requestElement = $addVitalResultForm->getElement("requestId");
        $requestElement->setMultiOptions($requests);
    }
}













