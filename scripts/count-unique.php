<?php


$lists = getList('unique.txt');

$unique = array_unique($lists);

echo count($unique);





function getList($fileName = 'unique.txt'){
    $danhsach = new SplFileObject($fileName);
    $result = array();
    $i=0;
    while (!$danhsach->eof()) {

        $line = $danhsach->fgets();
        $line = trim($line);
        $line = str_replace("  ", " ", $line);


        $result[$line] = 1;

        $i++;


    }

    $danhsach = null;

    return array_keys($result);
}

function getVaecoIdbyName($name){
    $customers = Mage::getModel('customer/customer')->getCollection()->addNameToSelect()->addAttributeToFilter('name', $name);

    if($customers->count() && $customers->count() == 1){
        $customer = Mage::getModel('customer/customer')->load($customers->getFirstItem()->getId());

        return $customer->getVaecoId();
    }
    return '';
}

function getVaecoIdbyDob($dob){
    $customers = Mage::getModel('customer/customer')->getCollection()->addNameToSelect()->addAttributeToFilter('dob', $dob);

    if($customers->count() && $customers->count() == 1){
        $customer = Mage::getModel('customer/customer')->load($customers->getFirstItem()->getId());

        return $customer->getVaecoId();
    }
    return '';
}