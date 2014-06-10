<?php


class UserController extends Zend_Controller_Action
{

    public function init()
    {
    }

    public function indexAction()
    {
        
    }

    public function signinAction()
    {
        
        $signinForm = new Application_Form_Sign();
        
        if($this->getRequest()->isPost()){
            $formData = $this->_request->getPost();
            if ($signinForm->isValid($formData)) {

               $db = Zend_Db_Table::getDefaultAdapter();

               $checkdata = new Zend_Auth_Adapter_DbTable(
                   $db,
                   'user',
                   'email',
                   'password'
                   );

               $checkdata->setIdentity($formData['email']);
               $checkdata->setCredential(md5($formData['password']));

               $result = $checkdata->authenticate();

               if ($result->isValid()) {
                   $auth = Zend_Auth::getInstance();
                   $session = $auth->getStorage();

                   $id= $checkdata->getResultRowObject('id')->id;
                   $email= $checkdata->getResultRowObject('email')->email;
                   $roleId= $checkdata->getResultRowObject('role_id')->role_id;

                   $personModel = new Application_Model_Person();
                   $person = $personModel->getPersonById($id); 
                   $name = $person['name'];

                   $roleModel = new Application_Model_Role();
                   $userType = $roleModel->getUserType($roleId);

                   $session->write(array('userId'=>$id, 'email'=>$email, 'name'=>$name, 'userType'=>$userType));

                   $sess = new Zend_Session_Namespace('Nubia_ACL');
                   $sess->clearACL = TRUE;

                   $this->_redirect('/');   
               }
               else {
                   $signinForm->getElement("email")->addError("E-Mail or Password is incorrect !");
               }
           }
        }
        $this->view->form = $signinForm;      
    }

    public function addAction()
    {
        //$this->view->action = "/user/add";
        $userForm = new Application_Form_NewUserForm(0);
        $physicianForm = new Application_Form_AddPhysician(array('action'=>"add"));
        $this->view->form = $userForm;
        $this->view->physForm = $physicianForm;
        if ($this->getRequest()->isPost()){
            if ($userForm->isValid($this->getRequest()->getParams())) {
                $userModel = new Application_Model_User();
                $personModel = new Application_Model_Person();
                $rolesModel = new Application_Model_Role();
                $roleName = $rolesModel -> getUserType($this->getParam("role_id"));
                $personData = array(
                    'name' => $this->getParam("name"),
                    'sex' => $this->getParam("sex"),
                    'telephone' => $this->getParam("telephone"),
                    'mobile' => $this->getParam("mobile"),
                    'join_date' => date("Y-m-d"),
                    'status' => 'Active',
                    'type' => $roleName
                );
                $userId = $personModel->addPerson($personData);
                $userData = array(
                    'password' => md5($this->getParam("password")),
                    'email' => $this->getParam("email"),
                    'role_id' => $this->getParam("role_id"),
                    'id' => $userId
                );                
                $userModel->addUser($userData);
                
                if($this->getParam("title") != NULL && $this->getParam("group_id") != NULL){
                    $physicianData = array(
                        'title' => $this->getParam("title"),
                        'group_id' => $this->getParam("group_id"),
                        'id' => $userId
                    );
                    $physicianModel = new Application_Model_Physician();
                    $physicianModel ->addPhysician($physicianData);
                }
                
                
                $this->redirect("/user/list");

            }
        }
    }

    public function searchAction()
    {
        $this->view->form = new Application_Form_SearchUserEmail();
        $userModel = new Application_Model_User();
       /* $choice = "view/";
        if($this->hasParam("delete")){
            $choice = "delete/";
            echo $choice;
        }else if($this->hasParam("edit")){
            $choice = "edit/";
            echo $choice;
        }
        $this->view->choice = $choice;*/
        if ($this->getRequest()->isPost()){
            $userEmail = $this->getParam("email");
            $userRole = $this->getParam("role");
            /*if($userRole == "all" && //type on session is admin){
                $userData = $userModel ->dminSearchUsersByEmailRole($userEmail);
            }else{}
            */
            $userData = $userModel ->searchUsersByEmailRole($userEmail, $userRole); //if return is null show msg
            if($userData){
                $this->view->userData = $userData;
            }else{
                $this->view->dataNotFound = 1;
            }
            //$this->redirect("/user/".$choice."userId/".$userData["id"]."");
        }
        
    }

