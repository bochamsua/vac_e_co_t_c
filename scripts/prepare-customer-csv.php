<?php
require_once 'app/Mage.php';

umask(0);
Mage::app();

$storeId = '0';

Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);



$resource = Mage::getSingleton('core/resource');
$writeConnection = $resource->getConnection('core_write');
$readConnection = $resource->getConnection('core_read');

$tablestaff = $resource->getTableName('bs_vaeco_staff');

$staff = $readConnection->fetchAll("select * from {$tablestaff}");

//"base","bosuauss@gmail.com","BLD Cty","Bo","Sua","bochamsuas","VAE02907s","09129609800","Kỹ sư0","Phòng Giáo vụ0","Male","1985-03-31 00:00:00","2014-07-01 00:00:00","admin","BLD Cty"
foreach ($staff as $line){
    $group = getGroup($line['department']);
    $name = $line['fullname'];
    $name = explode(" ", $name);
    $firstname = $name[0];
    $lastName = "";
    for ($i=1; $i < count($name); $i++){
        $lastName .= $name[$i]." ";
    }
    $lastName = trim($lastName);

    echo "\"base\",";
    echo "\"{$line['username']}@gmail.com\",";
    echo "\"{$group}\",";
    echo "\"{$firstname}\",";
    echo "\"{$lastName}\",";
    echo "\"{$line['username']}\",";
    echo "\"{$line['vaeco_id']}\",";
    echo "\"{$line['phone']}\",";
    echo "\"{$line['position']}\",";
    echo "\"{$line['division']}\",";
    echo "\"{$line['gender']}\",";
    echo "\"{$line['birthday']}\",";
    echo "\"{$line['jointdate']}\",";
    echo "\"admin\",";
    echo "\"{$group}\"<br>";



}

function getGroup($name){
    $resource = Mage::getSingleton('core/resource');
    $writeConnection = $resource->getConnection('core_write');
    $readConnection = $resource->getConnection('core_read');

    $tableGroup = $resource->getTableName('customer_group');

    $code = $readConnection->fetchOne("SELECT customer_group_code FROM {$tableGroup} WHERE customer_group_name = '{$name}'");

    if($code){
        return $code;
    }
    return "NOT LOGGED IN";

}

