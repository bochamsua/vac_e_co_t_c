<?php
/**
 * BS_KST extension
 * 
 * @category       BS
 * @package        BS_KST
 * @copyright      Copyright (c) 2015
 */
/**
 * KST default helper
 *
 * @category    BS
 * @package     BS_KST
 * @author Bui Phong
 */
class BS_KST_Helper_Data extends Mage_Core_Helper_Abstract
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

    public function checkPermission($userName){
        //Get all valid username, user_id with course_id in all rules
        $shortcut = Mage::getModel('bs_shortcut/shortcut')->getCollection()->addFieldToFilter('shortcut', 'kst_users')->getFirstItem();
        $courseIds = array();

        if($shortcut->getId()){
            $content = $shortcut->getDescription();
            $content = explode("\r\n", $content);
            $userRole = array();
            if(count($content)){
                foreach ($content as $item) {
                    $item = str_replace(" ","",$item);
                    $item = explode("-",$item);
                    if(count($item) == 2){
                        $user = $item[0];
                        $course = $item[1];
                        $course = explode(",", $course);

                        foreach ($course as $item) {
                            $userRole[$user][] = $item;
                        }
                    }
                }

                foreach ($userRole as $key => $value) {
                    if($key == $userName){
                        $value = array_unique($value);
                        $courseIds = $value;
                    }
                }
            }
        }

        return $courseIds;
    }
}
