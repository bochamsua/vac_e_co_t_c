<?php
chdir(dirname(__FILE__));
require_once '../app/Mage.php';

umask(0);
Mage::app();

$storeId = '0';

Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);


$helper = Mage::helper('bs_traininglist/docx');

$file = '/Applications/AMPPS/www/vaeco/media/files/8004_TOEFA-REPORT.docx';
$helper->convertFile($file, 'html');









