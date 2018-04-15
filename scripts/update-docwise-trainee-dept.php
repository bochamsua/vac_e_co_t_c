<?php


require_once '../app/Mage.php';


umask(0);
Mage::app();

$storeId = '0';

Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);



//get all scores
$scores = Mage::getModel('bs_docwise/score')->getCollection()->addFieldToFilter('vaeco_id', array('like'=>'VAE%'));
foreach ($scores as $score) {
    $groupName = getDept(trim($score->getVaecoId()));
    if($groupName){
        $score->setDept($groupName)->save();

    }
}

//get all traineess
$trainees = Mage::getModel('bs_docwise/trainee')->getCollection()->addFieldToFilter('vaeco_id', array('like'=>'VAE%'));
foreach ($trainees as $trainee) {
    $groupName = getDept(trim($trainee->getVaecoId()));
    if($groupName){
        $trainee->setDept($groupName)->save();

    }
}

echo "Done....";


function getDept($vaecoId){

    $resource = Mage::getSingleton('core/resource');
    $writeConnection = $resource->getConnection('core_write');
    $readConnection = $resource->getConnection('core_read');

    $staffId = $readConnection->fetchOne("SELECT entity_id FROM customer_entity_varchar WHERE `value` = '{$vaecoId}' AND attribute_id = 133");
    if($staffId){
        $groupId = $readConnection->fetchOne("SELECT group_id FROM customer_entity WHERE entity_id = {$staffId}");
        if($groupId){
            $groupName = $readConnection->fetchOne("SELECT customer_group_name_vi FROM customer_group WHERE customer_group_id = {$groupId}");
            if($groupName){
                return $groupName;
            }
        }
    }
    return false;
}









