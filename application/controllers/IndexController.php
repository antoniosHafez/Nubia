<?php

class IndexController extends Zend_Controller_Action
{

    private $ns = null;

    private $type = null;

    private $name = null;
    private $id;
    private $physicianModel;
    private $groupId;

    public function init()
    {
        $authorization = Zend_Auth::getInstance();
        $authInfo = $authorization->getIdentity();
        $this->type = $authInfo['userType'];
        $this->name = $authInfo['name'];
        $this->userId = $authInfo['userId'];
        if($this->type == "physician") {
            $this->groupId = $authInfo['phys_group_id'];
        }
    }

    public function indexAction()
    {
        if($this->type == "clinician") {
            $this->render("index-clinic");

            $this->_forward("list", "visit");
        }
        else if($this->type == "physician") {
            $fullBaseUrl = $this->view->serverUrl() . $this->view->baseUrl();
            $visit = new Application_Model_Visit();
            
            $accvisits = $visit->getAcceptedVisitsPhysician($this->userId);
            $penvisits = $visit->getPendingVisitsPhysician($this->groupId);
            $previsits = $visit->getPreviousVisitsPhysician($this->userId);
            $this->physicianModel = new Application_Model_Physician();
            #$this->personModel = new Application_Model_Person();
            $base = Zend_Controller_Front::getInstance()->getBaseUrl();

            foreach ($accvisits as $accvisit) {
                $array_feed_item['id'] = $accvisit['id'];
                $array_feed_item['title'] = $accvisit["description"];
                $array_feed_item['start'] = $accvisit["created_date"]; //Y-m-d H:i:s format
                //$array_feed_item['end'] = $array_event['end']; //Y-m-d H:i:s format
                $array_feed_item['allDay'] = 0;
                $array_feed_item['color'] = 'green'; 
                $array_feed_item['borderColor'] = 'green';
               
                //You can also a CSS class: 
                $array_feed_item['className'] = 'pl_act_rood';

                $array_feed_item['url'] =  "/Nubia/public/physicianvisit/live/patientId/".$accvisit['patient_id']."/vid/".$accvisit['id']."/phyid/".$accvisit['physican_id']."";

                //Add this event to the full list of events:
                $array_feed_items[] = $array_feed_item;
           }

           foreach ($previsits as $previsit) {
               $array_feed_item['id'] = $previsit['id'];
               $array_feed_item['title'] = $previsit["description"];
               $array_feed_item['start'] = $previsit["created_date"]; //Y-m-d H:i:s format
               //$array_feed_item['end'] = $array_event['end']; //Y-m-d H:i:s format
               $array_feed_item['allDay'] = 0;
               $array_feed_item['color'] = 'grey'; 
               $array_feed_item['borderColor'] = 'grey';
               //You can also a CSS class: 
               $array_feed_item['className'] = 'pl_act_rood';

               $array_feed_item['url'] =  "";

               //Add this event to the full list of events:
               $array_feed_items[] = $array_feed_item;
           }



            $acceptedvisitJ = json_encode($array_feed_items);
            $this->view->acceptedvisitJ = $acceptedvisitJ;
            $this->view->pen = $penvisits;
            $this->view->phyId = $this->userId;
            $this->_helper->viewRenderer('index');
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

