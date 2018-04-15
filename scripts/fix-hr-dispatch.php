<?php

require_once '../app/Mage.php';

umask(0);
Mage::app();

$storeId = '0';

Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);


$resource = Mage::getSingleton('core/resource');
$writeConnection = $resource->getConnection('core_write');
$readConnection = $resource->getConnection('core_read');

$courses = Mage::getModel('catalog/product')->getCollection();
foreach ($courses as $c) {
    $course = Mage::getModel('catalog/product')->load($c->getId());
    $sku = $course->getSku();

    $no = 'moke'.strtolower($course->getHrDispatchNo());
    if(strpos($no, 'tin') || strpos($no, 'mess') || $no == 'moke'){//set to Message
        $course->setHrDisType(400);
        $course->setHrDispatchNo('');
    }else {
        $course->setHrDisType(399);
    }

    $otherNo = 'moke'.strtolower($course->getOtherDispatchNo());
    if(strpos($otherNo, 'tin') || strpos($otherNo, 'mess') || $otherNo == 'moke'){//set to Message
        $course->setOtherDisType(398);
        $course->setOtherDispatchNo('');
    }else {
        $course->setOtherDisType(397);
    }

    $course->save();

    echo "Done {$sku} \n <br>";

    //echo $course->getId().'--'.$course->getHrDispatchNo().'<br>';


}












