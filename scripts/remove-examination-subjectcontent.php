<?php

require_once 'app/Mage.php';

umask(0);
Mage::app();

$storeId = '0';

Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);


$resource = Mage::getSingleton('core/resource');
$writeConnection = $resource->getConnection('core_write');
$readConnection = $resource->getConnection('core_read');


$curriculum    = Mage::getModel('bs_traininglist/curriculum')->getCollection()->addAttributeToFilter('c_code', array('like'=>'AMB%'));
$i=1;
foreach ($curriculum as $c) {
    //$curr = Mage::getModel('bs_traininglist/curriculum')->load($c->getId());

    $subjects = Mage::getModel('bs_subject/subject')->getCollection()->addFieldToFilter('curriculum_id', $c->getId());
    if($subjects->count()){
        foreach ($subjects as $sub) {

            $subcontents = Mage::getModel('bs_subject/subjectcontent')->getCollection()->addFieldToFilter('subject_id', $sub->getId());
            if($subcontents->count()){
                foreach ($subcontents as $subcon) {
                    $title = $subcon->getSubconTitle();
                    if(strpos("moke".strtolower($title), "examination")){
                        $subcon->delete();
                        echo "Done {$i}<br>";
                        $i++;
                    }
                }

            }

        }


    }





}


//echo "<pre>";
//print_r($curs);










