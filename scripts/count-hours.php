<?php

require_once '../app/Mage.php';

umask(0);
Mage::app();

$storeId = '0';

Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);


$courses = Mage::getModel('catalog/product')->getCollection()->addFieldToFilter('status', 1)->addFieldToFilter('entity_id', array('gt' => 58))->addAttributeToFilter('course_start_date', array('to'=>'2015-12-31'));

$totalTrainee = 0;
$totalHours = 0;

foreach ($courses as $c) {
    $course = Mage::getModel('catalog/product')->load($c->getId());

    $trainee = Mage::getModel('bs_trainee/trainee')->getCollection()->addProductFilter($c->getId());

    if($trainee->count()){
        $numberTrainee = $trainee->count();
    }else {
        $numberTrainee = (int)$course->getNumberTrainees();
    }
    $totalTrainee += $numberTrainee;

    //get total hours
    $hour = 0;
    $schedule = Mage::getModel('bs_register/schedule')->getCollection()->addFieldToFilter('course_id', $c->getId());
    if($schedule->count()){

        foreach ($schedule as $item) {
            $hour += (int)$item->getScheduleHours();
        }
    }

    $totalHours += $numberTrainee *  $hour;
}

echo $totalHours;


?>
