<?php

require_once 'app/Mage.php';

umask(0);
Mage::app();

$storeId = '0';

Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);


$trainees = Mage::getModel('bs_docwise/trainee')->getCollection();

foreach ($trainees as $tn) {
    $trainee = Mage::getModel('bs_docwise/trainee')->load($tn->getId());
    $vaecoId = trim($trainee->getVaecoId());
    $traineeName = trim($trainee->getTraineeName());

    $cus = Mage::getModel('customer/customer')->getCollection()->addAttributeToFilter('vaeco_id', $vaecoId)->getFirstItem();
    if($cus->getId()){
        $customer = Mage::getModel('customer/customer')->load($cus->getId());

        $customerVaecoId = trim($customer->getVaecoId());
        $customerName = trim($customer->getName());

        if($vaecoId == $customerVaecoId && $customerName != $traineeName){

            $trainee->setTraineeName($customerName)->save();
            echo "Change:".$traineeName." to ".$customerName."\n";//.$customerVaecoId."(".$vaecoId.")";
        }


    }

}














