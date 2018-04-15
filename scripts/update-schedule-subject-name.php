<?php

require_once '../app/Mage.php';

umask(0);
Mage::app();

$storeId = '0';

Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);


$schedule = Mage::getModel('bs_register/schedule')->getCollection();

foreach ($schedule as $item) {
    $subjectIds = $item->getScheduleSubjects();
    $subjectIds = explode(",", $subjectIds);

    $name = array();
    foreach ($subjectIds as $subjectId) {
        $subject = Mage::getModel('bs_subject/subject')->load($subjectId);
        if($subject->getId()){
            if($subject->getSubjectShortcode() != ''){
                $name[] = $subject->getSubjectShortcode();
            }else {
                $name[] = $subject->getSubjectName();
            }

        }


    }

    $name = implode(", ", $name);

    $res = $item->setScheduleSubjectNames($name)->save();
    if($res){
        echo "Done \n <br>";
    }else {
        echo "Error \n <br>";
    }

}









