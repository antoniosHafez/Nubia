<?php

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Nubia\Model\Group;          // <-- Add this import
use Nubia\Form\Group;     

class GroupController extends Zend_Controller_Action
{

    
    
    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // action body
    }

    public function addAction()
    {
        // action body
        
        $form = new GroupForm();
        $form->get('submit')->setValue('Add');        
        $request = $this->getRequest();
        if ($request->isPost()) {
            $group = new Group();
            $form->setInputFilter($group->getInputFilter());
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $group->exchangeArray($form->getData());
                $this->getGroupTable()->saveGroup($group);
                
        return $this->redirect()->toRoute('group');
            }
        }
        return array('form' => $form);
        
    }
    
    
    public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('group', array(
                'action' => 'add'
            ));
        }
        $group = $this->getGroupTable()->getGroup($id);

        $form  = new GroupForm();
        $form->bind($group);
        $form->get('submit')->setAttribute('value', 'Edit');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($group->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $this->getGroupTable()->saveGroup($form->getData());

                // Redirect to list of albums
                return $this->redirect()->toRoute('group');
            }
        }

        return array(
            'id' => $id,
            'form' => $form,
        );
    }
    
            
        }
    
        
