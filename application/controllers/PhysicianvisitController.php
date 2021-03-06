<?php

class PhysicianvisitController extends Zend_Controller_Action
{
    private $session_id =0;
    public function init()
    {
        $this->_helper->layout->disableLayout();
        $authorization = Zend_Auth::getInstance();
        $authInfo = $authorization->getIdentity();
        $this->session_id = $authInfo["userId"];
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
        $created_time=  $this->_request->getParam("created_time");
        $combined_date_and_time = $created_date . ' ' . $created_time;
            $past_date = strtotime($combined_date_and_time);
        if($phy_id != null & $visit_id !=null & $created_date !=null)
        {
        $visitModel = new Application_Model_Visit();
        $visitData=array(
            "physican_id"=>$phy_id,
            "created_date"=>  date('Y-m-d H:i:s',$past_date)
            
        );
        $visitModel->editVisit($visitData, $visit_id);
        $this->redirect("visit/view/id/".$visit_id."");
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
        $livevisitForm = new Application_Form_Livevisit();
        $med = new Application_Model_Medication();
        $this->view->medModel = $med;
        ///
        
        $id = $this->_request->getParam("vid");
        $phyid = $this->_request->getParam("phyid");
        $patientId = $this->_request->getParam("patientId");
        $visit_model = new Application_Model_Visit();
       
        if ($this->getRequest()->isPost()) {
            $formData = $this->_request->getPost();
                 $visit_model = new Application_Model_Visit();
                ////
                
                foreach ($formData["disbox"] as $diseaseID)
                {
                     $diseaseData = array(
                     "disease_id"=>$diseaseID,
                     "patient_id"=>$patientId,
                     "date"=>Date("Y-m-d"),
                     "visit_request_id"=>$id,
                     "user_modified_id"=>$this->session_id
                 );
                      $disModel->addDiseaseHistoryForVisit($diseaseData);
                   
                }
                
                foreach ($formData["medbox"] as $medID)
                {
                     $MedData = array(
                     "medication_id"=>$medID,
                     "patient_id"=>$patientId,
                     "physician_id"=>$phyid,
                     "visit_request_id"=>$id,
                     "user_modified_id"=>$this->session_id
                 );
                      $medModel->addMedHistoryForVisit($MedData);
                   
                }
                 foreach ($formData["surbox"] as $surID)
                {
                     $surData = array(
                     "surgery_id"=>$surID,
                     "patient_id"=>$patientId,
                     "date"=>Date("Y-m-d"),
                     "visit_request_id"=>$id,
                     "user_modified_id"=>$this->session_id
                 );
                      $surModel->addSurHistoryForVisit($surData);
                   
                }
                
                foreach ($formData["radbox"] as $radID)
                {
                     $radData = array(
                     "radiation_id"=>$radID,
                     "visit_request_id"=>$id,
                     "user_modified_id"=>$this->session_id
                 );
                      $radModel->addRadResultForVisit($radData);
                   
                }
                
                 foreach ($formData["vitbox"] as $vitID)
                {
                     $vitData = array(
                     "vital_id"=>$vitID,
                     "visit_request_id"=>$id,
                     "user_modified_id"=>$this->session_id
                 );
                      $vitModel->addVitResultForVisit($vitData);
                   
                }
       
                  foreach ($formData["testbox"] as $testID)
                {
                     $testData = array(
                     "test_id"=>$testID,
                     "visit_request_id"=>$id,
                     "user_modified_id"=>$this->session_id
                 );
                      $testModel->addTestResultForVisit($testData);
                   
                }
       
                $visitData = array(
                    "notes"=>$formData["notes"]
                );
                 $visit_model->editVisit($visitData,$id);
                
                       $this->redirect("visit/view/id/".$id);     
                      
                      
            
        }
        $this->view->prescriptionForm = $livevisitForm;
        ///
          $fullData = $patientModel->getPatientFullDataById($patientId);
                $this->view->fullData = $fullData;
                     $medicationModel = new Application_Model_MedicationHistory();
                $this->view->medicationData = $medicationModel->getMedicationByPatientID($patientId);
                $diseaseModel = new Application_Model_DiseaseHistory();
                $this->view->diseaseData = $diseaseModel ->getDiseaseHistoryByPatientID($patientId); 
                $surgeryModel = new Application_Model_SugeryHistory();
               $this->view->surgeryData = $surgeryModel->getSugeryHistoryByPatientID($patientId);
              $this->view->TestData=$testModel->getTestResultByVisitID($id) ;
              $this->view->VitalData=$vitModel->getVitalResultByVisitID($id);
              $radImgModel = new Application_Model_RadiationsImages();
              $this->view->radImgs = $radImgModel->getRadImgByVisitID($id);
               $testImgModel = new Application_Model_TestImages();
              $this->view->testImgs = $testImgModel->getTestImgByVisitID($id);
              $this->view->visitid = $id;
     
    }


}











