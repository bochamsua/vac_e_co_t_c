<?php


$list1 = getList('list1.txt');
$list2 = getList('list2.txt');

$list1 = array_unique($list1);
$list2 = array_unique($list2);


foreach ($list1 as $item) {
    if (!in_array($item, $list2)) {
        echo $item . "<br>\n";
    }
}


function getList($fileName = 'unique.txt'){
    $danhsach = new SplFileObject($fileName);
    $result = array();
    $i=0;
    while (!$danhsach->eof()) {

        $line = $danhsach->fgets();
        $line = trim($line);
        $line = str_replace("  ", " ", $line);


        $result[] = $line;

        $i++;


    }

    $danhsach = null;

    return $result;
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