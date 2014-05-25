<?php

class VisitController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        $VisitForm = new Application_Form_Visit();
         $request = $this->getRequest();

        if ($this->getRequest()->isPost()) {

            if ($VisitForm->isValid($request->getParams()))
            {
                 $date = $this->_request->getParam("date");
                $description = $this->_request->getParam("description");
                $patient = $this->_request->getParam("patient");
                $type = $this->_request->getParam("type");
                 $Gp = $this->_request->getParam("Gp");
                 $notes = $this->_request->getParam("notes");
                   $depandency = $this->_request->getParam("depandency");
                   $visit_model = new Application_Model_Visit();
                   $visit_model->addVisit($date, $description, $physican, $patient, $type, $notes, $Gp, $depandency);
       
       
               
            }
        }
        $this->view->visitform = $VisitForm;
    }

    public function addAction()
    {
        $uname = $this->_request->getParam("uname");
                $ugender = $this->_request->getParam("ugender");
                $birthdate = $this->_request->getParam("bdate");
                $email = $this->_request->getParam("uemail");
                 $privilege = $this->_request->getParam("uprivilege");
              
            
    }


}



