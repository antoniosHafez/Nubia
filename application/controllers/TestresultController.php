<?php

class TestResultController extends Zend_Controller_Action
{
    protected $base = null;

    protected $testResultModel = null;

    public function init()
    {
        $this->testResultModel = new Application_Model_TestResult();
        $base = Zend_Controller_Front::getInstance()->getBaseUrl();
    }

    public function indexAction()
    {
        $testResultsStatistics = $this->testResultModel->gettestResultsStatistics();
        $this->view->testResultsStatistics = $testResultsStatistics;  
    }

    public function addAction()
    {       
        $param = NULL;
        $data = $this->_request->getParams();
        if($data["raqid"])
        {   
            $addTestResultForm = new Application_Form_AddTestResult(true);
        }
        else
            $addTestResultForm = new Application_Form_AddTestResult(true);
        
        
        if ($this->_request->isPost()) {
            $formData = $this->_request->getPost();
            if ($addTestResultForm->isValid($formData)) {
                if($this->testResultModel->checkDuplication(0, $formData['requestId'],$formData['testId'])) {
                    $addTestResultForm->populate($formData);
                    $addTestResultForm->getElement("testId")->addError("Test Result is used Before");
                }
                else {
                    if($formData['testId'] == 0 || $formData['requestId'] == 0) {
                        $addTestResultForm->populate($formData);
                        $addTestResultForm->getElement("testId")->addError("Please Select Test & Request");
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
                                $addTestResultForm->getElement("requestId")->addError("Failed To load images"); 
                                $addTestResultForm->populate($formData);
                            }
                            else {
                                $testImagesModel = new Application_Model_TestImages();
                                $testResultId = $this->testResultModel->addTestResult($formData);
                                $date = new DateTime();
                                $timeStamp = $date->getTimestamp();
                                foreach($files as $file => $fileInfo) {
                                    if($upload->isUploaded($file)) {
                                       $testPath = PUBLIC_PATH.'/imgs/ResultImgs/'.$formData['requestId'].'/Tests';
                                        $testId = $formData['testId'];
                                        if (!file_exists($testPath)) {
                                            $old = umask(0);
                                            mkdir($testPath, 0777, true);
                                            umask($old); 
                                        }
                                        $extension = pathinfo($fileInfo['name'], PATHINFO_EXTENSION);
                                        $imageId = $testImagesModel->addTestImage($testResultId, $timeStamp, $extension);

                                        $upload->addFilter('Rename',
                                        array('target' => $testPath.'/'.$imageId."-".$timeStamp.'.'.$extension,'overwrite' => true));
                                        
                                        $upload->receive($file);
                                    }     
                                }
                                
                                $this->redirect("/testresult/view?radId=".$testId."&reqId=".$formData['requestId']); 
                            }
                        }
                        else {
                            $testResultId = $this->testResultModel->addTestResult($formData);
                            $this->redirect("/testresult/view?radId=".$testId."&reqId=".$formData['requestId']); 
                        }                      
                    }
                }
            }
            else {
                $addTestResultForm->populate($formData);
            }
        }
        else
        {
            $data = $this->_request->getParams();
            if(isset($data["raqid"]))
            {
                $array = array("requestId" => $data["raqid"]);
                $addTestResultForm->populate($array);
            }
        }
        
        $this->initForm($addTestResultForm,true);
        
        $this->view->form = $addTestResultForm;
    }

    public function editAction()
    {
        $addTestResultForm = new Application_Form_AddTestResult();
        
        $testId = $this->_request->getParam("radId");
        $requestId = $this->_request->getParam("reqId");
        
        if ($this->_request->isPost()) {
            $formData = $this->_request->getPost();
            
            if ($addTestResultForm->isValid($formData)) {
                if($formData['testId'] == 0 || $formData['requestId'] == 0) {
                        $this->getImages($formData['resultId'], $requestId);
                        $this->initForm($addTestResultForm);
                        $addTestResultForm->populate($formData);
                        $addTestResultForm->getElement("testId")->addError("Please Select Test & Request");
                }
                else {
                    if($this->testResultModel->checkDuplication($formData['resultId'], $formData['requestId'],$formData['testId'])) {
                    
                        $this->initForm($addTestResultForm);
                        $this->getImages($formData['resultId'], $requestId);

                        $addTestResultForm->markAsError();
                        $addTestResultForm->getElement("testId")->addError("Test Result is used Before");
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
                                $this->initForm($addTestResultForm);
                                $this->getImages($formData['resultId'], $requestId);
                                $addTestResultForm->getElement("testId")->addError("Failed To load images"); 
                                $addTestResultForm->populate($formData);
                            }
                            else {
                                $testImagesModel = new Application_Model_TestImages();

                                $editData = array('visit_request_id'=>$requestId, 'test_id'=>$testId);
                                $this->testResultModel->editTestResult($testId, $requestId, $editData);

                                $date = new DateTime();
                                $timeStamp = $date->getTimestamp();
                                foreach($files as $file => $fileInfo) {
                                    if($upload->isUploaded($file)) {
                                       $testPath = PUBLIC_PATH.'/imgs/ResultImgs/'.$formData['requestId'].'/Tests';
                                        $testResultId = $formData['resultId'];
                                        if (!file_exists($testPath)) {
                                            $old = umask(0);
                                            mkdir($testPath, 0777, true);
                                            umask($old); 
                                        }
                                        $extension = pathinfo($fileInfo['name'], PATHINFO_EXTENSION);
                                        $imageId = $testImagesModel->addTestImage($testResultId, $timeStamp, $extension);

                                        $upload->addFilter('Rename',
                                        array('target' => $testPath.'/'.$imageId."-".$timeStamp.'.'.$extension,'overwrite' => true));

                                        $upload->receive($file);
                                    }     
                                }
                                $this->redirect("/testresult/view?radId=".$testId."&reqId=".$requestId); 
                            }
                        }
                        else {

                            $editData = array('visit_request_id'=>$formData['requestId'], 'test_id'=>$formData['testId']);
                            $this->testResultModel->editTestResult($testId, $requestId, $editData);
                            $this->redirect("/testresult/view?radId=".$testId."&reqId=".$requestId);
                        }
                    }
                }
                
            } else {
                    $this->getImages($formData['resultId']);
            }
        }
        else {
            if ($testId && $requestId) {
                $test = $this->testResultModel->viewTestResult($testId, $requestId);
                if ($test) {
                    $resultId = $test[0]['id'];
                    
                    $this->getImages($resultId, $requestId);
                    
                    $this->initForm($addTestResultForm);
                    $formData = array('resultId'=>$resultId, 'testId'=>$testId, 'requestId'=> $requestId, 'submit'=> "Edit");
                    $addTestResultForm->setName("Edit Test :");
                    $addTestResultForm->populate($formData); 
                }
                else {
                    $this->_forward("search");
                }
            }
            else {
                $this->_forward("search");
            }
        }
        
        $this->view->form = $addTestResultForm;
    }

    public function deleteAction()
    {
        $testId = $this->_request->getParam("radId");
        $requestId = $this->_request->getParam("reqId");
        
        if ( $testId && $requestId ) {
            $this->testResultModel->deleteTestResult($testId, $requestId);   
            // Check For Error here !!
            $this->redirect("/test-result/view?dep=all&reqid=".$requestId."");
            //$this->_forward("list");
        }
        else {
            $this->_forward("search");
        }
    }

    public function viewAction()
    {
        
        $data = $this->_request->getParams();
        
        if($data["dep"] && $data["reqid"])
        {
            $radiationResultModel = new Application_Model_RadiationResult();
            $vitalResultModel = new Application_Model_VitalResult();
            
            $this->_helper->viewRenderer('depandency');
            
            $this->view->tests = $this->testResultModel->viewAllTestResult($data["reqid"]);
            $this->view->tests = $radiationResultModel->viewAllRadiationResult($data["reqid"]);
            $this->view->vitals = $vitalResultModel->viewAllVitalResult($data["reqid"]);
            $this->view->reqid = $data["reqid"];
        }
        else
        {
            $testId = $this->_request->getParam("radId");
            $requestId = $this->_request->getParam("reqId");

            if ( $testId && $requestId ) {
                $test = $this->testResultModel->viewTestResult($testId, $requestId);
                $this->view->testResult = $test;
            }
            else {
                $this->_forward("search");
            }    
        }
    }

    public function listAction()
    {
        $this->view->testResults = $this->testResultModel->getAlltestResults();
    }

    public function searchAction()
    {
        $requestId = $this->_request->getParam("requestId");
        
        $requestModel = new Application_Model_Visit();
        
        $this->view->requests = $requestModel->listVisit();
        
        if ($requestId) {
            $this->view->requestId = $requestId;
            $this->view->testResults = $this->testResultModel->searchTestResults($requestId);
        }
    }
    
    private function initForm($addTestResultForm,$param = FALSE) {
        $testModel = new Application_Model_Test();
        $requestModel = new Application_Model_Visit();

        $tests = $testModel->getTestsFormated();
        $tests = array(0=>'Choose Test')+$tests;
        $testElement = $addTestResultForm->getElement("testId");
        $testElement->setMultiOptions($tests);
        
        if($param)
        {
            $requests = $requestModel->getRequestsFormated();
            $requests = array(0=>'Choose Visit')+$requests;
            $requestElement = $addTestResultForm->getElement("requestId");
            $requestElement->setMultiOptions($requests);
        }
    }
    
    private function getImages($resultId, $requestId) {
        $testResultImages = new Application_Model_TestImages();
        $imagesTitles = $testResultImages->getTestImages($resultId);
        $this->view->images = $imagesTitles;
        $this->view->requestId = $requestId;
                    
    }
}













