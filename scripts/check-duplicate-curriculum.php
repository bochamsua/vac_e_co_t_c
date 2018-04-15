<?php

require_once '../app/Mage.php';

umask(0);
Mage::app();

$storeId = '0';

Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);



$instructors = Mage::getModel('bs_traininglist/curriculum')->getCollection()->addAttributeToFilter('c_history', 0);
foreach ($instructors as $instructor) {
    $ins = Mage::getModel('bs_traininglist/curriculum')->load($instructor->getId());
    $id = $ins->getId();
    $vaecoId = $ins->getCCode();
    $check = checkCurriculum($vaecoId, $id);
    if($check){
        echo "Duplicate curriculum found: ".$vaecoId."<br> \n";
    }
}




function checkCurriculum($code, $id = null){
    $curriculum = Mage::getModel('bs_traininglist/curriculum')->getCollection()->addAttributeToFilter('c_code', $code)->addAttributeToFilter('c_history', 0);

    if($id){
        $curriculum->addAttributeToFilter('entity_id', array('neq'=>$id));
    }
    $ins = $curriculum->getFirstItem();
    if($ins->getId()){
        return true;
    }

    return false;
}






