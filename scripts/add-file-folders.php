<?php
die ('die');
require_once 'app/Mage.php';

umask(0);
Mage::app();

$storeId = '0';

Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);

$cabinets = Mage::getModel('bs_logistics/filecabinet')->getCollection();
if($cabinets->count()){
    foreach ($cabinets as $cab) {
        $cab = Mage::getModel('bs_logistics/filecabinet')->load($cab->getId());

        $code = $cab->getFilecabinetCode();

        for($i=1; $i < 4; $i++){
            for($j=1; $j<16; $j++){
                if($j < 10){
                    $j = '0'.$j;
                }

                $filefolder = Mage::getModel('bs_logistics/filefolder');

                $info = $code.$i.'-'.$j;
                $data = array(
                    'filefolder_name'=>$info,
                    'filefolder_code'=>$info
                );
                $filefolder->addData($data);

                try {
                    $filefolder->save();
                    echo 'Done: '.$info.$i.'-'.$j.'<br>';
                }catch (Exception $e){
                    echo $e->getMessage();
                }

            }


        }
    }

}









