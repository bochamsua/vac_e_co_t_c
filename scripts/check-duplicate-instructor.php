<?php

require_once '../app/Mage.php';

umask(0);
Mage::app();

$storeId = '0';

Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);



$instructors = Mage::getModel('bs_instructor/instructor')->getCollection();
foreach ($instructors as $instructor) {
    $ins = Mage::getModel('bs_instructor/instructor')->load($instructor->getId());
    $id = $ins->getId();
    $vaecoId = $ins->getIvaecoid();
    $check = checkInstructor($vaecoId, $id);
    if($check){
        echo "Duplicate instructor found: ".$vaecoId."<br> \n";
    }
}




function checkInstructor($vaecoId, $id = null){
    $instructor = Mage::getModel('bs_instructor/instructor')->getCollection()->addAttributeToFilter('ivaecoid', $vaecoId);

    if($id){
        $instructor->addAttributeToFilter('entity_id', array('neq'=>$id));
    }
    $ins = $instructor->getFirstItem();
    if($ins->getId()){
        return true;
    }

    return false;
}






