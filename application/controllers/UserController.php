<?php


class UserController extends Zend_Controller_Action
{

    protected $userType;
    protected $groupId;
    protected $userId;
    protected $countItems = 8;

    public function init()
    {
        $auth = Zend_Auth::getInstance();
        $userInfo = $auth->getIdentity();
        $this->userId = $userInfo["userId"];
        $this->userType = $userInfo["userType"];
        if($this->userType == "physician")
            $this->groupId = $userInfo["phys_group_id"];
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
                   if($userType == "physician"){
                        $physicianModel = new Application_Model_Physician();
                        $physicianData = $physicianModel->getPhyisicanGroup($id);
                        $session->write(array('userId'=>$id, 'email'=>$email, 'name'=>$name, 'userType'=>$userType, 'phys_group_id'=>$physicianData['group_id']));
                   }else{
                        $session->write(array('userId'=>$id, 'email'=>$email, 'name'=>$name, 'userType'=>$userType));
                   }
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
        $allUsers = $userModel->listUsers();
        
        $paginator = Zend_Paginator::factory($allUsers);
        $paginator->setItemCountPerPage($this->countItems);
        $pageNumber = $this->getRequest()->getParam("page");
        $paginator->setCurrentPageNumber($pageNumber);

        $this->view->paginator = $paginator;
        $this->view->users = $allUsers;
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

        $auth = Zend_Auth::getInstance();
        $userInfo = $auth->getIdentity();
        
        //if ($this->hasParam("userId")) {
            $userId = $this->getParam("id");
        //}
        $userForm = new Application_Form_UserProfile($userId);
        $physicianForm = new Application_Form_AddPhysician(array('action' => "edit"));
        $userModel = new Application_Model_User();
        $physicianModel = new Application_Model_Physician();
        $personModel = new Application_Model_Person();
                           
        if ($userId == $userInfo['userId']) {
            if ($this->getRequest()->isGet()) {
                $userData = $userModel->getUserById($userId);
                $physicianData = $physicianModel->searchById($userId);
                
                if($this->hasParam("editProfile")){
                    if($userData['role'] == "admin" || $userData['role'] == "clinician"){
                        $this->view->adminClinUser = 1;
                        $this->view->userId = $userId;
                        $this->view->form = $userForm;
                        $userForm->populate($userData);
                        $this->render("edit-profile");
                    }
                    if($userData['role'] == "physician"){                        
                        $this->view->physUser = 1;
                        $this->view->userId = $userId;
                        $this->view->form = $userForm;
                        $this->view->physForm = $physicianForm;
                        $userForm->populate($userData);
                        $physicianForm->populate($physicianData);
                        $this->render("edit-profile");
                    }
                }else{
                    $this->view->userData = $userData;
                    if($userData['role'] == "physician"){
                        $this->view->physUser = 1;
                        $this->view->physData = $physicianData;                        
                    }
                }
                
            }
            if($this->getRequest()->isPost()){
                $userFormValid = FALSE;
                $physicianFormValid = FALSE;
                if ($userForm->isValid($this->getRequest()->getParams())) {
                    $userFormValid = TRUE;
                    if($this->getParam("type") == "physician"){
                       if($physicianForm->isValid($this->getRequest()->getParams())){
                           $physicianFormValid = TRUE;
                           $physicianData = array(
                               'title' => $this->getParam("title"),
                               'group_id' => $this->getParam("group_id")                                                              
                           );
                           $physUpdate = $physicianModel->editPhysician($physicianData, $userId);
                       }else{
                           $physicianFormValid = FALSE;
                       }                      
                   }//if type phys
                    if($this->getParam("password") != ""){
                        $userData = array(
                            'email' => $this->getParam("email"),
                            'password' => md5($this->getParam("password"))
                        );                  
                    }else{
                        $userData = array(
                            'email' => $this->getParam("email")
                        );
                    }
                    $personData = array(
                        'name' => $this->getParam("name"),
                        'sex' => $this->getParam("sex"),
                        'telephone' => $this->getParam("telephone"),
                        'mobile' => $this->getParam("mobile")
                    );
                    $personModel->editPerson($personData, $userId);
                    $userModel->editUser($userData, $userId);
               }else{
                   $userFormValid = FALSE;
               }
                if(!$physicianFormValid || !$userFormValid) {
                    $userData = $userModel->getUserById($userId);
                    $this->view->userId = $userId;
                    //$this->view->form = $userForm;
                    if($userData['role'] == "physician"){
                        $this->view->physForm = $physicianForm;
                        $this->view->form = $userForm;
                        $this->view->physUser = 1;
                    }else{
                        $this->view->form = $userForm;
                        $this->view->adminClinUser = 1;
                    }
                    $this->render("edit-profile");
                }else{
                    $this->redirect("user/show-Profile/userId/".$userId."");
                }
            }
        } 
    }

