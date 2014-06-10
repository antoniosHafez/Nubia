<?php

class Access_Controller_Plugin_ACL extends Zend_Controller_Plugin_Abstract {
 
    public function preDispatch(Zend_Controller_Request_Abstract $request) {
        $objAuth = Zend_Auth::getInstance();
	$clearACL = false;
 
	$role = 'admin';
        
        $moduleName = strtolower($request->getModuleName());
        $controllerName = strtolower($request->getControllerName());
        $actionName= strtolower($request->getActionName());
            
        if($objAuth->hasIdentity()) {
            $arrUser = $objAuth->getIdentity();
            $role = $arrUser['userType'];

            $sess = new Zend_Session_Namespace('Nubia_ACL');
            if($sess->clearACL) {
                $clearACL = true;
                unset($sess->clearACL);
            }

            $objAcl = Access_ACL_Factory::get($objAuth,$clearACL);

            if(!$objAcl->isAllowed($role, $moduleName .'::' .$controllerName .'::' .$actionName)) {
                echo "Enta ".$role." : Not Allowed To view this page !!";
                exit;
            }

        } else {

            $objAcl = Access_ACL_Factory::get($objAuth,$clearACL);
            
            if(!$objAcl->isAllowed($role, $moduleName .'::' .$controllerName .'::' .$actionName)) {
                echo "Enta ".$role." : You need to login first !!";
                exit;
            }
        }      
    }
}
