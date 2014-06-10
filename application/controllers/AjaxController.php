<?php

class AjaxController extends Zend_Controller_Action
{

    protected $userInfo = null;

    public function init()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $this->auth = Zend_Auth::getInstance();
        $this->userInfo = $this->auth->getIdentity();
    }

    public function indexAction()
    {
        // action body
    }

    public function addMedicationHistoryAction()
    {
        $medicationHistoryModel = new Application_Model_MedicationHistory();
        $medicationModel = new Application_Model_Medication();
        
        $medicationName = $this->_request->getParam("medication");
        $medication = $medicationModel->getMedicationByName($medicationName);
        
        if($medication) {
            $data['patient'] = $this->_request->getParam("patientId");
            $data['medication'] = $medication['id'];
            $data['user_modified_id'] = $this->userInfo['userId'];

            if($medicationHistoryModel->addMedicationHistory($data)) {
                echo "done";
            }
            else {
                echo "Medication  has been added before";
            }
        }
        else {
            echo "Medication is not Found";
        }
    }

    public function addDiseaseHistoryAction()
    {
        $diseaseHistoryModel = new Application_Model_DiseaseHistory();
        $diseaseModel = new Application_Model_Disease();
        
        $diseaseName = $this->_request->getParam("disease");
        $disease = $diseaseModel->getDiseaseByName($diseaseName);
        
        if($disease) {
            $data['patient'] = $this->_request->getParam("patientId");
            $data['disease'] = $disease['id'];
            $data['user_modified_id'] = $this->userInfo['userId'];
        
            if($diseaseHistoryModel->addDiseaseHistory($data)) {
                echo "done";
            }
            else {
                echo "Disease  has been added before";
            }  
        }
        else {
            echo "Disease is not Found";
        }

    }

    public function addSurgeryHistoryAction()
    {
        $surgeryHistoryModel = new Application_Model_SugeryHistory();
        $surgeryModel = new Application_Model_Surgery();
        
        $surgeryName = $this->_request->getParam("surgery");
        $surgery = $surgeryModel->getSurgeryByName($surgeryName);
        
        if($surgery) {
            $data['patient'] = $this->_request->getParam("patientId");
            $data['surgery'] = $surgery['id'];
            $data['user_modified_id'] = $this->userInfo['userId'];

            $surgeryHistoryModel->addSurgeryHistory($data);
            
            echo "done";
        }
        else {
            echo "Surgery is not Found";
        }  
    }

    public function addVitalAction()
    {
        $vitalModel = new Application_Model_Vital();
        $vitalResultModel = new Application_Model_VitalResult();
        
        $vitalName = $this->getRequest()->getParam("vital");
        $vital = $vitalModel->searchByName($vitalName);
        //echo $vital[0]['id'];
        if($vital){
            $data['vitalId'] = $vital[0]['id'];
            $data['requestId'] = $this->getRequest()->getParam("visitId");
            
            $vitalResultModel->addVitalResult($data);
            echo "done";
        }else{
            echo "Vital Type is not found";
        }
    }

    public function removeRadiationImageAction()
    {
        $radiationResultImages = new Application_Model_RadiationsImages();
        
        $radiationImageId = $this->_request->getParam("id");
        $radiationImageTitle = $this->_request->getParam("title");
        $requestId = $this->_request->getParam("requestId");
        
        $radiationResultImages->removeImage($radiationImageId);
        
        unlink(PUBLIC_PATH."/imgs/ResultImgs/$requestId/Radiations/$radiationImageId-$radiationImageTitle");
        
        echo "done";
    }

    public function removeTestImageAction()
    {
        $testResultImages = new Application_Model_TestImages();
        
        $testImageId = $this->_request->getParam("id");
        $testImageTitle = $this->_request->getParam("title");
        $requestId = $this->_request->getParam("requestId");
        
        $testResultImages->removeImage($testImageId);
        
        unlink(PUBLIC_PATH."/imgs/ResultImgs/$requestId/Tests/$testImageId-$testImageTitle");
        
        echo "done";
    }

    public function addRadiationAction()
    {
        $radiationModel = new Application_Model_Radiation();
        $radiationResultModel = new Application_Model_RadiationResult();
        
        $radiationName = $this->getRequest()->getParam("radiation");
        $radiation = $radiationModel->searchByName($radiationName);
        
        if($radiation){
            $data['radiationId'] = $radiation[0]['id'];
            $data['requestId'] = $this->getRequest()->getParam("visitId");
            
            $radiationResultModel->addRadiationResult($data);
            echo 'done';
        }else{
            echo "Radiation Type is not found";
        }
    }

    public function addTestAction()
    {
        $testModel = new Application_Model_Test;
        $testResultModel = new Application_Model_TestResult();
        
        $testName = $this->getRequest()->getParam("test");
        $test = $testModel->searchByName($testName);
        
        if($test){
            $data['testId'] = $test[0]['id'];
            $data['requestId'] = $this->getRequest()->getParam("visitId");
            
            $testResultModel->addTestResult($data);
            echo 'done';
        }else{
            echo "Test Type is not found";
        }
    }

    public function getAdminNotificationNumAction()
    {
        $adminNotificationModel = new Application_Model_AdminNotification();
        $notificationNum = $adminNotificationModel->getNotificationNum();
        
        if($notificationNum != "noNew") {
            echo $notificationNum;
        }
        else {
            echo 'noNew';
        }
    }
    
    public function getClinicianNotificationNumAction()
    {
        $clinicNotificationModel = new Application_Model_ClinicianNotification();
        $notificationNum = $clinicNotificationModel->getNotificationNum();
        
        if($notificationNum != "noNew") {
            echo $notificationNum;
        }
        else {
            echo 'noNew';
        }
    }

    public function setAdminNotificationSeenAction()
    {
        $adminNotificationModel = new Application_Model_AdminNotification();
        $adminNotificationModel->setNotificationAdminSeen();
    }
    
    public function setClinicianNotificationSeenAction()
    {
        $clinicNotificationModel = new Application_Model_ClinicianNotification();
        $clinicNotificationModel->setNotificationClinicSeen();
    }

}
