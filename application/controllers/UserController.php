<?php


class UserController extends Zend_Controller_Action
{
    private $type = null;
    private $name = null;
    private $ns = null;

    public function init()
    {
        $this->ns = new Zend_Session_Namespace("Zend_Auth");
        /*$authorization = Zend_Auth::getInstance();
        if (!$authorization->hasIdentity()) {
            $this->_redirect("index/index");
        }*/
        
        //$this->view->userType = $this->s->storage->type;
        $this->type = "gp";
        $this->name = "Omar";
        $this->view->userType = "gp";
        $this->generateResourcesAction();
    }

    public function indexAction()
    {
        if($this->type == "gp") {
            echo "[ Welcome GP:: ".$this->name." ]";
        }
        else {
            echo "[ Welcome Other ]";
        }
    }

    public function signinAction()
    {
        // action body
    }

    public function addAction()
    {
        $this->view->action = "/user/add";
        $userForm = new Application_Form_NewUserForm();
        $this->view->form = $userForm;
        if ($this->getRequest()->isPost()){
            if ($userForm->isValid($this->getRequest()->getParams())) {
                $userModel = new Application_Model_User();
                $personModel = new Application_Model_Person();
                $personData = array(
                    'name' => $this->getParam("name"),
                    'sex' => $this->getParam("sex"),
                    'telephone' => $this->getParam("telephone"),
                    'mobile' => $this->getParam("mobile")
                );
                $userId = $personModel->addPerson($personData);
                $userData = array(
                    'password' => md5($this->getParam("password")),
                    'email' => $this->getParam("email"),
                    'id' => $userId
                );
                $userModel->addUser($userData);
                $this->redirect("/user/list");

            }
        }
    }
    
    public function searchAction()
    {
        $this->view->form = new Application_Form_SearchUserEmail();
        if ($this->getRequest()->isPost()){
            $userEmail = $this->getParam("email");
            $userModel = new Application_Model_User();
            $userId = $userModel ->searchUserByEmail($userEmail);
            //echo $patientIDN;
            //echo $patientId["id"];
            $this->redirect("/user/edit/userId/".$userId["id"]."");
        } 
    }

    public function editAction()
    {
        $this->view->action = "/user/edit";
        $userForm = new Application_Form_NewUserForm();
        $this->view->form = $userForm;
        $userModel = new Application_Model_User();
        $personModel = new Application_Model_Person();       
        if($this->getRequest()-> isGet()){
            $userId = $this->getParam("userId");
            $this->view->userId = $userId;
            $userData = $userModel->getUserById($userId);
            $personData = $personModel->getPersonById($userId);
            $fullData = array_merge((array)$userData, (array)$personData); // or join
            $userForm->populate($fullData);
        }
        if ($this->getRequest()->isPost()){
            if ($userForm->isValid($this->getRequest()->getParams())){
                $userId = $this->getParam("userId");
                $personData = array(
                    'name' => $this->getParam("name"),
                    'sex' => $this->getParam("sex"),
                    'telephone' => $this->getParam("telephone"),
                    'mobile' => $this->getParam("mobile"),
                );
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
                $personModel->editPerson($personData, $userId);
                $userModel->editUser($userData, $userId);
                $this->redirect("/user/list");

            }
        }       
        $this->render('add');
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
            $personModel = new Application_Model_Person();
            $userModel -> deleteUser($userId);
            $personModel -> deletePerson($userId);
            $this->redirect("/user/list");
        }
    }

    public function signoutAction()
    {
        // action body
    }
    
    public function generateResourcesAction() {
        $autoloader = Zend_Loader_Autoloader::getInstance();
        $autoloader->registerNamespace('Access_');
        
        $objResources = new Access_ACL_Resources();
        $objResources->buildAllArrays();
        $objResources->writeToDB();
        
        $this->_forward("signin");
    }



}















