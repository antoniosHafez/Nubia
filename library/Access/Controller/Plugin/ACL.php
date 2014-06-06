<?php

class Access_Controller_Plugin_ACL extends Zend_Controller_Plugin_Abstract {
 
    public function preDispatch(Zend_Controller_Request_Abstract $request) {
        $objAuth = Zend_Auth::getInstance();
	$clearACL = false;
 
	$role = 'guest';
        
        $moduleName = strtolower($request->getModuleName());
        $controllerName = strtolower($request->getControllerName());
        $actionName= strtolower($request->getActionName());
        
        $sess = new Zend_Session_Namespace('Nubia_ACL'); 
        $sess->prePage = $controllerName."/".$actionName;
        
        if($objAuth->hasIdentity()) {
            $arrUser = $objAuth->getIdentity();
            $role = $arrUser['userType'];

            

            if($sess->clearACL) {
                $clearACL = true;
                unset($sess->clearACL);
            }
            
            $objAcl = Access_ACL_Factory::get($objAuth,$clearACL);
            
            if(!$objAcl->isAllowed($role, $moduleName .'::' .$controllerName .'::' .$actionName)) {
                $request->setModuleName("default");
                $request->setControllerName("Error");
                $request->setActionName("noauth");
            }

        } else {

            $objAcl = Access_ACL_Factory::get($objAuth,$clearACL);
            
            if(!$objAcl->isAllowed($role, $moduleName .'::' .$controllerName .'::' .$actionName)) {
                $request->setModuleName("default");
                $request->setControllerName("user");
                $request->setActionName("signin");
            }
        }      
    }
}
