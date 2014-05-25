<?php

class Application_Model_Person extends Zend_DB_Table_Abstract
{
    protected $_name='visit_request';

function addPerson($name,$telephone,$mobile,$sex)
    {
          $row=$this->createRow();
          $row->date=$name;
          $row->description=$telephone;
          $row->physican_id=$mobile;
          $row->patient_id = $sex;
          $row->join_date = date("Y-m-d");
           $row->save();
    }

}

