<?php

class VisitController extends Zend_Controller_Action
{
    protected $visitModel = null;

    public function init()
    {
        /* Initialize action controller here */
        $this->visitModel = new Application_Model_Visit();
    }

    public function indexAction()
    {
       
    }

    public function addAction()
    {
        $param =array("action"=>"add");
        if($this->_request->getParam("id"))
            $param =array("action"=>"add","patientGP" => "add-gp");
        
        $VisitForm = new Application_Form_Visit($param);
         $request = $this->getRequest();

        if ($this->getRequest()->isPost()) {

            if ($VisitForm->isValid($request->getParams())) {
                $date = $this->_request->getParam("date");
                $description = $this->_request->getParam("description");
                $patient = $this->_request->getParam("patient_id");
                //return error
                $physican = $this->_request->getParam("physican_id");
                $type = $this->_request->getParam("type");
                
               $group_id = $this->_request->getParam("group_id");
                $notes = $this->_request->getParam("notes");
                  //BySession =======>  $Gp = $this->_request->getParam("Gp");
                $depandency = $this->_request->getParam("depandency");
                
                $visit_model = new Application_Model_Visit();
                $id = $visit_model->addVisit($date, $description, NULL,$group_id, $patient, $type, $notes, 8, $depandency);
                $this->redirect("visit/view/id" . $id);
               
            }
        }
        else
        {
            $patientID = $this->_request->getParam("id");
                $values = array(
                    "patient_id" => $patientID
                        );
                $VisitForm->populate($values);
        }
        $this->view->visitform = $VisitForm;
    }

    public function listAction()
    {
         $visit_model = new Application_Model_Visit();
         $this->view->visits=$visit_model->listVisit();
    }

    public function editAction()
    {
        $action = array("action" => "edit");
        $VisitForm = new Application_Form_Visit($action);

        $id = $this->_request->getParam("id");
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
    }

    public function deleteAction() {
        $visit_model = new Application_Model_Visit();
        $id = $this->_request->getParam("id");
        $visit_model->deleteVisit($id);
    }

    public function viewAction()
    {
        $data = $this->_request->getParams();
        if($data["id"])
        {
            $this->view->visit= $this->visitModel->selectVisitById($data["id"]);
        }
        else
        {
            if($data["patientid"])
            {
                $this->view->visits = $this->visitModel->selectVisitByPatientID($data["patientid"]);
            }
        }
        $this->redirect('visit/list/');
    }
    
    
}