    public function editAction()
    {                
        $userModel = new Application_Model_User();
        $personModel = new Application_Model_Person();
        $physicianModel = new Application_Model_Physician();
        
        $userId = $this->getParam("userId");
        $physicianForm = new Application_Form_AddPhysician(array('action' => "edit"));
             
        $userForm = new Application_Form_NewUserForm($userId);
        if($this->getRequest()-> isGet()){
            $this->view->userId = $userId; 
            $this->view->form = $userForm; 
            
            
            $this->view->physForm = $physicianForm;
            
            $userData = $userModel->getUserById($userId);
            $personData = $personModel->getPersonById($userId);
            $fullData = array_merge((array)$userData, (array)$personData); // or join
            $userForm->populate($fullData);
            
            if($userData["role"] == "physician"){ 
                $physicianData = $physicianModel->searchById($userId);
                $physicianForm->populate($physicianData);
            }
        } 
        
        if ($this->getRequest()->isPost()){
            $physicianValid = TRUE;
            $personValid = TRUE;
            if ($userForm->isValid($this->getRequest()->getParams())){
                $userId = $this->getParam("userId");
                
                $rolesModel = new Application_Model_Role();
                $roleName = $rolesModel -> getUserType($this->getParam("role_id"));
                $roleId = $userModel->getRoleIdByUserId($userId);
                $oldRoleName = $rolesModel -> getUserType($roleId['role_id']);
                
                $personData = array(
                    'name' => $this->getParam("name"),
                    'sex' => $this->getParam("sex"),
                    'telephone' => $this->getParam("telephone"),
                    'mobile' => $this->getParam("mobile"),
                    'type' => $roleName
                );

                if($oldRoleName == "physician" && $roleName != $oldRoleName) {
                    
                    $physicianModel->deletePhysician($userId);
                } 
                else {
                    
                    if($roleName == "physician") {
                        $physicianData = array(
                            'title' => $this->getParam("title"),
                            'group_id' => $this->getParam("group_id"),
                            'id' => $userId
                        );
                        if($physicianForm->isValid($physicianData)) {
                            if($oldRoleName == "physician") { 
                                $physicianModel ->editPhysician($physicianData, $userId);
                            }
                            else {
                                $physicianModel = new Application_Model_Physician();
                                $physicianModel ->addPhysician($physicianData);
                            }   
                        }
                        else {
                            $physicianValid = FALSE;
                        }
                    }  
                }
                
                if($physicianValid) {
                    if($this->getParam("password") != ""){
                        $userData = array(
                            'email' => $this->getParam("email"),
                            'role_id' => $this->getParam("role_id"),
                            'password' => md5($this->getParam("password"))
                        );                  
                    }else{
                        $userData = array(
                            'email' => $this->getParam("email"),
                            'role_id' => $this->getParam("role_id"),
                        );
                    }
                    $personModel->editPerson($personData, $userId);
                    $userModel->editUser($userData, $userId);
                    $this->redirect("/user/list");
                }
            }
            else {
                
                $personValid = FALSE;

            }
            if(!$physicianValid || !$personValid) {
                $this->view->userId = $userId; 
                $this->view->form = $userForm; 
                $this->view->physForm = $physicianForm;
            }
        }
    }

    public function listAction()
    {
        $userModel = new Application_Model_User();
        $this->view->users = $userModel->listUsers();
    }

    public function deleteAction()
    {
        if ($this->getRequest()->isGet()){
            $userId = $this->getRequest()->getParam("userId");
            $userModel = new Application_Model_User();
            $userModel -> deleteUser($userId);
            $this->redirect("/user/list");
        }
    }

    public function signoutAction()
    {
            $authorization = Zend_Auth::getInstance();
            $authorization->clearIdentity();
            
            $sess = new Zend_Session_Namespace('Nubia_ACL');
            $sess->clearACL = TRUE;
            $this->_redirect('/');
    }

    public function generateResourcesAction()
    {
        $autoloader = Zend_Loader_Autoloader::getInstance();
        $autoloader->registerNamespace('Access_');
        
        $objResources = new Access_ACL_Resources();
        $objResources->buildAllArrays();
        $objResources->writeToDB();
        
        $this->_forward("signin");
    }

    public function viewAction()
    {
        $userModel = new Application_Model_User();
        if($this->getRequest()-> isGet()){
            $userId = $this->getParam("userId");
            $this->view->userData = $userModel->getUserById($userId);
        }
    }

    public function showProfileAction() 
    {
        $auth = Zend_Auth::getInstance();
        $userInfo = $auth->getIdentity();

        if ($this->hasParam("userId")) {
            $userId = $this->getParam("userId");
        }
        if ($userId == $userInfo['userId']) {        
            if ($this->getRequest()->isGet()) {
                $userModel = new Application_Model_User();
                $userData = $userModel->getUserById($userId);

                if ($this->hasParam("editProfile")) {
                    $userForm = new Application_Form_UserProfile();
                    $this->view->form = $userForm;
                    $this->view->userId = $userId;
                    $userForm->populate($userData);
                    if ($userData["role"] == "physician") {
                        $physicianForm = new Application_Form_AddPhysician(array('action' => "edit"));
                        $this->view->physForm = $physicianForm;
                        $physicianModel = new Application_Model_Physician();
                        $physicianData = $physicianModel->searchById($userId);
                        $physicianForm->populate($physicianData);
                        $this->render("edit-profile");
                    }
                } else {//editP
                    $this->view->userData = $userData;
                }
            }

            if ($this->getRequest()->isPost()) {
                if ($userForm->isValid($this->getRequest()->getParams())) {
                    $userId = $this->getParam("userId");
                    $rolesModel = new Application_Model_Role();
                    $roleName = $rolesModel->getUserType($this->getParam("role_id"));
                    $personData = array(
                        'name' => $this->getParam("name"),
                        'sex' => $this->getParam("sex"),
                        'telephone' => $this->getParam("telephone"),
                        'mobile' => $this->getParam("mobile"),
                        'type' => $roleName
                    );
                    if ($this->getParam("password") != "") {
                        $userData = array(
                            'email' => $this->getParam("email"),
                            'role_id' => $this->getParam("role_id"),
                            'password' => md5($this->getParam("password"))
                        );
                    } else {
                        $userData = array(
                            'email' => $this->getParam("email"),
                            'role_id' => $this->getParam("role_id"),
                        );
                    }
                    $personModel->editPerson($personData, $userId);
                    $userModel->editUser($userData, $userId);
                    //$this->redirect("/user/list");
                }
            }
        } else {
            echo "Permissions Denied";
        }        
    }

        
    public function notificationAction()
    {
        $adminNotification = new Application_Model_AdminNotification();
        $rows = $adminNotification->getNotification();
        
        $this->view->noti = $rows;
        
    }
}















