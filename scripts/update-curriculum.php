<?php

require_once 'app/Mage.php';

umask(0);
Mage::app();

$storeId = '0';

Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);


$resource = Mage::getSingleton('core/resource');
$writeConnection = $resource->getConnection('core_write');
$readConnection = $resource->getConnection('core_read');


$curriculum    = Mage::getModel('bs_traininglist/curriculum')->getCollection();
$i=1;
foreach ($curriculum as $c) {
    $curr = Mage::getModel('bs_traininglist/curriculum')->load($c->getId());
    $name = $curr->getCName();
    if(strpos("moke".$name, "In-flight Entertainment (IFE) –")){
        $name = str_replace("In-flight Entertainment (IFE) –", "IFE", $name);
        //$name = $name." Recurrent Training";
        $curr->setCName($name)->save();
        echo "Done {$i}<br>";
        $i++;
    }



}


//echo "<pre>";
//print_r($curs);










