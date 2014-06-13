<?php

class Access_Controller_Plugin_Visit extends Zend_Controller_Plugin_Abstract
{
   public function preDispatch(Zend_Controller_Request_Abstract $request)
   {
       
        $visitModel = new Application_Model_Visit();
        $auth = Zend_Auth::getInstance();
        $userInfo = $auth->getIdentity();
        
        if($userInfo['userType'] == 'physician')
            $visitToday = $visitModel->selectVisitsByDate(date('Y-m-d'), $userInfo['userId']);
        else
            $visitToday = $visitModel->selectVisitsByDate(date('Y-m-d'));
        
        $layout = Zend_Layout::getMvcInstance();
        $view = $layout->getView();
        $view->visitToday = $visitToday;
   }
}