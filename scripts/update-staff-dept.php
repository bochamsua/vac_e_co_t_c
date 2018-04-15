<?php
die('Do not run again!');
require_once '../app/Mage.php';

umask(0);
Mage::app();

$storeId = '0';

Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);


$resource = Mage::getSingleton('core/resource');
$writeConnection = $resource->getConnection('core_write');
$readConnection = $resource->getConnection('core_read');


//Dept Id
$groupId = 22;//CIMC - Noi that

$list = getList();

$i=1;
$j = 1;

foreach ($list as $line) {

    $vaecoId = $line[1];
    $name = $line[2];
    $name = explode(" ", $name);
    $firstName = $name[0];
    unset($name[0]);
    $lastName = implode(" ", $name);


    $division = $line[3];
    $position = $line[4];

    $dob = $line[5];
    $dob = handleDate($dob);

    $joinDate = $line[6];
    $joinDate = handleDate($joinDate);

    $gender = trim($line[7]);
    if($gender == 'Nam'){
        $gender = 1;
    }else {
        $gender = 2;
    }


    $staff = Mage::getModel('customer/customer')->getCollection()->addAttributeToFilter('vaeco_id', $vaecoId)->getFirstItem();
    if($staff->getId()){//if not exist
        $customerModel = Mage::getModel('customer/customer')->load($staff->getId());

        $customerModel->setGroupId($groupId);

        $customerModel->setPosition($position);
        $customerModel->setDivision($division);

        $res = $customerModel->save();

        if($res){
            echo "Done {$i}. {$vaecoId} br> \n";
        }else {
            echo "Something weng wrong";
        }
        $i++;
    }else {
        $customerModel = Mage::getModel('customer/customer');

        $customerModel->setFirstname($firstName);
        $customerModel->setLastname($lastName);
        $customerModel->setEmail($vaecoId.'@gmail.com');
        $customerModel->setVaecoId($vaecoId);
        $customerModel->setUsername('chưa có');
        $customerModel->setPhone('?');
        $customerModel->setPosition($position);
        $customerModel->setDivision($division);
        $customerModel->setDob($dob);
        $customerModel->setJointdate($joinDate);
        $customerModel->setGender($gender);

        $customerModel->setStaffFunction(null);
        $customerModel->setStaffBasic(null);


        $customerModel->setGroupId($groupId);

        $res = $customerModel->save();

        if($res){
            echo "Added {$j}. {$vaecoId} br> \n";
        }else {
            echo "Something weng wrong";
        }

        $j++;
    }


}


function getList($fileName = 'vaecoids.txt'){
    $danhsach = new SplFileObject($fileName);
    $result = array();
    while (!$danhsach->eof()) {
        $line = $danhsach->fgets();
        $line = trim($line);
        $line = explode("\t", $line);
        $result[] = $line;
    }

    $danhsach = null;

    return $result;
}

function handleDate($str){
    $str = explode("/", $str);
    if(count($str) == 2){//month/year
        $day = '01';
        $month = trim($str[0]);
        $year = trim($str[1]);
        if(strlen($year) == 2){
            $year = '19'.$year;
        }
    }else {//day/month/year OR month/day/year
        $year = trim($str[2]);
        if(strlen($year) == 2){
            $year = '19'.$year;
        }
        $check = (int)$str[0];
        if($check > 12){
            $day = trim($str[0]);
            $month = trim($str[1]);
        }else {
            $day = trim($str[1]);
            $month = trim($str[0]);
        }

        if($day < 10){
            $day = '0'.$day;
        }

        if((int)$month < 10){
            $month = '0'.$month;
        }
    }

    return $year.'-'.$month.'-'.$day;
}






