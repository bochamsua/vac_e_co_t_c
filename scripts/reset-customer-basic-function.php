<?php


require_once 'app/Mage.php';


umask(0);
Mage::app();

$storeId = '0';

Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);



$resource = Mage::getSingleton('core/resource');
$writeConnection = $resource->getConnection('core_write');
$readConnection = $resource->getConnection('core_read');


$res = $writeConnection->query("DELETE FROM customer_entity_int WHERE attribute_id in (141,142)");
if($res){
    echo "done";
}else {
    echo "something went wrong!";
}
/*
 *
TRUNCATE  bstaffs;
ALTER TABLE  bstaffs AUTO_INCREMENT = 1;
 */










