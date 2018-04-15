<?php
/**
 * BS_InstructorApproval extension
 * 
 * @category       BS
 * @package        BS_InstructorApproval
 * @copyright      Copyright (c) 2015
 */
/**
 * InstructorApproval default helper
 *
 * @category    BS
 * @package     BS_InstructorApproval
 * @author Bui Phong
 */
class BS_InstructorApproval_Helper_Data extends Mage_Core_Helper_Abstract
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

    public function getRelatedTraining($vaecoId, $include, $exclude){

        if(is_array($include) && count($include)){
            $customer = Mage::getModel('customer/customer')->getCollection()->addAttributeToFilter('vaeco_id', $vaecoId)->getFirstItem();

            $related = Mage::getModel('bs_staffinfo/training')->getCollection()->addFieldToFilter('staff_id', $customer->getId());

            //get trainee info
            $trainee = Mage::getModel('bs_trainee/trainee')->getCollection()->addAttributeToFilter('vaeco_id', $vaecoId)->getFirstItem();
            $adminStore = Mage_Core_Model_App::ADMIN_STORE_ID;
            $collection = Mage::getResourceModel('bs_trainee/trainee_product_collection')
                ->addTraineeFilter($trainee->getId());

            $collection->joinAttribute('product_name', 'catalog_product/name', 'entity_id', null, 'left', $adminStore);
            $collection->joinAttribute('course_start_date', 'catalog_product/course_start_date', 'entity_id', null, 'left', $adminStore);
            $collection->joinAttribute('course_finish_date', 'catalog_product/course_finish_date', 'entity_id', null, 'left', $adminStore);

            $filter = array();
            $like = array();
            foreach ($include as $in) {
                $filter[] = 'course';
                $like[] = array('like'=>'%'.$in.'%');
            }

            //print_r($collection->getSelect()->__toString()); die;
            $related->addFieldToFilter('course',$like);

            $collection->addFieldToFilter('product_name', $like);

            //$related->addFieldToFilter($filter,$like);

            //$sql = $related->getSelect()->__toString();

            //SELECT `main_table`.* FROM `bs_staffinfo_training` AS `main_table` WHERE (staff_id = '2670') AND ((course LIKE '%A350%') OR (course LIKE '%T.R%'))

            //SELECT `main_table`.* FROM `bs_staffinfo_training` AS `main_table` WHERE (staff_id = '2670') AND (((course LIKE '%A350%') OR (course LIKE '%T.R%')))

            if(is_array($exclude) && count($exclude)){
                foreach ($exclude as $e) {
                    $related->addFieldToFilter('course',array('nlike'=>'%'.$e.'%'));
                    $collection->addFieldToFilter('product_name', array('nlike'=>'%'.$e.'%'));
                }
            }



            $result = array();
            if($related->count()){
                foreach ($related as $item) {
                    $result[] = array(
                        'course'   => $item->getCourse(),
                        'country'   => $item->getOrganization(),
                        'start'     => $item->getStartDate(),
                        'end'       => $item->getEndDate(),
                        'cert'      => $item->getCertificate()

                    );
                }

            }

            if($collection->count()){
                foreach ($collection as $item) {
                    $result[] = array(
                        'course'   => $item->getProductName(),
                        'country'   => 'Vietnam',
                        'start'     => $item->getCourseStartDate(),
                        'end'       => $item->getCourseFinishDate(),
                        'cert'      => $item->getSku()

                    );
                }
            }

            if(count($result)){
                return $result;
            }
        }


        return false;


    }

    public function getCRSInfo($vaecoId, $type, $cat = array('B1','B2','C')){
        $collection = Mage::getModel('bs_certificate/crs')->getCollection();
        $collection->addFieldToFilter('vaeco_id', array('eq'=>$vaecoId));
        $collection->addFieldToFilter('ac_type', array('like'=>$type));
        $collection->addFieldToFilter('category', array('in'=>$cat));

        if($collection->count()){

            $item = $collection->getFirstItem();
            return $item->getCategory().'-'.$item->getAcType().'-'.Mage::getModel('core/date')->date("d/m/Y", $item->getIssueDate());
        }

        return false;

    }


    public function getConductedCourses($vaecoId, $start){


        $collection = Mage::getModel('catalog/product')->getCollection()->addAttributeToSelect('*');
        $collection->addAttributeToFilter('status', 1);
        $collection->addAttributeToFilter('course_finish_date', array('gteq'=>$start));

        if($collection->count()){

            $courses = array();
            $i=1;
            foreach ($collection as $item) {
                $ins = Mage::getModel('bs_instructor/instructor')->getCollection()
                    ->addProductFilter($item->getId())
                    ->addAttributeToFilter('ivaecoid', $vaecoId)
                ;
                if($ins->count()){
                    $course = Mage::getModel('catalog/product')->load($item->getId());
                    $courses[] = $i.'. '.$course->getSku().' ('.Mage::getModel('core/date')->date("d/m/Y", $course->getCourseStartDate()).'--'.Mage::getModel('core/date')->date("d/m/Y", $course->getCourseFinishDate()).')';
                    $i++;
                }
            }

            return implode("<br>", $courses);
        }

        return false;

    }

    public function filterDates($array, $dateFields)
    {
        if (empty($dateFields)) {
            return $array;
        }
        $filterInput = new Zend_Filter_LocalizedToNormalized(array(
            'date_format' => Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT)
        ));
        $filterInternal = new Zend_Filter_NormalizedToLocalized(array(
            'date_format' => Varien_Date::DATE_INTERNAL_FORMAT
        ));

        foreach ($dateFields as $dateField) {
            if (array_key_exists($dateField, $array) && !empty($dateField)) {
                $array[$dateField] = $filterInput->filter($array[$dateField]);
                $array[$dateField] = $filterInternal->filter($array[$dateField]);
            }
        }
        return $array;
    }
}
