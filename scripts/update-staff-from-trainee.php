<?php

require_once '../app/Mage.php';

umask(0);
Mage::app();

$storeId = '0';

Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);


$resource = Mage::getSingleton('core/resource');
$writeConnection = $resource->getConnection('core_write');
$readConnection = $resource->getConnection('core_read');


$groupId = 22;//Noi that

//Get all trainee in trainee table
$trainee = Mage::getModel('bs_trainee/trainee')->getCollection()->addAttributeToFilter('vaeco_id', array('notnull'=>true))->addAttributeToFilter('trainee_code', array('like'=>'%HV%'));
foreach ($trainee as $item) {
    $tn = Mage::getModel('bs_trainee/trainee')->load($item->getId());
    $vaecoId = $tn->getVaecoId();

    $name = $tn->getTraineeName();
    $name = explode(" ", $name);
    $firstName = $name[0];
    unset($name[0]);
    $lastName = implode(" ", $name);


    $position = 'Kỹ sư';
    $basic = $tn->getTraineeBasic();
    if(!$basic || $basic == 175){
        $position = 'Thợ';
    }


    //check staff
    $staff = Mage::getModel('customer/customer')->getCollection()->addAttributeToFilter('vaeco_id', $vaecoId)->getFirstItem();
    if(!$staff->getId()){//if not exist
        $customerModel = Mage::getModel('customer/customer');

        $customerModel->setFirstname($firstName);
        $customerModel->setLastname($lastName);
        $customerModel->setEmail($vaecoId.'@gmail.com');
        $customerModel->setVaecoId($vaecoId);
        $customerModel->setUsername('chưa có');
        $customerModel->setPhone($tn->getTraineePhone());
        $customerModel->setPosition($position);
        $customerModel->setDivision('');
        $customerModel->setDob($tn->getTraineeDob());

        $customerModel->setStaffFunction(null);
        $customerModel->setStaffBasic(null);


        $customerModel->setGroupId($groupId);

        $res = $customerModel->save();

        if($res){
            echo "Done {$i}. {$firstName} {$lastName} br> \n";
        }else {
            echo "Something weng wrong";
        }
    }
}







