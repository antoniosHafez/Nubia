<?php

class AjaxController extends Zend_Controller_Action
{

    public function init()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
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


}
