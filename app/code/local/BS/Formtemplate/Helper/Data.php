<?php
/**
 * BS_Formtemplate extension
 * 
 * @category       BS
 * @package        BS_Formtemplate
 * @copyright      Copyright (c) 2015
 */
/**
 * Formtemplate default helper
 *
 * @category    BS
 * @package     BS_Formtemplate
 * @author Bui Phong
 */
class BS_Formtemplate_Helper_Data extends Mage_Core_Helper_Abstract
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

    public function getFormtemplate($code){
        $template = Mage::getModel('bs_formtemplate/formtemplate')->getCollection()->addFieldToFilter('template_code', $code)->getFirstItem();
        $file = null;
        if($template->getId()){
            $file = Mage::helper('bs_formtemplate/formtemplate')->getFileBaseDir();
            $file .= $template->getTemplateFile();
        }

        return $file;
    }
}
