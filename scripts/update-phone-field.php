<?php
require_once '../app/Mage.php';


umask(0);
Mage::app();

$storeId = '0';

Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);



$resource = Mage::getSingleton('core/resource');
$writeConnection = $resource->getConnection('core_write');
$readConnection = $resource->getConnection('core_read');

$customer = Mage::getModel('customer/customer')->getCollection()->addAttributeToSelect('*');
if($customer->count()){
    try {
        $i=1;
        foreach ($customer as $item) {
            $phone = $item->getPhone();

            if(!$phone){
                $item->setPhone('?')->save();
                echo "Done {$i}<br> \n ";
                $i++;
            }
        }
    }catch (Exception $e){
        echo $e->getMessage();
    }
}










