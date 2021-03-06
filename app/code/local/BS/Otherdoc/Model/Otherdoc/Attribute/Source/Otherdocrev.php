<?php
/**
 * BS_Otherdoc extension
 * 
 * @category       BS
 * @package        BS_Otherdoc
 * @copyright      Copyright (c) 2015
 */
/**
 * Admin source model for Revision
 *
 * @category    BS
 * @package     BS_Otherdoc
 * @author Bui Phong
 */
class BS_Otherdoc_Model_Otherdoc_Attribute_Source_Otherdocrev extends Mage_Eav_Model_Entity_Attribute_Source_Table
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
        $options =  array(
            array(
                'label' => Mage::helper('bs_otherdoc')->__('00'),
                'value' => 1
            ),
            array(
                'label' => Mage::helper('bs_otherdoc')->__('01'),
                'value' => 2
            ),
            array(
                'label' => Mage::helper('bs_otherdoc')->__('02'),
                'value' => 3
            ),
            array(
                'label' => Mage::helper('bs_otherdoc')->__('03'),
                'value' => 4
            ),
            array(
                'label' => Mage::helper('bs_otherdoc')->__('04'),
                'value' => 5
            ),
            array(
                'label' => Mage::helper('bs_otherdoc')->__('05'),
                'value' => 6
            ),
            array(
                'label' => Mage::helper('bs_otherdoc')->__('06'),
                'value' => 7
            ),
            array(
                'label' => Mage::helper('bs_otherdoc')->__('07'),
                'value' => 8
            ),
            array(
                'label' => Mage::helper('bs_otherdoc')->__('08'),
                'value' => 9
            ),
            array(
                'label' => Mage::helper('bs_otherdoc')->__('09'),
                'value' => 10
            ),
            array(
                'label' => Mage::helper('bs_otherdoc')->__('10'),
                'value' => 11
            ),
        );
        if ($withEmpty) {
            array_unshift($options, array('label'=>'', 'value'=>''));
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
}
