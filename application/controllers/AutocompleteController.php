<?php

class AutocompleteController extends Zend_Controller_Action
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

    public function getMedicationAction()
    {
        $medicationModel = new Application_Model_Medication();
        $medcationKey = $this->_request->getParam("medication");
        echo  $medicationModel->getJsonMedication($medcationKey);
        
    }

    public function getDiseaseAction()
    {
        $diseaseModel = new Application_Model_Disease();
        $diseaseKey = $this->_request->getParam("disease");
        
        echo  $diseaseModel->getJsonDisease($diseaseKey);
    }

    public function getSurgeryAction()
    {
        $surgeryModel = new Application_Model_Surgery();
        $surgeryKey = $this->_request->getParam("surgery");
        
        echo  $surgeryModel->getJsonSurgery($surgeryKey);
    }

    public function getRadiationAction()
    {
        $radModel = new Application_Model_Radiation();
        $radKey = $this->_request->getParam("radiation");
        
        echo  $radModel->getJsonRadiation($radKey);
    }

    public function getVitalAction()
    {
        $vitModel = new Application_Model_Vital();
        $vitKey = $this->_request->getParam("vital");
        
        echo  $vitModel->getJsonVital($vitKey);
    }

    public function getTestAction()
    {
        $testModel = new Application_Model_Test();
        $testKey = $this->_request->getParam("test");
        
        echo  $testModel->getJsonTest($testKey);
        
    }

    public function getPatientAction()
    {
        $patientModel = new Application_Model_Patient();
        $patientKey = $this->_request->getParam("patient");
        
        echo $patientModel->getJsonPatient($patientKey);
    }


}









