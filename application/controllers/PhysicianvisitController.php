<?php

class PhysicianvisitController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // action body
    }

    public function listAction()
    {
        $id = $this->_request->getParam("id");
       $visit_model = new Application_Model_Visit();
       $this->view->preVisits=$visit_model->getPreviousVisitsPhysician($id);
       $phyModel = new Application_Model_Physician();
       $data= $phyModel->getPhyisicanGroup($id);
       $grp_id = $data["group_id"];
       $this->view->penVisits=$visit_model->getPendingVisitsPhysician($grp_id);
       $this->view->accVisits=$visit_model->getAcceptedVisitsPhysician($id);
       $this->view->phyId = $id;
    }

    public function acceptAction()
    {
        
        $phy_id=  $this->_request->getParam("pid");
        $visit_id=  $this->_request->getParam("vid");
        $created_date=  $this->_request->getParam("created_date");
        if($phy_id != null & $visit_id !=null & $created_date !=null)
        {
        $visitModel = new Application_Model_Visit();
        $visitData=array(
            "physican_id"=>$phy_id,
            "created_date"=>  $created_date
        );
        $visitModel->editVisit($visitData, $visit_id);
        $this->redirect("physicianvisit/list?id=$phy_id");
        }
    }

    public function viewAction()
    {
        // action body
    }

    public function cancelAction()
    {
         $phy_id=  $this->_request->getParam("pid");
        $visit_id=  $this->_request->getParam("vid");
    
        if($phy_id != null & $visit_id !=null)
        {
        
        $phy_id=  $this->_request->getParam("pid");
        $visit_id=  $this->_request->getParam("vid");
        $visitModel = new Application_Model_Visit();
        $visitData=array(
            "physican_id"=>null,
            "created_date"=>null
            
        );
        $visitModel->cancelVisit($visitData, $visit_id,$phy_id);
        $this->redirect("physicianvisit/list?id=$phy_id");
    }
    }

    public function liveAction()
    {
         $patientModel = new Application_Model_Patient();
        $personModel = new Application_Model_Person();
        $addressModel = new Application_Model_Address();
        $visitModel = new Application_Model_Visit();
        $disModel = new Application_Model_DiseaseHistory();
        $medModel = new Application_Model_MedicationHistory();
        $surModel = new Application_Model_SugeryHistory();
        $radModel = new Application_Model_RadiationResult();
        $vitModel = new Application_Model_VitalResult();
        $testModel = new Application_Model_TestResult();
        $diseaseForm = new Application_Form_Livevisit();
        ///
        
        $id = $this->_request->getParam("vid");
        $phyid = $this->_request->getParam("phyid");
        $patientId = $this->_request->getParam("patientId");
        $visit_model = new Application_Model_Visit();
        $data = $visit_model->selectVisitById($id);

        $diseaseForm->populate($data);
        
        if ($this->getRequest()->isPost()) {
            $formData = $this->_request->getPost();
            if ($diseaseForm->isValid($formData))
            {        
                 $visit_model = new Application_Model_Visit();
                ////
                
                foreach ($formData["disease_id"] as $diseaseID)
                {
                     $diseaseData = array(
                     "disease_id"=>$diseaseID,
                     "patient_id"=>$patientId,
                     "date"=>Date("Y-m-d"),
                     "visit_request_id"=>$id
                 );
                      $disModel->addDiseaseHistoryForVisit($diseaseData);
                   
                }
                
                foreach ($formData["medication_id"] as $medID)
                {
                     $MedData = array(
                     "medication_id"=>$medID,
                     "patient_id"=>$patientId,
                     "physician_id"=>$phyid,
                     "visit_request_id"=>$id
                 );
                      $medModel->addMedHistoryForVisit($MedData);
                   
                }
                 foreach ($formData["surgery_id"] as $surID)
                {
                     $surData = array(
                     "surgery_id"=>$surID,
                     "patient_id"=>$patientId,
                     "date"=>Date("Y-m-d"),
                     "visit_request_id"=>$id
                 );
                      $surModel->addSurHistoryForVisit($surData);
                   
                }
                
                foreach ($formData["radiation_id"] as $radID)
                {
                     $radData = array(
                     "radiation_id"=>$radID,
                                   "visit_request_id"=>$id
                 );
                      $radModel->addRadResultForVisit($radData);
                   
                }
                
                 foreach ($formData["vital_id"] as $vitID)
                {
                     $vitData = array(
                     "vital_id"=>$vitID,
                     "visit_request_id"=>$id
                 );
                      $vitModel->addVitResultForVisit($vitData);
                   
                }
       
                  foreach ($formData["test_id"] as $testID)
                {
                     $testData = array(
                     "test_id"=>$testID,
                     "visit_request_id"=>$id
                 );
                      $testModel->addTestResultForVisit($testData);
                   
                }
       
                $visitData = array(
                    "notes"=>$formData["notes"]
                );
                 $visit_model->editVisit($visitData,$id);
                
                       $this->redirect("visit/view/id/".$id);     
                      
                      
            }
        }
        $this->view->disease = $diseaseForm;
        ///
        if($this->getRequest()-> isGet()){
            if($this->hasParam("patientId")){
                $patientId = $this->getParam("patientId");
                $this->view->patientId = $patientId;
                $fullData = $patientModel->getPatientFullDataById($patientId);
                $this->view->fullData = $fullData;
            }
            if($this->hasParam("showPatientHistory")){
                $patientId = $this->getParam("showPatientHistory");
                $medicationModel = new Application_Model_MedicationHistory();
                $this->view->medicationData = $medicationModel -> getMedicationByPatientID($patientId);
                $diseaseModel = new Application_Model_DiseaseHistory();
                $this->view->diseaseData = $diseaseModel ->getDiseaseHistoryByPatientID($patientId); 
                $surgeryModel = new Application_Model_SugeryHistory();
                $this->view->surgeryData = $surgeryModel->getSugeryHistoryByPatientID($patientId);
                $this->view->patientId = $patientId;
                $this->render("list-patient-medical-history");
            }
            if($this->hasParam("previousVisits")){
                $patientId = $this->getParam("previousVisits");
                $this->view->patientId = $patientId;
                $this->view->previousVisits = $visitModel->getPreviousVisits($patientId);
                $this->render("list-previous-visits");
            }
            if($this->hasParam("pendingVisits")){
                $patientId = $this->getParam("pendingVisits");
                $this->view->patientId = $patientId;
                $this->view->pendingVisits = $visitModel->getPendingVisits($patientId);
                $this->render("list-pending-visits");
            }
            if($this->hasParam("acceptedVisits")){
                $patientId = $this->getParam("acceptedVisits");
                $this->view->patientId = $patientId;
                $this->view->acceptedVisits = $visitModel->getAcceptedVisits($patientId);
                $this->render("list-accepted-visits");
            }            
        }
    
    }


}











