<?php

class TestController extends Zend_Controller_Action
{

    protected $testModel = null;

    protected $base = null;

    public function init()
    {
        $this->testModel = new Application_Model_Test();
        $base = Zend_Controller_Front::getInstance()->getBaseUrl();
        
        echo "<h4><a href='".$base."/Test'>Test Page </a></h4>";
    }

    public function indexAction()
    {
        $testsStatistics = $this->testModel->getTestsStatistics();
        $this->view->testsStatistics = $testsStatistics;  
    }

    public function addAction()
    {
        $addTestForm = new Application_Form_AddTest();
        
        if ($this->_request->isPost()) {
            $formData = $this->_request->getPost();
            if ($addTestForm->isValid($formData)) {
                if($this->testModel->checkDuplication($formData['name'])) {
                    $addTestForm->populate($formData);
                    $addTestForm->markAsError();
                    $addTestForm->getElement("name")->addError("Name is used Before");
                }
                else {
                    $this->testModel->addTest($formData);
                    $this->_forward("list");
                }
            } else {
                $addTestForm->populate($formData);
            }
        }
        
        $this->view->form = $addTestForm;
    }

    public function deleteAction()
    {
        $id = $this->_request->getParam("id");
        
        if ($id) {
            $this->testModel->deleteTest($id);        
            $this->_forward("list");
        }
        else {
            $this->render("search");
        }
    }

    public function listAction()
    {
        $this->view->tests = $this->testModel->getAllTests();  
    }

    public function editAction()
    {
        $addTestForm = new Application_Form_AddTest();
        $id = $this->_request->getParam("id");
        
        if ($this->_request->isPost()) {
            $formData = $this->_request->getPost();
            
            if ($addTestForm->isValid($formData)) {
                if($this->testModel->checkDuplication($formData['name'])) {
                    $addTestForm->populate($formData);
                    $addTestForm->markAsError();
                    $addTestForm->getElement("name")->addError("Name is used Before");
                }
                else {
                    $editData = array('name'=>$formData['name']);
                    $this->testModel->editTest($id,$editData);
                    $this->_forward("list");
                }
            } else {
                $addTestForm->populate($formData);
            }
        }
        else {    
            if ($id) {
                $test = $this->testModel->viewTest($id);
                
                if ($test) {
                    $formData = array('testId'=>$test[0]['id'], 'name'=> $test[0]['name'], 'submit'=> "Edit");
                    $addTestForm->setName("Edit Test :");
                    $addTestForm->populate($formData); 
                }
                else {
                    $this->render("search");
                }
            }
            else {
                $this->render("search");
            }
        }
        
        $this->view->form = $addTestForm;
    }

    public function viewAction()
    {
        $id = $this->_request->getParam("id");
        
        if ( $id ) {
            $test = $this->testModel->viewTest($id);
            $this->view->test = $test;
        }
        else {
            $this->render("search");
        }    
    }

    public function searchAction()
    {
        $key = $this->_request->getParam("key");
        
        if ($key) {
            $this->view->key = $key;
            $this->view->tests = $this->testModel->searchByName("%".$key."%");
        }
    }


}













