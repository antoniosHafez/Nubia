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
        
        $param = NULL;
        $data = $this->_request->getParams();
        if($data["raqid"])
            $param = array("type" => "patient");
        
        $addVitalResultForm = new Application_Form_AddVitalResult($param);
        
        if ($this->_request->isPost()) {
            $formData = $this->_request->getPost();
            if ($addVitalResultForm->isValid($formData)) {
                if($this->vitalResultModel->checkDuplication(0,$formData['requestId'],$formData['vitalId'])) {
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
                        //$this->_forward("list");
                        $this->redirect("/test-result/view?dep=all&reqid=".$formData['requestId']."");
                    }                   
                }
            } else {
                $addVitalResultForm->populate($formData);
            }
        }
        else
        {
            $data = $this->_request->getParams();
            if($data["raqid"])
            {
                $array = array("requestId" => $data["raqid"]);
                $addVitalResultForm->populate($array);
            }
        }
        
        $this->initForm($addVitalResultForm,$param);
        
        $this->view->form = $addVitalResultForm;
    }

    public function editAction()
    {
        
        $vitalId = $this->_request->getParam("radId");
        $requestId = $this->_request->getParam("reqId");
        
        $param = NULL;
        if ($vitalId && $requestId)
            $param = array("type" => "patient");
        
        $addVitalResultForm = new Application_Form_AddVitalResult($param);
        
        if ($this->_request->isPost()) {
            $formData = $this->_request->getPost();
            
            if ($addVitalResultForm->isValid($formData)) {
                if($this->vitalResultModel->checkDuplication($formData['resultId'], $formData['requestId'],$formData['vitalId'])) {
                    
                    $this->initForm($addVitalResultForm,$param);

                    $formData = array('resultId'=>$formData['resultId'], 'vitalId'=>$vitalId, 'requestId'=> $requestId, 'data'=>$formData['data'], 'submit'=> "Edit");
                    $addVitalResultForm->setName("Edit Vital :");

                    $addVitalResultForm->populate($formData);
                    
                    $addVitalResultForm->markAsError();
                    $addVitalResultForm->getElement("vitalId")->addError("Vital Result is used Before");
                }
                else {
                    $editData = array('visit_request_id'=>$requestId, 'vital_id'=>$vitalId, 'vital_data'=>$formData['data']);
                    $this->vitalResultModel->editVitalResult($vitalId, $requestId, $editData);
                    //$this->_forward("list");
                    $this->redirect("/test-result/view?dep=all&reqid=".$requestId."");
                }
            } else {
                    $this->initForm($addVitalResultForm,$param);

                    $formData = array('resultId'=>$formData['resultId'], 'vitalId'=>$vitalId, 'requestId'=> $requestId, 'data'=>$formData['data'], 'submit'=> "Edit");
                    $addVitalResultForm->setName("Edit Vital :");

                    $addVitalResultForm->populate($formData);
            }
        }
        else {    
            if ($vitalId && $requestId) {
                $vital = $this->vitalResultModel->viewVitalResult($vitalId, $requestId);
                if ($vital) {
                    
                    $this->initForm($addVitalResultForm,$param);
                    $formData = array('resultId'=>$vital[0]['id'], 'vitalId'=>$vitalId, 'requestId'=> $requestId, 'data'=>$vital[0]['vital_data'], 'submit'=> "Edit");
                    $addVitalResultForm->setName("Edit Vital :");
                    $addVitalResultForm->populate($formData); 
                }
                else {
                    $this->_forward("search");
                }
            }
            else {
                $this->_forward("search");
            }
        }
        
        $this->view->form = $addVitalResultForm;
    }

    public function deleteAction()
    {
        $vitalId = $this->_request->getParam("radId");
        $requestId = $this->_request->getParam("reqId");
        
        if ( $vitalId && $requestId ) {
            $this->vitalResultModel->deleteVitalResult($vitalId, $requestId);   
            // Check For Error here !!
            //$this->_forward("list");
            $this->redirect("/test-result/view?dep=all&reqid=".$requestId."");
        }
        else {
            $this->_forward("search");
        }
    }

    public function viewAction()
    {
        $vitalId = $this->_request->getParam("radId");
        $requestId = $this->_request->getParam("reqId");
        
        if ( $vitalId && $requestId ) {
            $vital = $this->vitalResultModel->viewVitalResult($vitalId, $requestId);
            $this->view->vitalResult = $vital;
        }
        else {
            $this->_forward("search");
        }    
    }

    public function listAction()
    {
        $this->view->vitalResults = $this->vitalResultModel->getAllvitalResults();
    }

    public function searchAction()
    {
        $requestId = $this->_request->getParam("requestId");
        
        $requestModel = new Application_Model_Visit();
        
        $this->view->requests = $requestModel->listVisit();
        
        if ($requestId) {
            $this->view->requestId = $requestId;
            $this->view->vitalResults = $this->vitalResultModel->searchVitalResults($requestId);
        }
    }
    
    private function initForm($addVitalResultForm,$param = null) {
        $vitalModel = new Application_Model_Vital();
        $requestModel = new Application_Model_Visit();

        $vitals = $vitalModel->getVitalsFormated();
        $vitals = array(0=>'Choose Vital')+$vitals;
        $vitalElement = $addVitalResultForm->getElement("vitalId");
        $vitalElement->setMultiOptions($vitals);
        
        if($param == NULL)
        {
            $requests = $requestModel->getRequestsFormated();
            $requests = array(0=>'Choose Visit')+$requests;
            $requestElement = $addVitalResultForm->getElement("requestId");
            $requestElement->setMultiOptions($requests);
        }
    }
}













