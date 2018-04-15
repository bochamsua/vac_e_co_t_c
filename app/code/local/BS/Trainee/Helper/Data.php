<?php
/**
 * BS_Trainee extension
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the MIT License
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/mit-license.php
 * 
 * @category       BS
 * @package        BS_Trainee
 * @copyright      Copyright (c) 2015
 * @license        http://opensource.org/licenses/mit-license.php MIT License
 */
/**
 * Trainee default helper
 *
 * @category    BS
 * @package     BS_Trainee
 * @author      Bui Phong
 */
class BS_Trainee_Helper_Data extends Mage_Core_Helper_Abstract
{
    /**
     * convert array to options
     *
     * @access public
     * @param $options
     * @return array
     * @author Bui Phong
     */
    public function convertOptions($options)
    {
        $converted = array();
        foreach ($options as $option) {
            if (isset($option['value']) && !is_array($option['value']) &&
                isset($option['label']) && !is_array($option['label'])) {
                $converted[$option['value']] = $option['label'];
            }
        }
        return $converted;
    }
    
    public function checkDocwise($vaecoId, $date=null, $isHv = false){
        $docwise = Mage::getModel('bs_docwise/score')->getCollection()->addFieldToFilter('vaeco_id', $vaecoId)->setOrder('expire_date', 'DESC');

        $valid = false;
        if($date == null){
            $date = Mage::getModel('core/date')->date();
        }
        if($docwise->count()){
            foreach ($docwise as $doc) {
                if($doc->getExpireDate() >= $date && $doc->getScore() > 74){
                    $valid = true; break;
                }
            }

        }

        return $valid;

    }
    public function checkBlacklist($traineeId, $startDate){

        $trainee = Mage::getModel('bs_trainee/trainee')->load($traineeId);
        if($trainee->getIsBlacklist()){
            if($trainee->getBlacklistDate() == ''){
                return $trainee->getTraineeName();
            }else {
                $startDate = new DateTime($startDate);
                $releaseDate = new DateTime($trainee->getBlacklistDate());

                if($releaseDate >= $startDate){
                    return $trainee->getTraineeName();
                }

            }

        }

        return false;
    }

    public function checkDuplicate($product, $traineeId){
        $startDate = $product->getCourseStartDate();
        $finishDate = $product->getCourseFinishDate();

        $result = array();

        $collection1 = Mage::getModel('catalog/product')->getCollection()
            ->addAttributeToFilter('course_finish_date', array('gteq'=>$startDate))
            ->addAttributeToFilter('course_finish_date', array('lteq'=>$finishDate))
            ->addAttributeToFilter('entity_id', array('neq'=>$product->getId()));
        if($collection1->count()){
            foreach ($collection1 as $_prod) {
                $trainees = Mage::getModel('bs_trainee/trainee')->getCollection()->addProductFilter($_prod->getId());

                if($trainees->count()){
                    foreach ($trainees as $id) {
                        if($id->getId() == $traineeId){
                            $result[$_prod->getSku()] = $_prod->getSku();
                        }
                    }

                }
            }

        }

        $collection2 = Mage::getModel('catalog/product')->getCollection()
            ->addAttributeToFilter('course_start_date', array('gteq'=>$startDate))
            ->addAttributeToFilter('course_start_date', array('lteq'=>$finishDate))
            ->addAttributeToFilter('entity_id', array('neq'=>$product->getId()));
        if($collection2->count()){
            foreach ($collection2 as $_prod) {
                $trainees = Mage::getModel('bs_trainee/trainee')->getCollection()->addProductFilter($_prod->getId());

                if($trainees->count()){
                    foreach ($trainees as $id) {
                        if($id->getId() == $traineeId){
                            $result[$_prod->getSku()] = $_prod->getSku();
                        }
                    }

                }
            }

        }
        $collection3 = Mage::getModel('catalog/product')->getCollection()
            ->addAttributeToFilter('course_start_date', array('lteq'=>$startDate))
            ->addAttributeToFilter('course_finish_date', array('gteq'=>$finishDate))
            ->addAttributeToFilter('entity_id', array('neq'=>$product->getId()));
        if($collection3->count()){
            foreach ($collection3 as $_prod) {
                $trainees = Mage::getModel('bs_trainee/trainee')->getCollection()->addProductFilter($_prod->getId());

                if($trainees->count()){
                    foreach ($trainees as $id) {
                        if($id->getId() == $traineeId){
                            $result[$_prod->getSku()] = $_prod->getSku();
                        }
                    }

                }
            }

        }

        if(count($result)){
            return array_keys($result);
        }

        return false;

        //$collection3 = Mage::getModel('catalog/product')->getCollection()->addAttributeToFilter(array('course_start_date', 'course_finish_date'), array(array('lteq'=>$startDate), array('lteq'=>$finishDate)));
    }
}
