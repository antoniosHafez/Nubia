<?php

class VisitController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
         $action =array("type"=>"add");
        $VisitForm = new Application_Form_Visit($action);
         $request = $this->getRequest();

        if ($this->getRequest()->isPost()) {

            if ($VisitForm->isValid($request->getParams()))
            {
                 $date = $this->_request->getParam("date");
                $description = $this->_request->getParam("description");
                $patient = $this->_request->getParam("patient");
                $physican = $this->_request->getParam("physican");
                $type = $this->_request->getParam("type");
                 $Gp = $this->_request->getParam("Gp");
                 $notes = $this->_request->getParam("notes");
                   $depandency = $this->_request->getParam("depandency");
                 $visit_model = new Application_Model_Visit();
                 $id=$visit_model->addVisit($date, $description, $physican, $patient, $type, $notes, $Gp, $depandency);
                       $this->redirect("visit/view/id".$id);     
       
               
            }
        }
        $this->view->visitform = $VisitForm;
    }

    public function addAction()
    {
       
    }

    public function listAction()
    {
         $visit_model = new Application_Model_Visit();
         $this->view->visits=$visit_model->listVisit();
    }

    public function editAction()
    {
         $action =array("type"=>"add");
        $VisitForm = new Application_Form_Visit($action);
         $request = $this->getRequest();

        if ($this->getRequest()->isPost()) {

            if ($VisitForm->isValid($request->getParams()))
            {
                 $date = $this->_request->getParam("date");
                $description = $this->_request->getParam("description");
                $patient = $this->_request->getParam("patient_id");
                $physican = $this->_request->getParam("physican_id");
                $type = $this->_request->getParam("type");
                 $Gp = $this->_request->getParam("gp_id");
                 $notes = $this->_request->getParam("notes");
                   $depandency = $this->_request->getParam("depandency");
                   $id = $this->_request->getParam("id");
                   $visit_model = new Application_Model_Visit();
                   
       $action =array(
           "date"=>$date,
           "description"=>$description,
           "physican_id"=>$physican,
           "patient_id"=>$patient,
           "type"=>$type,
           "gp_id"=>$Gp,
           "notes"=>$notes,
           "depandency"=>$depandency
               );
       $visit_model->editVisit($action,$id);
          $this->redirect("visit/view/id".$id);     
            }
        }
        $id = $this->_request->getParam("id");
        $visit_model = new Application_Model_Visit();
        $data=$visit_model->selectVisitById($id);
        
         $VisitForm->populate($data);
        $this->view->visitform = $VisitForm;
       
    }

    public function deleteAction()
    {
                   $visit_model = new Application_Model_Visit();
                   $id = $this->_request->getParam("id");
                   $visit_model->deleteVisit($id);
                    
         $this->redirect('visit/list/');
                   
    }

    public function viewAction()
    {
         $visit_model = new Application_Model_Visit();
                   $id = $this->_request->getParam("id");
                  $this->view->visit= $visit_model->selectVisitById($id);
        
    }


}











