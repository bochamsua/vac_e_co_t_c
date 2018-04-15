<?php

require_once 'app/Mage.php';

umask(0);
Mage::app();

$storeId = '0';

Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);

$products = Mage::getModel('catalog/product')->getCollection();

$i=1;
foreach ($products as $product) {
    $pro = Mage::getModel('catalog/product')->load($product->getId());
    $pro->save();
    echo "Done $i \n <br>";
    $i++;
}





