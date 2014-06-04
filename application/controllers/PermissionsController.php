<?php

class PermissionsController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        $roleModel = new Application_Model_Role();
        $this->view->fullRoles = $roleModel->getFullRoles();
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
            $roleModel = new Application_Model_Role();
            $this->view->fullRoles = $roleModel->getFullRoles();
        }  
    }


}



