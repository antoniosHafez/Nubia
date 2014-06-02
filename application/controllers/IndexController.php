<?php

class IndexController extends Zend_Controller_Action
{

    private $ns = null;

    private $type = null;

    private $name = null;

    public function init()
    {
        $authorization = Zend_Auth::getInstance();
        $authInfo = $authorization->getIdentity();
        $this->type = $authInfo['userType'];
        $this->name = $authInfo['name'];
    }

    public function indexAction()
    {
        if($this->type == "clinician") {
            $this->render("index-clinic");

            $this->_forward("list", "visit");
        }
        else if($this->type == "physician") {
            $this->render("index-physician");
        }
        else if($this->type == "admin") {
            $this->render("index-admin");
        }
    }
    

    public function aboutAction()
    {
        // action body
    }
}

