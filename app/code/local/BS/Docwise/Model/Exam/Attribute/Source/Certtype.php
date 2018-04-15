<?php
/**
 * BS_Docwise extension
 * 
 * @category       BS
 * @package        BS_Docwise
 * @copyright      Copyright (c) 2015
 */
/**
 * Admin source model for Certificate Type
 *
 * @category    BS
 * @package     BS_Docwise
 * @author Bui Phong
 */
class BS_Docwise_Model_Exam_Attribute_Source_Certtype extends Mage_Eav_Model_Entity_Attribute_Source_Table
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
    public function getAllOptions($withEmpty = false, $defaultValues = false)
    {
        $options =  array(
            array(
                'label' => Mage::helper('bs_docwise')->__('Aircraft'),
                'value' => 1
            ),
            array(
                'label' => Mage::helper('bs_docwise')->__('Workshop'),
                'value' => 2
            ),
            array(
                'label' => Mage::helper('bs_docwise')->__('Ground Equipment Operation'),
                'value' => 3
            ),
            array(
                'label' => Mage::helper('bs_docwise')->__('Non-Technical'),
                'value' => 4
            ),
            array(
                'label' => Mage::helper('bs_docwise')->__('EASA'),
                'value' => 5
            ),
        );
        if ($withEmpty) {
            //array_unshift($options, array('label'=>'', 'value'=>''));
        }
        return $options;

    }

    /**
     * get options as array
     *
     * @access public
     * @param bool $withEmpty
     * @return string
     * @author Bui Phong
     */
    public function getOptionsArray($withEmpty = false)
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
}
