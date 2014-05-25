<?php

class Application_Form_Visit extends Zend_Form  
{

    public function init()
    {
          $this->setMethod('post');
             $this->addElement('text', 'date', array('label' => 'visit request date :', 'required' => true));
        $this->addElement('textarea', 'description', array('label' => 'description :', 'required' => true, 'filters' => array('StringTrim')));
         //
         $physican = new Zend_Form_Element_Select('physican', array('multiple' => false ));
            $physican->setMultiOptions(array('user' => 'user','admin'=>'administrator'));
           $physican->setOrder(6);
           $physican->setLabel("Choose physican");
            $this->addElement($physican);
        //
          $this->addElement('select', 'patient', array('label' => 'patient :', 'required' => true, 'filters' => array('StringTrim')));
           $this->addElement('select', 'type', array('label' => 'type :', 'required' => true, 'filters' => array('StringTrim')));
            $this->addElement('textarea', 'notes', array('label' => 'notes :', 'required' => true, 'filters' => array('StringTrim')));
             $this->addElement('select', 'Gp', array('label' => 'Gp :', 'required' => true, 'filters' => array('StringTrim')));
              $this->addElement('select', 'depandency', array('label' => 'depandency :', 'required' => true, 'filters' => array('StringTrim')));
              
        $this->addElement('submit', 'submitbtn', array('ignore'=> true,'label'=> 'Add New visit'));
    }


}

