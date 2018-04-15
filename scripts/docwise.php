<?php
require_once 'app/Mage.php';

umask(0);
Mage::app();

$storeId = '0';

Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);



$resource = Mage::getSingleton('core/resource');
$writeConnection = $resource->getConnection('core_write');
$readConnection = $resource->getConnection('core_read');

$tableCatProd = $resource->getTableName('catalog_category_product');





$lists = getList('docwise.txt');

$files = array();

$i=1;
foreach ($lists as $item) {


    $vaecoId = trim($item[0]);
    $scores = trim($item[1]);
    $examDate = trim($item[2]);;
    $expireDate = trim($item[3]);
    $certNo = trim($item[4]);
    $dispatch = trim($item[5]);


    if ($examDate != '') {
        $examDate = DateTime::createFromFormat('j-M-y', $examDate)->format('Y-m-d');
    }

    $trainee = Mage::getModel('bs_docwise/trainee')->getCollection()->addFieldToFilter('vaeco_id', $vaecoId)->getFirstItem();
    if ($trainee->getId()) {
        $score = Mage::getModel('bs_docwise/score')->getCollection()->addFieldToFilter('trainee_id', $trainee->getId())->setOrder('exam_date', 'DESC')->getFirstItem();

        if ($score->getId()) {
            $scoreDate = new DateTime($score->getExamDate());//->format('Y-m-d');
            $scoreDate = $scoreDate->format('Y-m-d');
            $sc = $score->getScore();

            if ($scoreDate != $examDate) {
                echo "Check: " . $vaecoId."-OLD:".$scores."-NEW:".$sc."-OLD Date:".$examDate."-NEW Date:".$scoreDate."<br> \n";
            }
        }

    }
}




function getList($fileName = 'docwise.txt'){
    $danhsach = new SplFileObject($fileName);
    $result = array();
    $i=0;
    while (!$danhsach->eof()) {

        $line = $danhsach->fgets();
        $line = trim($line);
        $line = str_replace("  ", " ", $line);
        $array = explode("\t", $line);

        $result[] = $array;

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