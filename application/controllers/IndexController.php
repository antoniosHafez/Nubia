<?php

class IndexController extends Zend_Controller_Action
{

    private $type = null;
    private $name = null;
    private $ns = null;

    public function init()
    {
        $this->ns = new Zend_Session_Namespace("Zend_Auth");
        $authorization = Zend_Auth::getInstance();
        if (!$authorization->hasIdentity()) {
            $this->_redirect("User/signin");
        }
        
        //$this->view->userType = $this->s->storage->type;
        $this->type = "gp";
        $this->name = "Omar";
        $this->view->userType = "gp";
    }

    public function indexAction()
    {
        $this->view->check = "haa3";
        
        if($this->type == "gp") {
            $this->render("index-clinic");
            //echo "[ Welcome GP:: ".$this->name." ]";
            $this->_forward("list", "visit");
        }
        else if($this->type == "physician") {
            $this->render("index-physician");
            echo "[ Welcome Other ]";
        }
        else if($this->type == "admin") {
            $this->render("index-admin");
            echo "[ Welcome Other ]";
        }
        else {
            echo "ahlan ya geust";
        }
    }
    


}



