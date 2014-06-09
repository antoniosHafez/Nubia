<?php

class RolesController extends Zend_Controller_Action
{

    protected $roleModel = null;

    protected $base = null;

    public function init()
    {
        $this->roleModel = new Application_Model_Role();
        $base = Zend_Controller_Front::getInstance()->getBaseUrl();
    }

    public function indexAction()
    {
        $rolesStatistics = $this->roleModel->getRolesStatistics();
        $this->view->rolesStatistics = $rolesStatistics;  
    }

    public function addAction()
    {
        $addRoleForm = new Application_Form_AddType();
        
        if ($this->_request->isPost()) {
            $formData = $this->_request->getPost();
            if ($addRoleForm->isValid($formData)) {
                if($this->roleModel->checkDuplication(0,$formData['typeName'])) {
                    $addRoleForm->populate($formData);
                    $addRoleForm->markAsError();
                    $addRoleForm->getElement("typeName")->addError("Name is used Before");
                }
                else {
                    $this->roleModel->addRole($formData);
                    $this->_forward("list");
                }
            } else {
                $addRoleForm->populate($formData);
            }
        }
        
        $this->view->form = $addRoleForm;
    }

    public function deleteAction()
    {
        $id = $this->_request->getParam("id");
        
        if ($id) {
            $this->roleModel->deleteRole($id);        
            $this->_forward("list");
        }
        else {
            $this->render("search");
        }
    }

    public function listAction()
    {
        $this->view->roles = $this->roleModel->getAllRoles();  
    }

    public function editAction()
    {
        $addRoleForm = new Application_Form_AddType();
        $id = $this->_request->getParam("id");
        
        if ($this->_request->isPost()) {
            $formData = $this->_request->getPost();
            
            if ($addRoleForm->isValid($formData)) {
                if($this->roleModel->checkDuplication($id, $formData['typeName'])) {
                    $addRoleForm->populate($formData);
                    $addRoleForm->markAsError();
                    $addRoleForm->getElement("typeName")->addError("Name is used Before");
                }
                else {
                    $editData = array('name'=>$formData['typeName']);
                    $this->roleModel->editRole($id,$editData);
                    $this->_forward("list");
                }
            } else {
                $addRoleForm->populate($formData);
            }
        }
        else {    
            if ($id) {
                $role = $this->roleModel->viewRole($id);
                
                if ($role) {
                    $formData = array('typeId'=>$role[0]['id'], 'name'=> $role[0]['typeName'], 'submit'=> "Edit");
                    $addRoleForm->setName("Edit Role :");
                    $addRoleForm->populate($formData); 
                }
                else {
                    $this->render("search");
                }
            }
            else {
                $this->render("search");
            }
        }
        
        $this->view->form = $addRoleForm;
    }

    public function viewAction()
    {
        $id = $this->_request->getParam("id");
        
        if ( $id ) {
            $role = $this->roleModel->viewRole($id);
            $this->view->role = $role;
        }
        else {
            $this->render("search");
        }    
    }


}









