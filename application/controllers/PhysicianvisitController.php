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


}









