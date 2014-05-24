<?php
namespace Nubia\Form; 
class Application_Form_Group extends Zend_Form
{

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
        
    }
    public function __construct($name = null) {
        parent::__construct('group');
        $this->setAttribute('method','post');
        
        $this->add(array(
            'name'     =>'id',
            'attribute' =>array('type'=>'hidden',),
            ));
        
        $this->add(array('name'=>'groupname','attributes'=>array('type'=>'text',),
        'option' => array('label'=>'groupname',),));
        
        $this->add(array(
            'name'=>'submit',
            'attribute'=>array(
                'type'=>'submit',
                'value'=>'Go',
                'id'=>'submitbutton',
                
            ),
            
        ));
        
        
        
    }
    
    
    
}

