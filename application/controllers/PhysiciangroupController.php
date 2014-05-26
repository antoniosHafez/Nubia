<?php

class PhysiciangroupController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
        
        $this->physiciangroupModel = new Application_Model_Physiciangroup();
        $this->physicianModel = new Application_Model_Physician();
        
        $base = Zend_Controller_Front::getInstance()->getBaseUrl();
        
        echo "<h4><a href='".$base."/Physiciangroup'>Physiciangroup Page</a><h4>";  
        
    }

    public function indexAction()
    {
        // action body
        /*
        $physiciangroupStatistics = $this->physiciangroupModel->getPhysiciansgroupStatistics();
        $this->view->physiciansgroupStatistics = $physiciansgroupStatistics;  
        */
        
                
        $this->physiciangroupModel = new Application_Model_Physician();
        
        $base = Zend_Controller_Front::getInstance()->getBaseUrl();
        
        echo "<h4><a href='".$base."/Physician'>Physician group Page</a><h4>";  
              }
        
    

    public function addAction()
    {
        // action body

        $addPhysiciangroupForm = new Application_Form_Addphysiciangroup();
        $physiciangroupModel = new Application_Model_Physiciangroup();
        
        if ($this->_request->isPost()) {
            $formData = $this->_request->getPost();
            if ($addPhysiciangroupForm->isValid($formData)) {
                if($this->physiciangroupModel->checkDuplication($formData['name'])) {
                    $addPhysicianForm->populate($formData);
                    $addPhysicianForm->markAsError();
                    $addPhysicianForm->getElement('name')->addError("Name is used Before");
                }
                else {
                    
                    
                    $physiciangroupData = array(
                        'name' => $formData["name"]
                        
                    );
                    
                    
                    
                    $physiciangroupModel->addPhysiciangroup($physiciangroupData);
                    

                    
                    $this->_forward("list");
                }
            } else {
              
            }
        }
        
        $this->view->form = $addPhysiciangroupForm;
        
    }

    public function deleteAction()
    {
        // action body
        
        $id = $this->_request->getParam("id");
        
        if ($id) {
            
            
            $physiciangroupModel = new Application_Model_Physiciangroup();   
              $physiciangroup = $physiciangroupModel->deletePhysiciangroup($id);
               
                $this->_forward("list");    

                       
            
        }
        else {
             $this->render("search");
             $this->view->physician = $physician->getPhysician($id);
             
            
        }
        
    }

    public function listAction()
    {
        // action body
        
        $this->view->physiciangroup = $this->physiciangroupModel->getAllPhysiciansgroup();  
        
    }

    public function editAction()
    {
        // action body
        
        
        $form = new Application_Form_Addphysiciangroup();
        
         $id = $this->_request->getParam("id");
        $phyGroupModel = new Application_Model_Physiciangroup();
        $formData = $phyGroupModel->searchById($id);
       
        $form->populate($formData);
        $this->view->form = $form;
        if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();
            if ($form->isValid($formData)) {
                $id = (int)$form->getValue('id');
                $physiciangroupName = $form->getValue('name');
                $data= array("name"=>$physiciangroupName);
                $physician = new Application_Model_Physiciangroup();
                $physician->editPhysiciangroup($id,$data);
                                $this->_forward("list");    

            } else {
                
            }
        } 
        
        
        
    }

    public function viewAction()
    {
        // action body
        
         $id = $this->_request->getParam("id");
        
        if ( $id ) {
            $physiciangroup = $this->physiciangroupModel->viewPhysician($id);
            $this->view->physiciangroup = $physiciangroup;
        }
        else {
            $this->render("search");
        }    
        
        
    }

    public function searchAction()
    {
        // action body
 
        
        $key = $this->_request->getParam("key");
        
        if ($key) {
            $this->view->key = $key;
            $this->view->physiciangroup = $this->physiciangroupModel->searchByTitle("%".$key."%");
        }
        
        
        
    }


}








