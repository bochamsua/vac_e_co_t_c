<?php

require_once 'app/Mage.php';

umask(0);
Mage::app();

$storeId = '0';

Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);




$resource = Mage::getSingleton('core/resource');
$writeConnection = $resource->getConnection('core_write');
$readConnection = $resource->getConnection('core_read');

$customer = $readConnection->fetchOne("SELECT COUNT(entity_id) FROM customer_entity");

echo "Staff: ".$customer." <br>";




$relates = $readConnection->fetchOne("SELECT COUNT(DISTINCT staff_id) FROM bs_staffinfo_training");
echo "Info: ".$relates." <br>";

//echo "<pre>";
//print_r($curs);










