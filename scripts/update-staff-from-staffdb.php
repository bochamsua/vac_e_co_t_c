<?php

require_once '../app/Mage.php';

umask(0);
Mage::app();

$storeId = '0';

Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);


$resource = Mage::getSingleton('core/resource');
$writeConnection = $resource->getConnection('core_write');
$readConnection = $resource->getConnection('core_read');

$staff = $readConnection->fetchAll("Select * from staff WHERE StaffID LIKE '%VAE%'");



$i=1;
foreach ($staff as $item) {
    $vaecoId = $item['StaffID'];

    $name = $item['FullName'];
    $name = explode(" ", $name);
    $firstName = $name[0];
    unset($name[0]);
    $lastName = implode(" ", $name);

    $dob = $item['BirthDay'];
    $jointDate = $item['JoinDate'];

    $sex = $item['Sex'];
    $gender = 1;
    if($sex == 'Female'){
        $gender = 2;
    }
    $division = $item['MyDepartment'];

    $idNumber = $item['IDNumber'];
    $idPlace = $item['IDGrantedBy'];
    $idDate = $item['IDGrantedDate'];

    $pob = $item['BirthPlace'];
    $address = $item['ResidencePlace'];

    $phone = $item['PhoneNumber'];

    $position = $item['Position'];

    $groupId = 27;
    $dept = (int)$item['MyDivision'];
    if($dept > 0){
        $groupId = $dept;
    }


    $staff = Mage::getModel('customer/customer')->getCollection()->addAttributeToFilter('vaeco_id', $vaecoId)->getFirstItem();
    if(!$staff->getId()){//if not exist
        $customerModel = Mage::getModel('customer/customer');

        $customerModel->setFirstname($firstName);
        $customerModel->setLastname($lastName);
        $customerModel->setEmail($vaecoId.'@gmail.com');
        $customerModel->setVaecoId($vaecoId);
        $customerModel->setUsername('chưa có');
        $customerModel->setPhone($phone);
        $customerModel->setPosition($position);
        $customerModel->setDivision($division);
        $customerModel->setDob($dob);
        $customerModel->setGender($gender);
        $customerModel->setJointdate($jointDate);
        $customerModel->setPob($pob);
        $customerModel->setIdNumber($idNumber);
        $customerModel->setIdDate($idDate);
        $customerModel->setIdPlace($idPlace);
        $customerModel->setCurrentAddress($address);

        $customerModel->setStaffFunction(null);
        $customerModel->setStaffBasic(null);

        $customerModel->setGroupId($groupId);

        $res = $customerModel->save();

        if($res){
            echo "Done {$i}. {$firstName} {$lastName} br> \n";
        }else {
            echo "Something weng wrong";
        }

        $i++;
    }


}








