<?php

require_once 'app/Mage.php';

umask(0);
Mage::app();

$storeId = '0';

Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);


$resource = Mage::getSingleton('core/resource');
$writeConnection = $resource->getConnection('core_write');
$readConnection = $resource->getConnection('core_read');


$curriculums    = Mage::getModel('bs_traininglist/curriculum')->getCollection()->addAttributeToFilter('status', 1)->addAttributeToFilter('c_history', array(array('eq'=>0),array('null'=>true)))->addAttributeToFilter('c_code', array('nlike'=>'%-OW%'));
$i=1;
foreach ($curriculums as $c) {
    $curriculum = Mage::getModel('bs_traininglist/curriculum')->load($c->getId());


    $code = $curriculum->getCCode();

    //get subjects
    $totalSubHour = 0;
    $err = "";
    $subjects = Mage::getModel('bs_subject/subject')->getCollection()->addFieldToFilter('curriculum_id', $curriculum->getId());
    if(!$subjects->count()){
        echo $code." doesn't have any subject.<br>\n";

    }


}









