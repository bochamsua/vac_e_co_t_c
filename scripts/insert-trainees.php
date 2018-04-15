<?php
require_once 'app/Mage.php';

umask(0);
Mage::app();

$storeId = '0';

Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);



$resource = Mage::getSingleton('core/resource');
$writeConnection = $resource->getConnection('core_write');
$readConnection = $resource->getConnection('core_read');

$tableTrainee = $resource->getTableName('bs_trainee/trainee');

$list = getList();

if(count($list)){
    foreach ($list as $l) {
        $model = Mage::getModel('bs_trainee/trainee');
        $data = array(
            'trainee_name'=>$l['name'],
            'trainee_code'=>$l['id'],
            'status'=>'1',
            'attribute_set_id'=>'16',
        );
        $model->addData($data);
        try {

            $model->save();
            echo "Done {$l['name']} <br>";
        }catch (Exception $e){
            echo $e->getMessage();
        }
    }

}

function getList($fileName = 'trainees.txt'){
    $list = new SplFileObject($fileName);
    $result = array();
    while (!$list->eof()) {
        $line = $list->fgets();
        $line = trim($line);
        $line = str_replace("  ", " ", $line);
        $array = explode("\t", $line);

        $username = null;
        $id = null;
        $name = null;
        if(count($array) && count($array) > 1){
            $name = trim($array[0]);
            $id = trim($array[1]);
            if($id != ''){

                $result[] = array(
                    'name'=>$name,
                    'id'=>$id,
                );
            }


        }
    }

    $list = null;

    return $result;
}








