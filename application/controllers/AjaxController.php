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
        
        $data['patient'] = $this->_request->getParam("patientId");
        $data['medication'] = $medication['id'];

 
        $medicationHistoryModel->addMedicationHistory($data);
        
        echo "done";
    }

    public function addDiseaseHistoryAction()
    {
        $diseaseHistoryModel = new Application_Model_DiseaseHistory();
        $diseaseModel = new Application_Model_Disease();
        
        $diseaseName = $this->_request->getParam("disease");
        $disease = $diseaseModel->getDiseaseByName($diseaseName);
        
        $data['patient'] = $this->_request->getParam("patientId");
        $data['disease'] = $disease['id'];

 
        $diseaseHistoryModel->addDiseaseHistory($data);
        
        echo "done";
    }

    public function addSurgeryHistoryAction()
    {
        $surgeryHistoryModel = new Application_Model_MedicationHistory();
        $surgeryModel = new Application_Model_Medication();
        
        $surgeryName = $this->_request->getParam("medication");
        $surgery = $surgeryModel->getSurgeryByName($surgeryName);
        
        $data['patient'] = $this->_request->getParam("patientId");
        $data['surgery'] = $surgery['id'];

 
        $surgeryHistoryModel->addSurgeryHistory($data);
        
        echo "done";
    }


}