    public function showProfileAction() 
    {
        $auth = Zend_Auth::getInstance();
        $userInfo = $auth->getIdentity();
        
        //if ($this->hasParam("userId")) {
            $userId = $this->getParam("userId");
        //}
        $userForm = new Application_Form_UserProfile($userId);
        $physicianForm = new Application_Form_AddPhysician(array('action' => "edit"));
        $userModel = new Application_Model_User();
        $physicianModel = new Application_Model_Physician();
        $personModel = new Application_Model_Person();
                           
        if ($userId == $userInfo['userId']) {
            if ($this->getRequest()->isGet()) {
                $userData = $userModel->getUserById($userId);
                $physicianData = $physicianModel->searchById($userId);
                
                if($this->hasParam("editProfile")){
                    if($userData['role'] == "admin" || $userData['role'] == "clinician"){
                        $this->view->adminClinUser = 1;
                        $this->view->userId = $userId;
                        $this->view->form = $userForm;
                        $userForm->populate($userData);
                        $this->render("edit-profile");
                    }
                    if($userData['role'] == "physician"){                        
                        $this->view->physUser = 1;
                        $this->view->userId = $userId;
                        $this->view->form = $userForm;
                        $this->view->physForm = $physicianForm;
                        $userForm->populate($userData);
                        $physicianForm->populate($physicianData);
                        $this->render("edit-profile");
                    }
                }else{
                    $this->view->userData = $userData;
                    if($userData['role'] == "physician"){
                        $this->view->physUser = 1;
                        $this->view->physData = $physicianData;                        
                    }
                }
                
            }
            if($this->getRequest()->isPost()){
                $userFormValid = FALSE;
                $physicianFormValid = FALSE;
                if ($userForm->isValid($this->getRequest()->getParams())) {
                    $userFormValid = TRUE;
                    if($this->getParam("type") == "physician"){
                       if($physicianForm->isValid($this->getRequest()->getParams())){
                           $physicianFormValid = TRUE;
                           $physicianData = array(
                               'title' => $this->getParam("title"),
                               'group_id' => $this->getParam("group_id")                                                              
                           );
                           $physUpdate = $physicianModel->editPhysician($physicianData, $userId);
                       }else{
                           $physicianFormValid = FALSE;
                       }                      
                   }//if type phys
                    if($this->getParam("password") != ""){
                        $userData = array(
                            'email' => $this->getParam("email"),
                            'password' => md5($this->getParam("password"))
                        );                  
                    }else{
                        $userData = array(
                            'email' => $this->getParam("email")
                        );
                    }
                    $personData = array(
                        'name' => $this->getParam("name"),
                        'sex' => $this->getParam("sex"),
                        'telephone' => $this->getParam("telephone"),
                        'mobile' => $this->getParam("mobile")
                    );
                    $personModel->editPerson($personData, $userId);
                    $userModel->editUser($userData, $userId);
               }else{
                   $userFormValid = FALSE;
               }
                if(!$physicianFormValid || !$userFormValid) {
                    $userData = $userModel->getUserById($userId);
                    $this->view->userId = $userId;
                    //$this->view->form = $userForm;
                    if($userData['role'] == "physician"){
                        $this->view->physForm = $physicianForm;
                        $this->view->form = $userForm;
                        $this->view->physUser = 1;
                    }else{
                        $this->view->form = $userForm;
                        $this->view->adminClinUser = 1;
                    }
                    $this->render("edit-profile");
                }else{
                    $this->redirect("user/show-Profile/userId/".$userId."");
                }
            }
        } 
    }

    public function notificationAction()
    {
            
        if($this->userType  == "admin")
        {
            $adminNotification = new Application_Model_AdminNotification();
            $rows = $adminNotification->getNotification();
            
            $paginator = Zend_Paginator::factory($rows);
            $paginator->setItemCountPerPage($this->countItems);
            $pageNumber = $this->getRequest()->getParam("page");
            $paginator->setCurrentPageNumber($pageNumber);
            
            $this->view->paginator = $paginator;
            $this->view->adminNotis = $rows;
        }
        else
        {
            if($this->userType == "clinician")
            {
                $clinicNotification = new Application_Model_ClinicianNotification();
                $rows = $clinicNotification->getNotification();
                
                $paginator = Zend_Paginator::factory($rows);
                $paginator->setItemCountPerPage($this->countItems);
                $pageNumber = $this->getRequest()->getParam("page");
                $paginator->setCurrentPageNumber($pageNumber);

                $this->view->paginator = $paginator;
                $this->view->clinicNotis = $rows;
            }
            else if($this->userType == "physician")
            {
                $physNotifications = array();
                //$groupNotifications = array();
                $groupNewNotifications = array();
                $physNotifModel = new Application_Model_PhysicianNotification();
                $physicianModel = new Application_Model_Physician();
                $rows = $physNotifModel->getNotificationsByGroupId($this->groupId);
                foreach ($rows as $row){
                    if($row["physician_id"]){
                        //$group_id = $physicianModel->getPhyisicanGroup($row["physician_id"]);
                        //if($group_id["group_id"] == $this->groupId){
                            if($row["physician_id"] == $this->userId)
                                array_push($physNotifications,$row);
                        //}else{
                          //  array_push($groupNotifications,$row);
                        //}
                    }else{
                        //array_push($groupNewNotifications,$row);
                        array_push($physNotifications,$row);
                    }
                }
                //print_r($physNotifications);
                //exit;
               
                $paginator = Zend_Paginator::factory($physNotifications);
                $paginator->setItemCountPerPage($this->countItems);
                $pageNumber = $this->getRequest()->getParam("page");
                $paginator->setCurrentPageNumber($pageNumber);

                $this->view->paginator = $paginator;
                $this->view->physNotis = $physNotifications;
            }
        }        
    }
}

