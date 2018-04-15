<?php
chdir(dirname(__FILE__));

require_once '../app/Mage.php';

umask(0);
Mage::app();

$storeId = '0';

Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);

$resource = Mage::getSingleton('core/resource');
$writeConnection = $resource->getConnection('core_write');
$readConnection = $resource->getConnection('core_read');

$tableUrl = $resource->getTableName('core/url_rewrite');
$tableProVarchar = $resource->getTableName('catalog_product_entity_varchar');


try {
    //Truncate
    $writeConnection->query("DELETE FROM {$tableUrl} WHERE request_path LIKE '%virtual%'");
    $writeConnection->query("DELETE FROM `log_url`");
    $writeConnection->query("DELETE FROM `log_url_info`");
    $writeConnection->query("DELETE FROM `log_visitor`");
    $writeConnection->query("DELETE FROM `log_visitor_info`");
    $writeConnection->query("DELETE FROM `report_event`");
    $writeConnection->query("DELETE FROM `report_viewed_product_index`");

    //Remove all virtual url key
    $writeConnection->query("DELETE FROM {$tableProVarchar} WHERE attribute_id in (97,98) AND `value` LIKE '%virtual%'");

    //Reindex url
    $processId = 3;//url rewrite
    $process = Mage::getModel('index/process')->load($processId);
    $process->reindexEverything();

    echo  "Done";
}catch (Exception $e){
    echo $e->getMessage();
}









