<?php

require_once '../app/Mage.php';

umask(0);
Mage::app();

$storeId = '0';

Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);



$instructors = Mage::getModel('bs_tasktraining/taskinstructor')->getCollection();
foreach ($instructors as $instructor) {

    $id = $instructor->getId();
    $vaecoId = $instructor->getVaecoId();
    $check = checkInstructor($vaecoId, $id);
    if($check){
        echo "Duplicate instructor found: ".$vaecoId."<br> \n";
    }
}

echo 'Done';




function checkInstructor($vaecoId, $id = null){
    $instructor = Mage::getModel('bs_tasktraining/taskinstructor')->getCollection()->addFieldToFilter('vaeco_id', $vaecoId);

    if($id){
        $instructor->addFieldToFilter('entity_id', array('neq'=>$id));
    }
    $ins = $instructor->getFirstItem();
    if($ins->getId()){
        return true;
    }

    return false;
}






