<?php
require_once 'app/Mage.php';

umask(0);
Mage::app();

$storeId = '0';

Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);



$resource = Mage::getSingleton('core/resource');
$writeConnection = $resource->getConnection('core_write');
$readConnection = $resource->getConnection('core_read');

$tableTrainee = $resource->getTableName('bs_instructor/instructor');

$list = getList();

if(count($list)){
    foreach ($list as $l) {
        $model = Mage::getModel('bs_instructor/instructor');

        $customer = Mage::getModel('customer/customer')->getCollection()->addAttributeToFilter('vaeco_id', array('eq'=>$l))->getFirstItem();
        if($customer){
            $cus = Mage::getModel('customer/customer')->load($customer->getId());
            $name = $cus->getName();
            $data = array(
                'iname'=>$name,
                'ivaecoid'=>$l,
                'status'=>'1',
                'attribute_set_id'=>'15',
            );
            $model->addData($data);
            try {

                $model->save();
                echo "Done {$name} <br>";
            }catch (Exception $e){
                echo $e->getMessage();
            }
        }

    }

}

function getList($fileName = 'instructors.txt'){
    $list = new SplFileObject($fileName);
    $result = array();
    while (!$list->eof()) {
        $line = $list->fgets();
        $line = trim($line);


        $result[] = $line;
    }

    $list = null;

    $result = array_unique($result);
    return $result;
}








