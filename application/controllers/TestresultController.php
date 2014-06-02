<?php

class TestResultController extends Zend_Controller_Action
{
    protected $base = null;

    protected $testResultModel = null;

    public function init()
    {
        $this->testResultModel = new Application_Model_TestResult();
        $base = Zend_Controller_Front::getInstance()->getBaseUrl();
        
        echo "<h4><a href='".$base."/Testresult'>Test Result Page </a></h4>";
    }

    public function indexAction()
    {
        $testResultsStatistics = $this->testResultModel->gettestResultsStatistics();
        $this->view->testResultsStatistics = $testResultsStatistics;  
    }

    public function addAction()
    {
        $addTestResultForm = new Application_Form_AddTestResult();
        
        if ($this->_request->isPost()) {
            $formData = $this->_request->getPost();
            if ($addTestResultForm->isValid($formData)) {
                if($this->testResultModel->checkDuplication(0,$formData['requestId'],$formData['testId'])) {
                    $addTestResultForm->populate($formData);
                    $addTestResultForm->getElement("testId")->addError("Test Result is used Before");
                }
                else {
                    if($formData['testId'] == 0 || $formData['requestId'] == 0) {
                        $addTestResultForm->populate($formData);
                        $addTestResultForm->getElement("testId")->addError("Please Select Test & Request");
                    }
                    else {
                        $this->testResultModel->addTestResult($formData);
                        $this->_forward("list");
                    }                   
                }
            } else {
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
        
        
        $this->initForm($addTestResultForm);
        
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
                if($this->testResultModel->checkDuplication($formData['resultId'], $formData['requestId'],$formData['testId'])) {
                    
                    $this->initForm($addTestResultForm);

                    $formData = array('resultId'=>$formData['resultId'], 'testId'=>$testId, 'requestId'=> $requestId, 'data'=>$formData['data'], 'submit'=> "Edit");
                    $addTestResultForm->setName("Edit Test :");

                    $addTestResultForm->populate($formData);
                    
                    $addTestResultForm->markAsError();
                    $addTestResultForm->getElement("testId")->addError("Test Result is used Before");
                }
                else {
                    $editData = array('visit_request_id'=>$requestId, 'test_id'=>$testId, 'test_data'=>$formData['data']);
                    $this->testResultModel->editTestResult($testId, $requestId, $editData);
                    $this->_forward("list");
                }
            } else {

                    $this->initForm($addTestResultForm);

                    $formData = array('resultId'=>$formData['resultId'], 'testId'=>$testId, 'requestId'=> $requestId, 'data'=>$formData['data'], 'submit'=> "Edit");
                    $addTestResultForm->setName("Edit Test :");

                    $addTestResultForm->populate($formData);
            }
        }
        else {    
            if ($testId && $requestId) {
                $test = $this->testResultModel->viewTestResult($testId, $requestId);
                if ($test) {
                    $this->initForm($addTestResultForm);
        
                    $formData = array('resultId'=>$test[0]['id'], 'testId'=>$testId, 'requestId'=> $requestId, 'data'=>$test[0]['test_data'], 'submit'=> "Edit");
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
            $this->_forward("list");
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
            $this->view->radiations = $radiationResultModel->viewAllRadiationResult($data["reqid"]);
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
    
    private function initForm($addTestResultForm) {
        $testModel = new Application_Model_Test();
        $requestModel = new Application_Model_Visit();

        $tests = $testModel->getTestsFormated();
        $tests = array(0=>'Choose Test')+$tests;
        $testElement = $addTestResultForm->getElement("testId");
        $testElement->setMultiOptions($tests);

        $requests = $requestModel->getRequestsFormated();
        $requests = array(0=>'Choose Visit')+$requests;
        $requestElement = $addTestResultForm->getElement("requestId");
        $requestElement->setMultiOptions($requests);
    }
}













