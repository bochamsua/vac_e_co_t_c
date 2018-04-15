<?php

require_once 'app/Mage.php';

umask(0);
Mage::app();

$storeId = '0';

Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);


$resource = Mage::getSingleton('core/resource');
$writeConnection = $resource->getConnection('core_write');
$readConnection = $resource->getConnection('core_read');


$curriculum    = Mage::getModel('bs_traininglist/curriculum')
    ->getCollection()
    ->addAttributeToFilter('c_code', array('like'=>'AMB-%'))
;

foreach ($curriculum as $c) {

    $subjects = Mage::getModel('bs_subject/subject')->getCollection()->addFieldToFilter('curriculum_id', $c->getId())->setOrder('entity_id', 'ASC');
    if($subjects->count()){
        $i=10;
        foreach ($subjects as $sub) {
            $title = $sub->getSubjectName();

            $code = getShortcode($title);

            //save position
            $sub->setSubjectShortcode($code)->save();



            $i += 10;
        }

    }



}

function getShortcode($title){
    $title = "moke".$title;
    $title = strtolower($title);

    $code = '';

    if(strpos($title, "english")){
        $code = 'TAE';
    }

    for($i=20; $i>1; $i--){
        if(strpos($title, "module ".$i)){
            if($i==11){
                if(strpos($title, "airframe")){
                    $code = 'M'.$i.'-A'; break;
                }elseif(strpos($title, "electrical")){
                    $code = 'M'.$i.'-E'; break;
                }elseif(strpos($title, "avionic")){
                    $code = 'M'.$i.'-AV'; break;
                }elseif(strpos($title, "- systems part")){
                    $code = 'M'.$i.'-S'; break;
                }
            }else {
                $code = 'M'.$i; break;
            }

        }
    }

    if($code != ''){
        return $code;
    }

    return '';
}

function getExamHour($subjectHour){
    $hour = 0;
    if($subjectHour > 0 && $subjectHour <= 30){
        $hour =  0.5;
    }elseif($subjectHour > 30 && $subjectHour <= 60){
        $hour =  1;
    }elseif($subjectHour > 60 && $subjectHour <= 90){
        $hour =  1.5;
    }elseif($subjectHour > 90 && $subjectHour <= 120){
        $hour =  2;
    }elseif($subjectHour > 120 && $subjectHour <= 150){
        $hour =  2.5;
    }if($subjectHour > 120 && $subjectHour <= 180){
        $hour =  3;
    }elseif($subjectHour > 180 && $subjectHour <= 210){
        $hour =  3.5;
    }elseif($subjectHour > 210 && $subjectHour <= 240){
        $hour =  4;
    }elseif($subjectHour > 240 && $subjectHour <= 270){
        $hour =  4.5;
    }elseif($subjectHour > 270 && $subjectHour <= 300){
        $hour =  5;
    }

    if($hour > 0){
        return $hour;
    }

    return false;
}

function generateRandomString($length = 3) {
    return substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
}

//echo "<pre>";
//print_r($curs);










