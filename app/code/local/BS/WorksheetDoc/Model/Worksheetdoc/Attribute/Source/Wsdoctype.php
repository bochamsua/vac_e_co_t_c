<?php
/**
 * BS_WorksheetDoc extension
 * 
 * @category       BS
 * @package        BS_WorksheetDoc
 * @copyright      Copyright (c) 2015
 */
/**
 * Admin source model for Document Type
 *
 * @category    BS
 * @package     BS_WorksheetDoc
 * @author      Bui Phong
 */
class BS_WorksheetDoc_Model_Worksheetdoc_Attribute_Source_Wsdoctype extends Mage_Eav_Model_Entity_Attribute_Source_Table
{
    /**
     * get possible values
     *
     * @access public
     * @param bool $withEmpty
     * @param bool $defaultValues
     * @return array
     * @author Bui Phong
     */
    public function getAllOptions($withEmpty = true, $defaultValues = false)
    {
        $source  = Mage::getModel('eav/config')->getAttribute('customer', 'cdoc_type');
        return $source->getSource()->getAllOptions($withEmpty, $defaultValues);
    }

    /**
     * get options as array
     *
     * @access public
     * @param bool $withEmpty
     * @return string
     * @author Bui Phong
     */
    public function getOptionsArray($withEmpty = true)
    {
        $options = array();
        foreach ($this->getAllOptions($withEmpty) as $option) {
            $options[$option['value']] = $option['label'];
        }
        return $options;
    }

    /**
     * get option text
     *
     * @access public
     * @param mixed $value
     * @return string
     * @author Bui Phong
     */
    public function getOptionText($value)
    {
        $options = $this->getOptionsArray();
        if (!is_array($value)) {
            $value = explode(',', $value);
        }
        $texts = array();
        foreach ($value as $v) {
            if (isset($options[$v])) {
                $texts[] = $options[$v];
            }
        }
        return implode(', ', $texts);
    }

    public function getOptionFormatted($value){
        $text = $this->getOptionText($value);
        $text = strtolower($text);
        $text = preg_replace('/[^a-z0-9_\\-\\.]+/i', '_', $text);

        return $text;
    }
}
