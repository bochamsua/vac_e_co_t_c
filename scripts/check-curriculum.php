<?php

require_once 'app/Mage.php';

umask(0);
Mage::app();

$storeId = '0';

Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);


$resource = Mage::getSingleton('core/resource');
$writeConnection = $resource->getConnection('core_write');
$readConnection = $resource->getConnection('core_read');


$curriculums    = Mage::getModel('bs_traininglist/curriculum')->getCollection();
$i=1;
foreach ($curriculums as $c) {
    $curriculum = Mage::getModel('bs_traininglist/curriculum')->load($c->getId());



    $duration = (float)$curriculum->getCDuration();

    //get subjects
    $totalSubHour = 0;
    $err = "";
    $subjects = Mage::getModel('bs_subject/subject')->getCollection()->addFieldToFilter('curriculum_id', $curriculum->getId());
    if($subjects->count()){
        foreach ($subjects as $sub) {
            $currentHour = (float)$sub->getSubjectHour();
            $subName = $sub->getSubjectName();
            $totalSubHour += $currentHour;

            //get subject content
            $totalSubConHour = 0;
            $subjectContents = Mage::getModel('bs_subject/subjectcontent')->getCollection()->addFieldToFilter('subject_id', $sub->getId());
            if($subjectContents->count()){
                foreach ($subjectContents as $subcon) {
                    $totalSubConHour += (float)$subcon->getSubconHour();
                }

                if($totalSubConHour != $currentHour){
                    $err .= "Subject {$subName}: Total hours of all subcontent (<strong>{$totalSubConHour} hours</strong>) is different from the subject hour (<strong>{$currentHour} hours</strong>)! <br>";
                }


            }
        }

    }
    if($duration != $totalSubHour){
        echo $i.'. '.$curriculum->getCCode()."<br>";
        echo ($err !='')? $err:"";
        echo "Total hours of all subjects (<strong>{$totalSubHour} hours</strong>) is different from the curriculum duration (<strong>{$duration} hours</strong>)! <br>";
        echo "<br>";
        $i++;
    }




}


//echo "<pre>";
//print_r($curs);










