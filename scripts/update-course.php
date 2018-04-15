<?php
require_once 'app/Mage.php';

umask(0);
Mage::app();

$storeId = '0';

Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);


/*
 *
<option value="227" selected="selected">On going</option>
<option value="228">Finished</option>
<option value="229">Not yet conducting</option>

 */
$currentDate = date("Y-m-d", Mage::getModel('core/date')->timestamp(time()));
$products = Mage::getModel('catalog/product')->getCollection()->addAttributeToFilter('course_status', array('nin'=>array(228)));
if($products->count()){
    foreach ($products as $p) {
        $product = Mage::getModel('catalog/product')->load($p->getId());
        $courseStart = date("Y-m-d", Mage::getModel('core/date')->timestamp($product->getCourseStartDate()));

        $courseFinish = date("Y-m-d", Mage::getModel('core/date')->timestamp($product->getCourseFinishDate()));

        if($courseFinish < $currentDate){

            $product->setCourseStatus(228)->save();

        }elseif($courseStart > $currentDate){
            $product->setCourseStatus(229)->save();
        }elseif($courseStart <= $currentDate){
            $product->setCourseStatus(227)->save();
        }
    }
}

echo "Done";

