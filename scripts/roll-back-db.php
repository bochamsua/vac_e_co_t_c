<?php
require_once 'app/Mage.php';

umask(0);
Mage::app();

$storeId = '0';

Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);

$resource = Mage::getSingleton('core/resource');
$writeConnection = $resource->getConnection('core_write');
$readConnection = $resource->getConnection('core_read');

$tableUrl = $resource->getTableName('core/url_rewrite');
$tableProVarchar = $resource->getTableName('catalog_product_entity_varchar');

$helper = Mage::helper('backup');
$time = '1450793503';//file name
$name = 'moke';//backup name

try {
    $backupManager = Mage_Backup::getBackupInstance('db')
        ->setBackupExtension($helper->getExtensionByType('db'))
        ->setTime($time)
        ->setBackupsDir($helper->getBackupsDir())
        ->setName($name, false)
        ->setResourceModel(Mage::getResourceModel('backup/db'));

    $backupManager->rollback();

    $helper->invalidateCache()->invalidateIndexer();

   /* $adminSession = Mage::getSingleton('adminhtml/session');
    $adminSession->unsetAll();
    $adminSession->getCookie()->delete($adminSession->getSessionName());*/


    echo "Done";

}catch (Exception $e){
    echo $e->getMessage();
}









