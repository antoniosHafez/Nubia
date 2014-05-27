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
        
        
        // action bodym
        $param=array('action'=>"add");
        $addPhysicianForm = new Application_Form_AddPhysician($param);
        $physicianModel = new Application_Model_Physician();
        $personModel= new Application_Model_Person();
        $userModel= new Application_Model_User();
        
        if ($this->_request->isPost()) {
            $formData = $this->_request->getPost();
            if ($addPhysicianForm->isValid($formData)) {
                    
                    $personData=array(
                        'name'=> $formData['name'],
                        'telephone'=> $formData['telephone'],
                        'mobile'=>$formData['mobile'],
                        'sex'=>$formData['sex'],
                        'join_date'=>  date("Y-m-d")
                            
                      
                    );
                    $personId = $personModel->addPerson($personData);
                   
                    
                    $physicianData = array(
                        'title' => $formData["title"],
                        'group_id'=> $formData["group_id"],
                        'id'=>$personId
                    );
                    
                    
                    
                    $physicianModel->addPhysician($physicianData);
                     $userData = array(
                        'email' => $formData["email"],
                        'password'=> md5($formData["password"]),
                        'id'=>$personId
                    );
                    $userModel->addUser($userData);
                    
                    $this->_forward("list");
                
            } 
        }
        
        $this->view->form = $addPhysicianForm;
        
    }

    public function deleteAction()
    {
        $id = $this->_request->getParam("id");
        
        if ($id) {
            #$del = $this->getRequest()->getPost('del');
            
             $physicianModel = new Application_Model_User();   
             $physician = $physicianModel->deleteUser($id);
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
         $param=array('action'=>"edit");
        $form = new Application_Form_AddPhysician($param);
        #$form->submit->setLabel('Save');
        
         $id = $this->_request->getParam("id");
        $phyModel = new Application_Model_Physician();
         $phyData = $phyModel->searchById($id);
        $personModel= new Application_Model_Person();
        $perData=$personModel->getPersonById($id);
        $userModel= new Application_Model_User();
         $userData=$userModel->getUserById($id);
          $formData=array(
            'name'=> $perData['name'],
             'telephone'=> $perData['telephone'],
             'mobile'=>$perData['mobile'],
           'sex'=>$perData['sex'], 
             'title' => $phyData["title"],
             'group_id'=> $phyData["group_id"],
            'email' => $userData["email"]
                       
        );
        $form->populate($formData);
        
       
        if ($this->_request->isPost()) {
            $formData = $this->_request->getPost();
            if ($form->isValid($formData)) {
                    
                    $personData=array(
                        'name'=> $formData['name'],
                        'telephone'=> $formData['telephone'],
                        'mobile'=>$formData['mobile'],
                        'sex'=>$formData['sex'],
                        'join_date'=>  date("Y-m-d")
                            
                      
                    );
                    $personId = $personModel->editPerson($personData,$id);
                   
                    
                    $physicianData = array(
                        'title' => $formData["title"],
                        'group_id'=> $formData["group_id"],
                        'id'=>$id
                    );
                    
                    
                    $phyModel->editPhysician($physicianData,$id);
                      if($formData["password"] == null || trim($formData["password"]) == "" )
                        {
                            $userData = array(
                        'email' => $formData["email"],
                         
                        
                        'id'=>$id
                    );            
                         }else
                         {
                              $userData = array(
                        'email' => $formData["email"],
                         
                        'password'=> md5($formData["password"]),
                        'id'=>$id
                    );
                         }
                    
                    
                    $userModel->editUser($userData,$id);
                    
                    $this->_forward("list");
                
            } 
        }
      
        
        $this->view->form = $form;
        
    }

    public function viewAction()
    {
        // action body
        
      $id = $this->_request->getParam("id");
        $phyModel = new Application_Model_Physician();
         $phyData = $phyModel->searchById($id);
        $personModel= new Application_Model_Person();
        $perData=$personModel->getPersonById($id);
        $userModel= new Application_Model_User();
         $userData=$userModel->getUserById($id);
        $this->view->phyData=$phyData;
        $this->view->perData=$perData;
        $this->view->userData=$userData;
        
        
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