<?php

class Access_ACL_Resources {
 
   private $arrModules = array();
   private $arrControllers = array();
   private $arrActions = array();
   private $arrIgnore = array('.','..','.svn');
 
   public function __get($strVar) {
      return ( isset($this->$strVar) ) ? $this->$strVar : null;
   }
 
   public function __set($strVar, $strValue) {
      $this->$strVar = $strValue;
   }	
 
   public function writeToDB() {
      $this->checkForData();
      $resourceModel = new Application_Model_Resources();
      $resourceModel->truncate();
      foreach( $this->arrModules as $strModuleName ) {
         if( array_key_exists( $strModuleName, $this->arrControllers ) ) {
              
            foreach( $this->arrControllers[$strModuleName] as $strControllerName ) {
               if( array_key_exists( $strControllerName, $this->arrActions[$strModuleName] ) ) {
                  foreach( $this->arrActions[$strModuleName][$strControllerName] as $strActionName ) {
                     $name = $strActionName.$strControllerName;
                     
                     $strActionName = strtolower($strActionName);
                     $strControllerName = strtolower($strControllerName);
                     $resourceModel->addResource($strModuleName, $strControllerName, $strActionName,$name);
                  }
               }
            }
         }
       }
       return $this;
   }
 
   private function checkForData() {
      if ( count($this->arrModules) < 1 ) { echo 'No modules found.'; exit;}
      if ( count($this->arrControllers) < 1 ) { echo 'No Controllers found.'; exit;}
      if ( count($this->arrActions) < 1 ) { echo 'No Actions found.'; exit;}
   }
 
   public function buildAllArrays() {
      $this->arrModules = array('default');
      $this->buildControllerArrays();
      $this->buildActionArrays();
      return $this;
   }
 
 
   public function buildControllerArrays() {
            $strModuleName = "default";
            $datControllerFolder = opendir(APPLICATION_PATH . '/controllers' );
            while ( ($dstFile = readdir($datControllerFolder) ) !== false ) {
               if( ! in_array($dstFile, $this->arrIgnore)) {
                  if( preg_match( '/Controller/', $dstFile) ) { $this->arrControllers[$strModuleName][] = substr( $dstFile,0,-14 ) ; }
               }
            }
            closedir($datControllerFolder);
   }
 
   public function buildActionArrays() {
      if( count($this->arrControllers) > 0 ) {
         foreach( $this->arrControllers as $strModule => $arrController ) {
            foreach( $arrController as $strController ) {
               $strClassName = ucfirst( $strController . 'Controller' );
 
               if( ! class_exists( $strClassName ) ) {
                  Zend_Loader::loadFile(APPLICATION_PATH . '/controllers/'.ucfirst( $strController ).'Controller.php');
               }
 
               $objReflection = new Zend_Reflection_Class( $strClassName );
               $arrMethods = $objReflection->getMethods();
               foreach( $arrMethods as $objMethods ) {
                  if( preg_match( '/Action/', $objMethods->name ) ) {
                     $this->arrActions[$strModule][$strController][] = substr($this->_camelCaseToHyphens($objMethods->name),0,-6 );
                  }
               }
            }
         }
      }
   }
 
   private function _camelCaseToHyphens($string) {
      if($string == 'currentPermissionsAction') {$found = true;}
         $length = strlen($string);
         $convertedString = '';
         for($i = 0; $i <$length; $i++) {
            if(ord($string[$i]) > ord('A') && ord($string[$i]) < ord('Z')) {
               $convertedString .= '-' .strtolower($string[$i]);
            } else {
               $convertedString .= $string[$i];
            }
         }
         return strtolower($convertedString);
   }
}

