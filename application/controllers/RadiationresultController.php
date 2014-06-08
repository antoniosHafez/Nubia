<?php

class RadiationResultController extends Zend_Controller_Action
{

    protected $base = null;

    protected $radiationResultModel = null;

    public function init()
    {
        $this->radiationResultModel = new Application_Model_RadiationResult();
        $base = Zend_Controller_Front::getInstance()->getBaseUrl();
    }

    public function indexAction()
    {
        $radiationResultsStatistics = $this->radiationResultModel->getradiationResultsStatistics();
        $this->view->radiationResultsStatistics = $radiationResultsStatistics;  
    }

    public function addAction()
    {       
        $param = NULL;
        $data = $this->_request->getParams();
        if($data["raqid"])
        {   
            $addRadiationResultForm = new Application_Form_AddRadiationResult(true);
        }
        else
            $addRadiationResultForm = new Application_Form_AddRadiationResult(true);
        
        
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
                        $errors = 0;  
                        $upload = new Zend_File_Transfer_Adapter_Http();
                        $upload->addValidator('IsImage', false);
                        $files  = $upload->getFileInfo();
                        
                        $checkUploaded = FALSE;
                        foreach($files as $file => $fileInfo) {  
                            if ($upload->isUploaded($file)) {
                                $checkUploaded = TRUE;
                                if (!$upload->isValid($file)) {
                                    $errors++;
                                }
                            }
                        }
                        
                        if($checkUploaded) {
                            if($errors > 0) {
                                $addRadiationResultForm->getElement("requestId")->addError("Failed To load images"); 
                                $addRadiationResultForm->populate($formData);
                            }
                            else {
                                $radiationImagesModel = new Application_Model_RadiationsImages();
                                
                                $radiationResultId = $this->radiationResultModel->addRadiationResult($formData);
                                
                                $date = new DateTime();
                                $timeStamp = $date->getTimestamp();
                                foreach($files as $file => $fileInfo) {
                                    if($upload->isUploaded($file)) {
                                       $radiationPath = PUBLIC_PATH.'/imgs/ResultImgs/'.$formData['requestId'].'/Radiations';
                                        $radiationId = $formData['radiationId'];
                                        if (!file_exists($radiationPath)) {
                                            $old = umask(0);
                                            mkdir($radiationPath, 0777, true);
                                            umask($old); 
                                        }
                                        $extension = pathinfo($fileInfo['name'], PATHINFO_EXTENSION);
                                        $imageId = $radiationImagesModel->addRadiationImage($radiationResultId, $timeStamp, $extension);

                                        $upload->addFilter('Rename',
                                        array('target' => $radiationPath.'/'.$imageId."-".$timeStamp.'.'.$extension,'overwrite' => true));
                                        
                                        $upload->receive($file);
                                    }     
                                }
                                
                                $this->redirect("/radiationresult/view?radId=".$radiationId."&reqId=".$formData['requestId']); 
                            }
                        }
                        else {
                            $radiationResultId = $this->radiationResultModel->addRadiationResult($formData);
                            $this->redirect("/radiationresult/view?radId=".$radiationId."&reqId=".$formData['requestId']); 
                        }                      
                    }
                }
            }
            else {
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
        
        $this->initForm($addRadiationResultForm,true);
        
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
                if($formData['radiationId'] == 0 || $formData['requestId'] == 0) {
                        $this->getImages($formData['resultId'], $requestId);
                        $this->initForm($addRadiationResultForm);
                        $addRadiationResultForm->populate($formData);
                        $addRadiationResultForm->getElement("radiationId")->addError("Please Select Radiation & Request");
                }
                else {
                    if($this->radiationResultModel->checkDuplication($formData['resultId'], $formData['requestId'],$formData['radiationId'])) {
                    
                        $this->initForm($addRadiationResultForm);
                        $this->getImages($formData['resultId'], $requestId);

                        $addRadiationResultForm->markAsError();
                        $addRadiationResultForm->getElement("radiationId")->addError("Radiation Result is used Before");
                    }
                    else {
                        $errors = 0;  
                        $upload = new Zend_File_Transfer_Adapter_Http();
                        $upload->addValidator('IsImage', false);
                        $files  = $upload->getFileInfo();

                        $checkUploaded = FALSE;
                        foreach($files as $file => $fileInfo) {  
                            if ($upload->isUploaded($file)) {
                                $checkUploaded = TRUE;
                                if (!$upload->isValid($file)) {
                                    $errors++;
                                }
                            }
                        }

                        if($checkUploaded) {
                            if($errors > 0) {
                                $this->initForm($addRadiationResultForm);
                                $this->getImages($formData['resultId'], $requestId);
                                $addRadiationResultForm->getElement("radiationId")->addError("Failed To load images"); 
                                $addRadiationResultForm->populate($formData);
                            }
                            else {
                                $radiationImagesModel = new Application_Model_RadiationsImages();

                                $editData = array('visit_request_id'=>$requestId, 'radiation_id'=>$radiationId);
                                $this->radiationResultModel->editRadiationResult($radiationId, $requestId, $editData);

                                $date = new DateTime();
                                $timeStamp = $date->getTimestamp();
                                foreach($files as $file => $fileInfo) {
                                    if($upload->isUploaded($file)) {
                                       $radiationPath = PUBLIC_PATH.'/imgs/ResultImgs/'.$formData['requestId'].'/Radiations';
                                        $radiationResultId = $formData['resultId'];
                                        if (!file_exists($radiationPath)) {
                                            $old = umask(0);
                                            mkdir($radiationPath, 0777, true);
                                            umask($old); 
                                        }
                                        $extension = pathinfo($fileInfo['name'], PATHINFO_EXTENSION);
                                        $imageId = $radiationImagesModel->addRadiationImage($radiationResultId, $timeStamp, $extension);

                                        $upload->addFilter('Rename',
                                        array('target' => $radiationPath.'/'.$imageId."-".$timeStamp.'.'.$extension,'overwrite' => true));

                                        $upload->receive($file);
                                    }     
                                }
                                $this->redirect("/radiationresult/view?radId=".$radiationId."&reqId=".$requestId); 
                            }
                        }
                        else {

                            $editData = array('visit_request_id'=>$formData['requestId'], 'radiation_id'=>$formData['radiationId']);
                            $this->radiationResultModel->editRadiationResult($radiationId, $requestId, $editData);
                            $this->redirect("/radiationresult/view?radId=".$radiationId."&reqId=".$requestId);
                        }
                    }
                }
                
            } else {
                    $this->getImages($formData['resultId']);
            }
        }
        else {
            if ($radiationId && $requestId) {
                $radiation = $this->radiationResultModel->viewRadiationResult($radiationId, $requestId);
                if ($radiation) {
                    $resultId = $radiation[0]['id'];
                    
                    $this->getImages($resultId, $requestId);
                    
                    $this->initForm($addRadiationResultForm);
                    $formData = array('resultId'=>$resultId, 'radiationId'=>$radiationId, 'requestId'=> $requestId, 'submit'=> "Edit");
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
            //$this->redirect("test-result/view?dep=all&reqid=".$requestId."");
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
    
    private function initForm($addRadiationResultForm, $param = FALSE) {
        $radiationModel = new Application_Model_Radiation();
        $requestModel = new Application_Model_Visit();

        $radiations = $radiationModel->getRadiationsFormated();
        $radiations = array(0=>'Choose Radiation')+$radiations;
        $radiationElement = $addRadiationResultForm->getElement("radiationId");
        $radiationElement->setMultiOptions($radiations);
        
        if($param) {
            $requests = $requestModel->getRequestsFormated();
            $requests = array(0=>'Choose Visit')+$requests;
            $requestElement = $addRadiationResultForm->getElement("requestId");
            $requestElement->setMultiOptions($requests);
        }
    }
    
    private function getImages($resultId, $requestId) {
        $radiationResultImages = new Application_Model_RadiationsImages();
        $imagesTitles = $radiationResultImages->getRadiationImages($resultId);
        $this->view->images = $imagesTitles;
        $this->view->requestId = $requestId;
                    
    }
}

    













