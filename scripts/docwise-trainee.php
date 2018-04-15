<?php
require_once 'app/Mage.php';

umask(0);
Mage::app();

$storeId = '0';

Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);



$resource = Mage::getSingleton('core/resource');
$writeConnection = $resource->getConnection('core_write');
$readConnection = $resource->getConnection('core_read');

$tableScore = $resource->getTableName('bs_docwise_scores');





$scores = $readConnection->fetchAll("SELECT * FROM `bs_docwise_scores` WHERE vaeco_id LIKE 'VAE%'");

$result = array();
foreach ($scores as $item) {
    $vaecoId = $item['vaeco_id'];

    $result[$vaecoId][] = $item;

}
$i=1;
foreach ($result as $key => $values) {
    $vaecoId = $key;
    $name = $values[0]['trainee_name'];
    $dob = getDobByVaecoId($vaecoId);
    if(!$dob){
        $dob = '';
    }

    $trainee = Mage::getModel('bs_docwise/trainee')->setTraineeName($name)->setVaecoId($vaecoId)->setDob($dob)->save();
    $traineeId = $trainee->getId();

    foreach ($values as $item) {
        $score = Mage::getModel('bs_docwise/score');
        $score->setTraineeId($traineeId)
            ->setTraineeName($name)
            ->setVaecoId($vaecoId)
            ->setScore($item['score'])
            ->setCertNo($item['cert_no'])
            ->setExamDate($item['exam_date'])
            ->setExpireDate($item['expire_date'])
            ->setQuestionNo($item['question_no'])

            ;
        $score->save();
    }

    echo $i."-";
    $i++;

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
    }elseif ($customers->count() > 1){
        $result = array();
        foreach ($customers as $cus) {
            $customer = Mage::getModel('customer/customer')->load($cus->getId());
            $result[] = $customer->getVaecoId();
       }
        return $result;

    }else {
        return 'RETIRED';
    }
    return '';
}

function getVaecoIdbyDob($dob){
    $customers = Mage::getModel('customer/customer')->getCollection()->addNameToSelect()->addAttributeToFilter('dob', $dob);

    if($customers->count() && $customers->count() == 1){
        $customer = Mage::getModel('customer/customer')->load($customers->getFirstItem()->getId());

        return $customer->getVaecoId();
    }elseif ($customers->count() > 1){
        return '';
    }else {
        return 'RETIRED';
    }
    return '';
}

function getDobByVaecoId($vaecoId){
    $customers = Mage::getModel('customer/customer')->getCollection()->addAttributeToFilter('vaeco_id', $vaecoId);

    if($customers->count() && $customers->count() == 1){
        $customer = Mage::getModel('customer/customer')->load($customers->getFirstItem()->getId());

        return $customer->getDob();
    }
    return false;
}