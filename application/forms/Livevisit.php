<?php

class Application_Form_Livevisit extends Zend_Form
{

    public function init()
    {
        
          $dissearch = new Zend_Form_Element_Text('dissearch');
       $dissearch->setAttrib('onkeyup', "HandleKey('disease')");
       $dissearch->setAttrib("value", null);
       $dissearch->setAttrib('class', 'searchtext');
        $dissearch->setAttrib('onkeyup', "autocomp('disease')");
       $this->addElement($dissearch);
       
       $disAdd = new Zend_Form_Element_Button('disAdd');
      $disAdd->setAttrib('class', 'searchbtn');
      $disAdd->setValue("Add");
       $disAdd->setAttrib('onclick', "addtobox('disease')");
       $this->addElement($disAdd);
       
       
        $disease_id = new Zend_Form_Element_Select('disease_id');
        $disease_id
        ->setAttrib("hidden", "true");
         $this->addElement($disease_id);
        
        //listbox
        $disbox = new Zend_Form_Element_Multiselect("disbox");
        $disbox->setAttrib('class', 'box');
        $this->addElement($disbox);
        //
        
        /////
         $medsearch = new Zend_Form_Element_Text('medsearch');
       $medsearch->setAttrib('onkeyup', "autocomp('medication')");
         $medsearch->setAttrib('class', 'searchtext');
    
       $this->addElement($medsearch);
       
       
        
       
       
       $medAdd = new Zend_Form_Element_Button('medAdd');
      $medAdd->setAttrib('class', 'searchbtn');
      $medAdd->setValue("Add");
       $medAdd->setAttrib('onclick', "addtobox('medication')");
       $this->addElement($medAdd); 
       
       
        
       
       
         $medication_id = new Zend_Form_Element_Select('medication_id');
        $medication_id->setAttrib("hidden", "true")
                
        ->setRegisterInArrayValidator(false);
        $this->addElement($medication_id);
        
       
        
         $medbox = new Zend_Form_Element_Multiselect("medbox");
        $medbox->setAttrib('class', 'box');
        $this->addElement($medbox);
       
       
        ////
       $radsearch = new Zend_Form_Element_Text('radsearch');
      // $radsearch->setAttrib('onkeyup', "HandleKey('radiation')");
       $radsearch->setAttrib("value", null);
       $radsearch->setAttrib('class', 'searchtext');
        $radsearch->setAttrib('onkeyup', "autocomp('radiation')");
       $this->addElement($radsearch);
       
       $radAdd = new Zend_Form_Element_Button('radAdd');
      $radAdd->setAttrib('class', 'searchbtn');
      $radAdd->setValue("Add");
       $radAdd->setAttrib('onclick', "addtobox('radiation')");
       $this->addElement($radAdd);
       
        ////////
        $radiation_id = new Zend_Form_Element_Select('radiation_id');
        $radiation_id->setAttrib("hidden", "true");
        $this->addElement($radiation_id);
              
        
        
        
       
        
        //listbox
        $radbox = new Zend_Form_Element_Multiselect("radbox");
               $radbox->setAttrib('class', 'box');

        $this->addElement($radbox);
        //
        ////////
          $vitsearch = new Zend_Form_Element_Text('vitsearch');
       $vitsearch->setAttrib('onkeyup', "HandleKey('vital')");
       $vitsearch->setAttrib("value", null);
        $vitsearch->setAttrib('onkeyup', "autocomp('vital')");
        $vitsearch->setAttrib('class', 'searchtext');
    
       $this->addElement($vitsearch);
       
        $vitAdd = new Zend_Form_Element_Button('vitAdd');
      $vitAdd->setAttrib('class', 'searchbtn');
      $vitAdd->setValue("Add");
       $vitAdd->setAttrib('onclick', "addtobox('vital')");
       $this->addElement($vitAdd);
        
         $vital_id = new Zend_Form_Element_Select('vital_id');
        $vital_id
        ->setAttrib("hidden", "true");
        $this->addElement($vital_id);
        
           $vitbox = new Zend_Form_Element_Multiselect("vitbox");
                   $vitbox->setAttrib('class', 'box');

        $this->addElement($vitbox);
      
        //////////
          $testsearch = new Zend_Form_Element_Text('testsearch');
       $testsearch->setAttrib('onkeyup', "HandleKey('test')");
       $testsearch->setAttrib("value", null);
        $testsearch->setAttrib('onkeyup', "autocomp('test')");
       $testsearch->setAttrib('class', 'searchtext');
    
       $this->addElement($testsearch);
       
       $testAdd = new Zend_Form_Element_Button('testAdd');
      $testAdd->setAttrib('class', 'searchbtn');
      $testAdd->setValue("Add");
       $testAdd->setAttrib('onclick', "addtobox('test')");
       $this->addElement($testAdd);
        
         $test_id = new Zend_Form_Element_Select('test_id');
        $test_id
       ->setAttrib("hidden", "true");
        $this->addElement($test_id);
        
           $testbox = new Zend_Form_Element_Multiselect("testbox");
                   $testbox->setAttrib('class', 'box');

        $this->addElement($testbox);
      /////
        $sursearch = new Zend_Form_Element_Text('sursearch');
       $sursearch->setAttrib('onkeyup', "HandleKey('surgery')");
       $sursearch->setAttrib("value", null);
        $sursearch->setAttrib('onkeyup', "autocomp('surgery')");
        $sursearch->setAttrib('class', 'searchtext');
    
       $this->addElement($sursearch);
       
       $surAdd = new Zend_Form_Element_Button('surAdd');
      $surAdd->setAttrib('class', 'searchbtn');
      $surAdd->setValue("Add");
       $surAdd->setAttrib('onclick', "addtobox('surgery')");
       $this->addElement($surAdd); 
       
         $surgery_id = new Zend_Form_Element_Select('surgery_id');
        $surgery_id
        ->setAttrib("hidden", "true");
        $this->addElement($surgery_id);
        
         $surbox = new Zend_Form_Element_Multiselect("surbox");
                 $surbox->setAttrib('class', 'box');

        $this->addElement($surbox);
       
       /////////
       
   $this->addElement('textarea', 'notes', array('label' => 'notes :', 'required' => false, 'filters' => array('StringTrim')));
  
         $this->addElement('submit', 'submit', array('ignore'=> true,'label'=> 'Finish Visit'));
    }


}

