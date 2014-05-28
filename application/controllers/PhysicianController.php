<?php

class PhysicianController extends Zend_Controller_Action
{

    #protected $physicianModel = null;

   #protected $base = null;

    public function init()
    {
       $this->physicianModel = new Application_Model_Physician();
        $this->physicianModel = new Application_Model_Physician();
        
        $base = Zend_Controller_Front::getInstance()->getBaseUrl();
        
        echo "<h4><a href='".$base."/Physician'>Physician Page</a><h4>";  
        

    }

    public function indexAction()
    {
        // action 
    
        //                
        //                
        //                
        $this->physicianModel = new Application_Model_Physician();
        #$this->personModel = new Application_Model_Person();
        $base = Zend_Controller_Front::getInstance()->getBaseUrl();
        
        echo "<h4><a href='".$base."/Physician'>Physician Page</a><h4>";  
                        
     #   $physicianStatistics = $this->physicianModel->getPhysiciansStatistics();
      #  $this->view->physiciansStatistics = $physiciansStatistics;  
    }

    public function addAction()
    {
        // action body
        
        
        // action body
        $addPhysicianForm = new Application_Form_AddPhysician();
        $physicianModel = new Application_Model_Physician();
        #$personModel= new Application_Model_Person();
        if ($this->_request->isPost()) {
            $formData = $this->_request->getPost();
            if ($addPhysicianForm->isValid($formData)) {
                if($this->physicianModel->checkDuplication($formData['name'])) {
                    $addPhysicianForm->populate($formData);
                    $addPhysicianForm->markAsError();
                    $addPhysicianForm->getElement('name')->addError("Name is used Before");
                }
                else {
                    
                    $personData=array(
                        'name'=> $formData['name'],
                        'telephone'=> $formData['telephone'],
                        'mobile'=>$formData['mobile'],
                        'sex'=>$formData['sex']
                            
                      
                    );
                    $personId = $personModel->addPerson($personData);
                   
                    
                    $physicianData = array(
                        'title' => $formData["title"],
                        'group_id'=> 1,
                        
                    );
                    
                    
                    
                    $physicianModel->addPhysician($physicianData);
                    

                    
                    $this->_forward("list");
                }
            } else {
                $addPhysicianForm->populate($formData);
            }
        }
        
        $this->view->form = $addPhysicianForm;
        
    }

    public function deleteAction()
    {
        $id = $this->_request->getParam("id");
        
        if ($id) {
            #$del = $this->getRequest()->getPost('del');
            
             $physicianModel = new Application_Model_Physician();   
             $physician = $physicianModel->deletePhysician($id);
             $this->_forward("list");
           
        }
         else{       
                
                $this->render("search");
                $this->view->physician = $physician->getPhysician($id);
          #$person = $this->personModel -> deletePerson($id);
                   

                    
            
        }
       /* else {
             $this->render("search");
             $this->view->physician = $physician->getPhysician($id);
             $this->view->person = $person->getPerson($id);
   
            
            
            
           
        }*/
    }

    public function listAction()
    {
        // action body
        $this->view->physician = $this->physicianModel->getAllPhysicians();  
    }

    public function editAction()
    {
        // action body
        $form = new Application_Form_AddPhysician();
        #$form->submit->setLabel('Save');
        
         $id = $this->_request->getParam("id");
        $phyModel = new Application_Model_Physician();
         $formData = $phyModel->searchById($id);
        $form->populate($formData);
        
        $this->view->form = $form;
        
        if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();
            if ($form->isValid($formData)) {
                $id = (int)$form->getValue('id');
                $physicianName = $form->getValue('name');
                $physicianTitle = $form->getValue('title');
                $physicianGender = $form->getValue('sex');
                $physicianTelephone = $form->getValue('telephone');
                $physicianMobile = $form->getValue('mobile');
                $groupName = $form->getValue('name');
                
                $data= array("name"=>$physicianName,"title"=>$physicianTitle ,"gender"=>$physicianGender, "gender"=>$physicianGender, "mobile"=>$physicianMobile );
                #$data2= array("title"=>$physicianTitle);
                #$data3= array("gender"=>$physicianGender);
                #$data4= array("telephone"=>$physicianTelephone);
                #$data5= array("mobile"=>$physicianMobile);
                #$data6= array("name"=>$groupName);
                
                
                $physician = new Application_Model_Physician();
                 $physician->editPhysician($id,$data);
                                $this->_forward("list");    

            }else {
                #$form->populate($formData);
            }
        } 
        
        
    }

    public function viewAction()
    {
        // action body
        
        $id = $this->_request->getParam("id");
        
        if ( $id ) {
            $physician = $this->physicianModel->viewPhysician($id);
            $this->view->physician = $physician;
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
            $this->view->physician = $this->physicianModel->searchByTitle("%".$key."%");
        }
    }   
    
}