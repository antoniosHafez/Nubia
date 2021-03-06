<?php

class PermissionsController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        $roleResourceModel = new Application_Model_RoleResources();
        $this->view->fullRoles = $roleResourceModel->getFullPermissions();
        $this->view->roles  = Application_Model_Role::getAll();
    }

    public function editAction()
    {
        if($this->getRequest()->isPost()) {
           $params = $this->getRequest()->getParams();
           
           $roleResourceModel = new Application_Model_RoleResources();
           $roleResourceModel->addRoleResources($params);
           
           $this->_forward("index");
        }
        else {
            $roleModel = new Application_Model_RoleResources();
            $this->view->fullRoles = $roleModel->getFullPermissions();
            $this->view->roles  = Application_Model_Role::getAll();
        }  
    }

    public function generateresourcesAction()
    {
        $autoloader = Zend_Loader_Autoloader::getInstance();
        $autoloader->registerNamespace('Access_');
        
        $objResources = new Access_ACL_Resources();
        $objResources->buildAllArrays();
        $objResources->writeToDB();
        
        $this->_forward("index");
    }


}





