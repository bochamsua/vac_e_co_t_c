<?php
chdir(dirname(__FILE__));
require_once '../app/Mage.php';

umask(0);
Mage::app();

$storeId = '0';

Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);

$helper = Mage::helper('backup');
$type = 'db';

try {
    $backupManager = Mage_Backup::getBackupInstance($type)
        ->setBackupExtension($helper->getExtensionByType($type))
        ->setTime(time())
        ->setBackupsDir($helper->getBackupsDir());

    $backupManager->setName($type);

    $backupManager->create();


    echo $helper->getCreateSuccessMessageByType($type);

}catch (Exception $e){
    echo $e->getMessage();
}









