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
        if($phy_id != null & $visit_id !=null)
        {
        $visitModel = new Application_Model_Visit();
        $visitData=array(
            "physican_id"=>$phy_id
            
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
        if($phy_id != null & $visit_id !=null)
        {
        
        $phy_id=  $this->_request->getParam("pid");
        $visit_id=  $this->_request->getParam("vid");
        $visitModel = new Application_Model_Visit();
        $visitData=array(
            "physican_id"=>null
            
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
        ///
        $action = array("action" => "doctor");
        $VisitForm = new Application_Form_Visit($action);

        $id = $this->_request->getParam("vid");
        $visit_model = new Application_Model_Visit();
        $data = $visit_model->selectVisitById($id);

        $VisitForm->populate($data);
        
        if ($this->getRequest()->isPost()) {
            $formData = $this->_request->getPost();
            if ($VisitForm->isValid($formData))
            {
                
                
                 $visit_model = new Application_Model_Visit();
                 unset($formData['submit']);
                 $visit_model->editVisit($formData,$id);
                       $this->redirect("visit/view/id/".$id);     
                      
                      
            }
        }
        $this->view->visitform = $VisitForm;
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











