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


}







